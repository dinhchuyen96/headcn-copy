<?php

namespace App\Imports;

use App\Enum\EWarehouse;
use App\Models\Motorbike;
use App\Models\District;
use App\Models\MasterData;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Province;
use App\Models\Customer;
use App\Models\Ward;
use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Http\Livewire\Motorbike\BanBuonMotorbike;
use Illuminate\Http\Request;
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Enum\EMotorbike;

class BanBuonMotorbikeImport implements ToCollection, WithStartRow, SkipsEmptyRows, WithValidation
{
    use SkipsErrors;
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        DB::beginTransaction();
        try {
            $customer_code = [];
            OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT_IMPORT_BANBUON)->where('admin_id', auth()->id())->delete();
            $warehouseGS = Warehouse::where('name', EWarehouse::GS)->first();

            foreach ($collection as $row) {
                $row[0] = trim($row[0]);
                $row[1] = trim($row[1]);
                $row[2] = trim($row[2]);
                $row[3] = trim($row[3]);
                $row[4] = trim($row[4]);
                $row[5] = trim($row[5]);
                $row[6] = trim($row[6]);

                $customer_code[] = $row[0];
            }
            $customer_code = array_unique(array_filter($customer_code));
            $rowNo = 1;
            foreach ($collection as $row) {
                $motorbike = Motorbike::where('chassic_no', $row[3])->where('is_out', EMotorbike::NOT_OUT)->where('engine_no', $row[4])->first();
                if (!$motorbike) {
                    session()->put('error', 'Dòng ' . $rowNo . ': Cặp số khung - Số máy không tồn tại trong danh sách nhập');
                    return 0;
                }

                if ($motorbike->warehouse_id == $warehouseGS->id) {
                    session()->put('error', 'Xe máy (' . $row[3] . '|' . $row[4] . ') đang ở trong kho GS nên không thể bán. Hãy chuyển từ kho GS về');
                    return 0;
                }

                $order_detail = OrderDetail::where('chassic_no', $row[3])->where('engine_no', $row[4])->where('type', '!=', EOrderDetail::TYPE_NHAP)->first();
                if ($order_detail && $order_detail->status == EOrderDetail::STATUS_SAVED) {
                    session()->put('error', 'Dòng ' . $rowNo . ': Cặp số khung - Số máy đã được bán');
                    return 0;
                }
                if ($order_detail && $order_detail->status == EOrderDetail::STATUS_SAVE_DRAFT) {
                    $order_detail->delete();
                }
                $order_detail = OrderDetail::where('chassic_no', $row[3])->where('engine_no', $row[4])->where('status', EOrderDetail::STATUS_SAVE_DRAFT_IMPORT_BANBUON)->where('admin_id', auth()->id())->first();
                if ($order_detail) {
                    session()->put('error', 'Dòng ' . $rowNo . ': Cặp số khung - Số máy trùng với dòng trước đó');
                    return 0;
                }
                OrderDetail::create([
                    'chassic_no' => $row[3],
                    'engine_no' => $row[4],
                    'status' => EOrderDetail::STATUS_SAVE_DRAFT_IMPORT_BANBUON,
                    'admin_id' => auth()->id(),
                ]);
                $rowNo++;
            }
            OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT_IMPORT_BANBUON)->where('admin_id', auth()->id())->delete();
            foreach ($collection as $row) {
                $customer = Customer::firstOrCreate([
                    'code' => $row[0],
                ], [
                    'name' => $row[1],
                    'address' => $row[2],
                ]);

                $motorbike = Motorbike::where('chassic_no', $row[3])->where('is_out', EMotorbike::NOT_OUT)->where('engine_no', $row[4])->first();
                $motorbike->customer_id = $customer->id;
                $motorbike->sell_date = date('Y-m-d');
                $motorbike->status = EMotorbike::SOLD;
                $motorbike->save();

                $order_detail = new OrderDetail();
                $order_detail->product_id = $motorbike->id;
                $order_detail->chassic_no = $motorbike->chassic_no;
                $order_detail->engine_no = $motorbike->engine_no;
                $order_detail->model_code = $motorbike->model_code;
                $order_detail->model_type = $motorbike->model_type;
                $order_detail->model_list = $motorbike->model_list;
                $order_detail->color = $motorbike->color;
                $order_detail->price = $motorbike->price;
                $order_detail->vat_price = $row[5];
                $order_detail->actual_price = $row[6];
                $order_detail->status = EOrderDetail::STATUS_SAVE_DRAFT_IMPORT_BANBUON;
                $order_detail->category = EOrderDetail::CATE_MOTORBIKE;
                $order_detail->type = EOrderDetail::TYPE_BANBUON;
                $order_detail->admin_id = auth()->id();
                $order_detail->save();
            }
            foreach ($customer_code as $val) {
                $customer = Customer::where('code', $val)->first();
                $order = new Order();
                $order->created_by = auth()->id();
                $order->category = EOrder::CATE_MOTORBIKE;
                $order->order_type = EOrder::ORDER_TYPE_SELL;
                $order->type = EOrder::TYPE_BANBUON;
                $order->status = EOrder::STATUS_UNPAID;
                $order->customer_id = $customer->id;
                $order->save();
                OrderDetail::leftJoin('motorbikes', 'motorbikes.id', '=', 'order_details.product_id')->where('customer_id', $customer->id)
                    ->where('order_details.status', EOrderDetail::STATUS_SAVE_DRAFT_IMPORT_BANBUON)
                    ->where('order_details.type', EOrderDetail::TYPE_BANBUON)
                    ->where('order_details.category', EOrderDetail::CATE_MOTORBIKE)
                    ->where('is_out', EMotorbike::NOT_OUT)
                    ->where('order_details.admin_id', auth()->id())->update([
                        'order_details.status' => EOrderDetail::STATUS_SAVED,
                        'order_details.order_id' => $order->id,
                    ]);
                $order->update([
                    'total_items' => $order->details->count(),
                    'total_money' => OrderDetail::where('order_id', $order->id)->sum('actual_price'),
                    'order_no' => 'ORDER_' . $order->id,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '0' => 'required',
            '1' => 'required',
            '3' => 'required',
            '4' => 'required',
            '5' => 'required',
            '6' => 'required',
        ];
    }
    public function customValidationMessages()
    {
        return [
            '0.required' => 'Cột mã khách hàng không được bỏ trống',
            '1.required' => 'Cột tên khách hàng không được bỏ trống',
            '3.required' => 'Cột Số khung không được bỏ trống',
            '4.required' => 'Cột Số máy không được bỏ trống',
            '5.required' => 'Cột Giá in hóa đơn lượng phụ tụng không được bỏ trống',
            '6.required' => 'Cột Giá bán thực tế không được bỏ trống',
        ];
    }
}
