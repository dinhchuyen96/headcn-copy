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

class WarningUrgentExport implements FromCollection, WithHeadings, WithMapping,ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        return HMSPartNotAllowUrgent::query()
        ->with('orderPlanDetails.orderPlan')
        ->whereHas('orderPlanDetails', function ($query2) use ($get_first_day, $today) {
            return $query2->whereHas('orderPlan', function ($query3) use ($get_first_day, $today) {
                return $query3->whereDate('po_date', '>=', $get_first_day)
                    ->whereDate('po_date', '<=', $today)
                    ->where('part_order_type', 'LIKE', '%Urgent Order%');
            });
        })->get();
    }

    public function headings(): array
    {
        return [
        'ID',
        'Po_number',
        'Po_date',
        'Po_type',
        'Part_no',
        'Qty'
        ];
    }

    public function map($listOrders): array
    {
        $poNumber = $listOrders->orderPlanDetails->orderPlan->order_number;
        $poDate = $listOrders->orderPlanDetails->orderPlan->po_date;
        $poType = $listOrders->orderPlanDetails->orderPlan->part_order_type;
        $hms_id = "IM_00".$listOrders->id;
        $quantity = $listOrders->orderPlanDetails->quantity_requested;
        return [
            $hms_id,
            $poNumber,
            $poDate,
            $poType,
            $listOrders->part_no,
            $quantity
        ];
    }
    
    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
        }];
  
    }
}
