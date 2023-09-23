<?php

namespace App\Exports;

use App\Enum\EHmsReceivePlan;
use App\Models\HMSReceivePlan;
use App\Models\Mtoc;
use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class MtocExport implements FromCollection, WithHeadings, WithMapping,ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Mtoc::query()->get();
    }

    public function headings(): array
    {
        return [
        'Mã MTOC',
        'Danh mục đời xe',
        'Tên đời xe',
        'Phân loại đời xe',
        'Giá đề suât',
        'Mã màu xe',
        'Tên màu xe',
        ];
    }

    public function map($listOrders): array
    {
        $carModeList = $listOrders->option_code ?? '';
        $carModeName = $listOrders->model_code ?? '';
        $carModeType = $listOrders->type_code ??'';
        $carColorCode= $listOrders->color_code??'';
        return [
            $listOrders->getMTOC(),
            $carModeList,
            $carModeName,
            $carModeType,
            $listOrders->suggest_price,
            $carColorCode,
            $listOrders->color_name,
        ];
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
        }];

    }
}
