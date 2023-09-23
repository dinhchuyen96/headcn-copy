<?php

namespace App\Exports;

use App\Enum\EHmsReceivePlan;
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

class WarrantyClaimExport implements FromCollection, WithHeadings, WithMapping,ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return HMSServiceResults::query()->whereRaw('DATEDIFF(sr_closed_date_time,sr_created_date_time) > 5')->get();
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
        $hms_id = "IM_00".$listOrders->id;
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
