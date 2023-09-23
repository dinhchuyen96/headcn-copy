<?php

namespace App\Exports;

use App\Enum\EHmsReceivePlan;
use App\Models\HMSPartNotAllowUrgent;
use App\Models\HMSReceivePlan;
use App\Models\HMSServiceResults;
use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class ApplyInsuranceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        return HMSServiceResults::query()
            ->where('sr_created_date_time', '>=', $get_first_day)
            ->where('sr_closed_date_time', '<=', $today)
            ->whereNotNull('sr_closed_date_time')
            ->where('reason_for_cancellation', '!=', '')
            ->where('sr_created_date_time', '>=', $get_first_day)
            ->where('sr_created_date_time', '<=', $today)
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Sr_number',
            'Ngày tạo',
            'Ngày đóng',
        ];
    }

    public function map($listOrders): array
    {
        $hms_id = "IM_00" . $listOrders->id;
        return [
            $hms_id,
            $listOrders->sr,
            $listOrders->sr_created_date_time,
            $listOrders->sr_closed_date_time,

        ];
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
        }];
    }
}
