<?php

namespace App\Imports;

use App\Models\Accessory;
use App\Models\Customer;
use App\Models\District;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class BanBuonImport implements ToCollection, WithStartRow, WithValidation
{
    use SkipsErrors;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $cus_codes=[];
        $customer_ids=[];
        $warehouse=[];
        $order_ids=[];


        foreach ($collection as $key=> $row)
        {
            $cus_codes[]=$row[0];
            $warehouse[]=$row[6];
        }
        $cus_codes= array_unique(array_filter($cus_codes));
        $warehouse=array_unique(array_filter($warehouse));
        foreach ($collection as $key => $row) {
            if ($row->filter()->isNotEmpty()) {
                $ward_id = '';
                $district_id = '';
                $province_id = '';
                $address = '';
                if ($row[2] != '') {
                    $addressSup = explode(',', $row[2]);
                    krsort($addressSup);
                    foreach ($addressSup as $key => $ad) {
                        $ad = trim($ad);

                        $province = Province::where('name', $ad)->first();
                        if ($province) {
                            $province_id = $province->province_code;
                            unset($addressSup[$key]);
                        }
                        $district = District::where('name', $ad);
                        if ($province_id) $district->where('province_code', $province_id);
                        $district = $district->first();
                        if ($district) {
                            $district_id = $district->district_code;
                            unset($addressSup[$key]);
                        }
                        $ward = Ward::where('ex_ward.name', $ad);
                        if ($district_id) $ward->where('ex_ward.district_code', $district_id);
                        $ward = $ward->first();
                        if ($ward) {
                            $ward_id = $ward->ward_code;
                            unset($addressSup[$key]);
                        }
                    }
                    $address = implode(',', $addressSup);
                }
                if ($row[0] && $row[3]) {
                    $customer = Customer::where('code', trim($row[0]))->first();
                    if (!$customer) {
                        Log::info('');
                        $customer = Customer::create([
                            'code' => trim($row[0]),
                            'name' => $row[1],
                            'address' => $address,
                            'district' => $district_id,
                            'city' => $province_id,
                            'ward' => $ward_id,
                        ]);
                    } else
                        if (in_array(trim($row[0]), $cus_codes)
                            && in_array(trim($row[6]), $warehouse)) {
                            $customer_ids[$row[0]] = [$row[6]];
                        }
                    $accessory = Accessory::where('code', $row[3])->first();
                    if ($accessory) {
                        $accessory->quantity = $accessory->quantity - $row[7];
                        $accessory->save();
                    } else {
                        $accessory = Accessory::create([
                            'code' => $row[3],
                            'name' => $row[4],
                            'admin_id' => auth()->id(),
                        ]);
                    }


                    $order_detail = new OrderDetail();
                    $order_detail->product_id = $accessory->id;
                    $order_detail->code = $accessory->code;
                    $order_detail->quantity = $row[7];
                    $order_detail->vat_price = $row[9];
                    $order_detail->actual_price = $row[10];
                    $order_detail->listed_price = $row[8];
                    $order_detail->admin_id = 0;
                    $order_detail->status = 0;
                    $order_detail->category = 1;
                    $order_detail->type = 1;

                    /**
                     * TUDN 301121
                     * add mor warehouse id and position id in
                     * order detail
                     */
                    $warehouse_id =$accessory->warehouse_id ==null ?0:$accessory->warehouse_id;
                    $position_in_warehouse_id = $accessory->position_in_warehouse_id ==null ?0:$accessory->position_in_warehouse_id;
                    $order_detail->warehouse_id = $warehouse_id;
                    $order_detail->position_in_warehouse_id =$position_in_warehouse_id;

                    //End TUDN 301121

                    $order_detail->save();
                }
            }
            foreach ($customer_ids as $customer_code => $item) {
                $sup = Customer::where('code', $customer_code)->first();
                $order = new Order();
                $order->created_by = auth()->id();
                $order->customer_id = $sup->id;
                $order->status = 2;
                $order->order_type = 1;
                $order->order_type = 1;
                $order->category = 1;
                $order->type = 1;
                $order->save();
                $order_ids[] = $order->id;
                $order_detail_check = OrderDetail::leftJoin('accessories', 'accessories.id', '=', 'order_details.product_id')
                    ->where('order_details.admin_id', 0)->where('category', 1)->where('type', 1)->where('order_details.status', 0)->get();
                if ($order_detail_check) {
                    $order_detail_id = OrderDetail::leftJoin('accessories', 'accessories.id', '=', 'order_details.product_id')->where('order_details.status', 0)
                        ->where('order_details.admin_id', 0)->pluck('order_details.id')->toArray();
                    OrderDetail::whereIn('id', $order_detail_id)->update([
                        'admin_id' => auth()->id(),
                        'order_id' => $order->id,
                        'status' => 1,
                    ]);
                }
            }
            foreach ($order_ids as $order_id) {
                $orderUpdateItem = Order::findOrFail($order_id);
                $orderUpdateItem->total_items = $orderUpdateItem->totalItem();
                $orderUpdateItem->total_money = $orderUpdateItem->totalPriceByType();
                $orderUpdateItem->save();
            }

        }

    }

    public function startRow(): int
    {
        return 2;
    }
    public function rules(): array
    {
        return [
            '0' => 'required|exists:customers,code',
            '3' => 'required',
            '6' => 'required',
            '7' => 'required|integer|gt:0|digits_between:1,7',
            '8' => 'required|integer|gt:0|digits_between:1,9',
            '9' => 'required|integer|gt:0|digits_between:1,9',
            '10' => 'required|integer|gt:0|digits_between:1,9',
        ];
    }
    public function customValidationMessages()
    {
        return [
            '0.required' => 'Mã KH không được trống',
            '3.required' => 'Mã phụ tùng không được trống',
            '6.required' => 'Kho xuất không được trống',
            '7.required' => 'SL phụ tùng không được trống',
            '7.integer' => 'SL phải là kiểu số',
            '7.gt' => 'SL phải lớn hơn 0',
            '7.digits_between' => 'SL chi được từ 1-9999999',
            '8.required' => 'Giá niêm yết không được trống',
            '8.integer' => 'Giá phải là kiểu số',
            '8.gt' => 'Giá phải lớn hơn 0',
            '8.digits_between' => 'Giá chi được từ 1-999999999',
            '9.required' => 'Giá in hóa đơn được trống',
            '10.required' => 'Giá thực tế không được trống',
            '10.integer' => 'Giá thực tế phải là kiểu số',
            '10.gt' => 'Giá thực tế phải lớn hơn 0',
            '10.digits_between' => 'Giá thực tế chi được từ 1-999999999',
            '0.exists' => 'Mã KH không tồn tại',
        ];
    }
}
