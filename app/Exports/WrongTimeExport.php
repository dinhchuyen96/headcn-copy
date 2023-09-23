<?php

namespace App\Exports;

use App\Enum\EHmsReceivePlan;
use App\Models\HMSReceivePlan;
use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class WrongTimeExport implements FromCollection, WithHeadings, WithMapping,ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        return HMSReceivePlan::query()->where('eta', '>=', $get_first_day)->where('eta', '<=', $today)->where('arrival_date', '>', 'eta')->get();
    }

    public function headings(): array
    {
        return [
        'ID',
        'Số khung',
        'Số máy',
        'Model',
        'Màu xe',
        'Số lượng',
        'Số ngày chậm',
        'Trạng thái',
        'Nhà cung cấp',
        ];
    }

    public function map($listOrders): array
    {
        $remainDay =Carbon::parse($listOrders->eta)->diffInDays(Carbon::parse($listOrders->actual_arrival_date_time));
        $physicalStatus = ($listOrders->physical_status == EHmsReceivePlan::STATUS_BLANK ) ? 'Blank' : 'Receive Ok';
        $hms_id = "IM_00".$listOrders->id;
        return [
            $hms_id,
            $listOrders->chassic_no,
            $listOrders->engine_no,
            $listOrders->model_type,
            $listOrders->color,
            1,
            $remainDay."ngày",
            $physicalStatus,
            'HVN',
        ];
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
        }];

    }
}
