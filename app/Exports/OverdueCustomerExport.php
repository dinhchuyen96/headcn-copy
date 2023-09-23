<?php

namespace App\Exports;

use App\Enum\EHmsReceivePlan;
use App\Models\HMSReceivePlan;
use App\Models\Mtoc;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class OverdueCustomerExport implements FromCollection, WithHeadings, WithMapping,ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        $day =  strtotime('-7 day', strtotime($today));
        $diffDate  = date('Y-m-d', $day);
        return Order::leftJoin('receipts', 'receipts.customer_id', '=', 'orders.customer_id')
        ->with('customer')
        ->whereDate('orders.created_at', '<=', $today)
        ->whereDate('receipt_date', '<=', $today)
        ->whereDate('receipt_date', '>', $diffDate)
        ->select(DB::raw('count(orders.customer_id) as countCustomer'), 'orders.customer_id', DB::raw('SUM(total_money) - SUM(money) AS remainAmount'))
        ->groupBy('orders.customer_id')->get();
    }

    public function headings(): array
    {
        return [
        'ID',
        'Mã khách hàng',
        'Tên khách hàng',
        'Tên đệm khách hàng',
        'Họ khách hàng',
        'Số điện thoại',
        'Tổng dư nợ',
        ];
    }

    public function map($listOrders): array
    {
        $cusId ='IM_00'.$listOrders->customer->id ??'';
        $fname = $lname = $middleName = "";
        $nameList = (string)$listOrders->customer->name;
        if ($nameList != null) {
            $arrName = explode(" ", $nameList);
            $totalArrName = count($arrName);
            $fname = $arrName[$totalArrName - 1]; // ten
            if($totalArrName>1){
                $lname = $arrName[0];
                $middleName = trim(str_replace($fname, " ", str_replace($lname, "", $nameList)));
            }
        }
        $listOrders->customer->fname = $fname;
        $listOrders->customer->mid = $middleName;
        $listOrders->customer->lname = $lname;
        return [
            $cusId,
            $listOrders->customer->code ?? '',
            $listOrders->customer->fname ?? '',
            $listOrders->customer->mid ?? '',
            $listOrders->customer->lname ?? '',
            $listOrders->customer->phone ?? '',
            $listOrders->remainAmount ?? ''
        ];
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
        }];

    }
}
