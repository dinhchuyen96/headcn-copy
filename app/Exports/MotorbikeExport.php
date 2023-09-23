<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Motorbike;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class MotorbikeExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Order::query()
            ->where('orders.category', '=', 2)
            ->where('orders.type', '!=', 3)
            ->leftjoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->leftjoin('ex_province', 'customers.city', '=', 'ex_province.province_code')
            ->leftjoin('ex_district', 'customers.district', '=', 'ex_district.district_code')
            ->leftjoin('ex_ward', 'customers.ward', '=', 'ex_ward.ward_code')
            ->leftjoin('order_details', 'orders.id', '=', 'order_details.order_id')
            // ->where('order_details.category','=',2)
            ->select(
                'customers.code as code',
                'customers.name as name',
                'customers.identity_code as cmt',
                'customers.phone as phone',
                'ex_province.name as city',
                'ex_district.name as district',
                'ex_ward.name as ward',
                'customers.address as address',
                'order_details.chassic_no as chassic_no',
                'order_details.engine_no as engine_no',
                'order_details.model_list as model_list',
                'order_details.model_code as model_code',
                'order_details.model_type as model_type',
                'orders.status as status',
                'orders.type as type',
                'orders.total_money as total_money',
                'orders.created_at as created_at'
            )
            ->orderBy('orders.id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Mã khách hàng',
            'Tên khánh hàng',
            'Tên đệm khách hàng',
            'Họ khách hàng',
            'CMTHC',
            'SĐT',
            'Tỉnh/Thành Phố',
            'Quận/Huyện',
            'Phường/Xã',
            'Địa chỉ',
            'Số khung',
            'Số máy',
            'Danh mục đời xe',
            'Tên đời xe',
            'Phân loại đời xe',
            'Số tiền thanh toán',
            'Trạng thái',
            'Phân loại mua hàng',
            'Ngày tạo'
        ];
    }

    public function map($listOrder): array
    {
        $fname = $lname = $middleName = "";
        $nameList = (string)$listOrder->name;
        if ($nameList != null) {
            $arrName = explode(" ", $nameList);
            $totalArrName = count($arrName);
            $fname = $arrName[$totalArrName - 1]; // ten
            if($totalArrName>1){
                $lname = $arrName[0];
                $middleName = trim(str_replace($fname, " ", str_replace($lname, "", $nameList)));
            }
        }
        $listOrder->fname = $fname;
        $listOrder->mid = $middleName;
        $listOrder->lname = $lname;
        // dd($listOrder);
        switch ($listOrder->status) {
            case '1':
                $listOrder->status = "đã thanh toán";
                break;
            case '2':
                $listOrder->status = "chưa thanh toán";
                break;
            case '3':
                $listOrder->status = "chờ xử lý";
                break;
            case '4':
                $listOrder->status = "đã hủy";
                break;
            case '5':
                $listOrder->status = "chờ xử lý hủy";
                break;
        }
        switch ($listOrder->type) {
            case '1':
                $listOrder->type = "bán buôn";
                break;
            case '2':
                $listOrder->type = "bán lẻ";
                break;
        }
        return [
            $listOrder->code,
            $listOrder->fname,
            $listOrder->mid,
            $listOrder->lname,
            $listOrder->cmt,
            $listOrder->phone,
            $listOrder->city,
            $listOrder->district,
            $listOrder->ward,
            $listOrder->address,
            $listOrder->chassic_no,
            $listOrder->engine_no,
            $listOrder->model_list,
            $listOrder->model_code,
            $listOrder->model_type,
            $listOrder->total_money,
            $listOrder->status,
            $listOrder->type,
            reformatDate($listOrder->created_at, 'd/m/Y')
        ];
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
        }];
    }
}
