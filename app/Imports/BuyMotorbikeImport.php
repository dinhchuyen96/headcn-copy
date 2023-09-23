<?php

namespace App\Imports;

use App\Models\Motorbike;
use App\Models\District;
use App\Models\MasterData;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Province;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\Supplier;
use App\Models\HMSReceivePlan;
use App\Models\Mtoc;
use Illuminate\Support\Collection;
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
use DateTime;
use App\Enum\EMotorbike;

class BuyMotorbikeImport implements ToCollection, WithStartRow, SkipsEmptyRows, WithValidation
{
    public $check_hvn_plan;
    use SkipsErrors;
    /**
     * @param Collection $collection
     */


    public function collection(Collection $collection)
    {
        $supplier_code = [];
        foreach ($collection as $row) {
            $row[0] = trim($row[0]);
            $row[1] = trim($row[1]);
            $row[2] = trim($row[2]);
            $row[3] = trim($row[3]);
            $row[4] = trim($row[4]);
            $row[5] = trim($row[5]);
            $row[6] = trim($row[6]);
            $row[7] = trim($row[7]);
            $row[8] = trim($row[8]);
            $row[9] = trim($row[9]);
            $row[10] = trim($row[10]);
            $row[11] = trim($row[11]);
            $row[12] = trim($row[12]);
            $supplier_code[] = $row[0];
        }
        $supplier_code = array_unique(array_filter($supplier_code));
        $rowNo = 1;
        OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT_IMPORT_BUY)->where('admin_id', auth()->id())->delete();
        foreach ($collection as $row) {
            $motorbike = Motorbike::where('chassic_no', $row[4])->where('engine_no', $row[5])->first();
            if ($motorbike) {
                session()->put('error', 'Dòng ' . $rowNo . ': Cặp số khung - Số máy đã được nhập về hoặc đã bị bán');
                return 0;
            }
            $warehouse = Warehouse::where('name', $row[3])->first();
            if (!$warehouse) {
                session()->put('error', 'Dòng ' . $rowNo . ': Không tìm thấy kho tương ứng');
                return 0;
            }
            $order_detail = OrderDetail::where('chassic_no', $row[4])->where('engine_no', $row[5])->where('type', EOrderDetail::TYPE_NHAP)->where('status', EOrderDetail::STATUS_SAVE_DRAFT)->first();

            if ($order_detail) {
                $order_detail->delete();
            }

            if($this->check_hvn_plan == true)
            {
                $hms_plan = HMSReceivePlan::where('chassic_no', $row[4])->where('engine_no', $row[5])->first();

                if (!$hms_plan && $row[0] == 'HVN') {
                    session()->put('error', 'Dòng ' . $rowNo . ': Cặp số khung - Số máy không có trong kế hoạch nhập của Honda Việt Nam');
                    return 0;
                }
            }
            
            $order_detail = OrderDetail::where('chassic_no', $row[4])->where('engine_no', $row[5])->where('status', EOrderDetail::STATUS_SAVE_DRAFT_IMPORT_BUY)->where('admin_id', auth()->id())->first();
            if ($order_detail) {
                session()->put('error', 'Dòng ' . $rowNo . ': Cặp số khung - Số máy trùng với dòng trước đó');
                return 0;
            }
            if (!is_numeric($row[11])) {
                session()->put('error', 'Dòng ' . $rowNo . ': Giá xe không phải là số nguyên');
                return 0;
            }
            if (DateTime::createFromFormat('d/m/Y', $row[12]) === false) {
                session()->put('error', 'Dòng ' . $rowNo . ': Sai kiểu dữ liệu ngày nhập (ngày/tháng/năm)');
                return 0;
            }

            OrderDetail::create([
                'chassic_no' => $row[4],
                'engine_no' => $row[5],
                'status' => EOrderDetail::STATUS_SAVE_DRAFT_IMPORT_BUY,
                'admin_id' => auth()->id(),
            ]);
            $rowNo++;
        }
        OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT_IMPORT_BUY)->where('admin_id', auth()->id())->delete();
        foreach ($collection as $row) {
            $supplier = Supplier::firstOrCreate([
                'code' => $row[0],
            ], [
                'name' => $row[1],
                'address' => $row[2],
            ]);
            $warehouse = Warehouse::where('name', $row[3])->first();
            $motorbike =  new Motorbike();
            $motorbike->price = $row[11];
            $motorbike->chassic_no = $row[4];
            $motorbike->engine_no = $row[5];
            $motorbike->model_code = $row[6];
            $motorbike->model_list = $row[7];
            $motorbike->model_type = $row[8];
            $motorbike->color = $row[10];
            $motorbike->supplier_id = $supplier->id;
            $motorbike->status = EMotorbike::NEW_INPUT;
            $strdate = $row[12];
            $buydate = DateTime::createFromFormat('d/m/Y', $strdate);
            //$order_detail->buy_date =$buydate->format('Y-m-d');//reFormatDate($buydate, 'Y-m-d');
            $motorbike->buy_date =$buydate->format('Y-m-d');
            //$motorbike->buy_date = date("Y-m-d", strtotime($row[12]));
            $motorbike->warehouse_id = $warehouse->id;
            $motorbike->mtoc_id = null;
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
            $order_detail->actual_price = $motorbike->price; // Giá thực tế = giá nhập vào
            $order_detail->listed_price = $motorbike->price; // Giá in hóa đơn = giá nhập vào
            $order_detail->warehouse_id = $warehouse->id;
            $order_detail->buy_date = $motorbike->buy_date;
            $order_detail->status = EOrderDetail::STATUS_SAVE_DRAFT_IMPORT_BUY;
            $order_detail->category = EOrderDetail::CATE_MOTORBIKE;
            $order_detail->type = EOrderDetail::TYPE_NHAP;
            $order_detail->admin_id = auth()->id();
            $order_detail->save();
        }
        foreach ($supplier_code as $val) {
            $supplier = Supplier::where('code', $val)->first();
            $order = new Order();
            $order->created_by = auth()->id();
            $order->category = EOrder::CATE_MOTORBIKE;
            $order->order_type = EOrder::ORDER_TYPE_BUY;
            $order->type = EOrder::TYPE_NHAP;
            $order->supplier_id = $supplier->id;
            $order->status = EOrder::STATUS_UNPAID;
            $order->save();

            OrderDetail::leftJoin('motorbikes', 'motorbikes.id', '=', 'order_details.product_id')
                ->where('supplier_id', $supplier->id)
                ->where('order_details.status', EOrderDetail::STATUS_SAVE_DRAFT_IMPORT_BUY)
                ->where('order_details.type', EOrderDetail::TYPE_NHAP)
                ->where('order_details.category', EOrderDetail::CATE_MOTORBIKE)
                ->where('is_out', EMotorbike::NOT_OUT)
                ->where('order_details.admin_id', auth()->id())->update([
                    'order_details.status' => EOrderDetail::STATUS_SAVED,
                    'order_details.order_id' => $order->id,
                    'motorbikes.order_id' => $order->id,
                ]);
            $order->update([
                'total_money' => $order->totalPrice(),
                'total_items' => $order->totalItem(),
                'order_no' => 'ORDER_' . $order->id,
            ]);
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
            '7' => 'required',
            '8' => 'required',
            '9' => 'required',
            '10' => 'required',
            '11' => 'required',
            '12' => 'required|date_format:"d/m/Y"|before:tomorrow',
        ];
    }
    public function customValidationMessages()
    {
        return [
            '0.required' => 'Cột mã nhà cung cấp không được bỏ trống',
            '1.required' => 'Cột tên nhà cung cấp không được bỏ trống',
            '3.required' => 'Cột mã kho không được bỏ trống',
            '4.required' => 'Cột Số khung không được bỏ trống',
            '5.required' => 'Cột Số máy không được bỏ trống',
            '6.required' => 'Cột tên đời xe tế không được bỏ trống',
            '7.required' => 'Cột danh mục đời xe không được bỏ trống',
            '8.required' => 'Cột phân loại xe không được bỏ trống',
            '9.required' => 'Cột màu xe không được bỏ trống',
            '10.required' => 'Cột màu xe không được bỏ trống',
            '11.required' => 'Cột giá niêm yết không được bỏ trống',
            '12.required' => 'Cột ngày nhập không được bỏ trống',
            '12.date' => 'Cột ngày nhập không không đúng định dạng d-m-Y',
            '12.before' => 'Cột ngày nhập không được lớn hơn ngày hiện tại',
        ];
    }
    public function  __construct($check_hvn_plan)
    {
        $this->check_hvn_plan= $check_hvn_plan;
    }
}
