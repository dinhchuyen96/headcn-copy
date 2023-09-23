<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Motorbike;
use App\Enum\EMotorbike;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class MotorbikeListExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $dataExport;
    function __construct($dataExport)
    {
        $this->dataExport = $dataExport;
    }
    public function collection()
    {
        // return Motorbike::query()->where('is_out', EMotorbike::NOT_OUT)
        //     ->leftjoin('suppliers', 'motorbikes.supplier_id', '=', 'suppliers.id')
        //     ->select('chassic_no', 'engine_no', 'model_code', 'color', 'price', 'model_list', 'model_type', 'suppliers.name as spname')
        //     ->orderBy('motorbikes.id', 'asc')->get();
        return $this->dataExport;
    }


    public function headings(): array
    {
        return [
            'Số khung',
            'Số máy',
            'Đời xe',
            'Màu xe',
            'Đơn giá',
            'Danh mục đời xe',
            'Phân loại đời xe',
            'Nhà cung cấp',
        ];
    }

    public function map($listMotor): array
    {

        // switch ($listOrder->status) {
        //     case '1':
        //         $listOrder->status="đã thanh toán";
        //         break;
        //     case '2':
        //         $listOrder->status="chưa thanh toán";
        //         break;
        //     case '3':
        //         $listOrder->status="chờ xử lý";
        //         break;
        //     case '4':
        //         $listOrder->status="đã hủy";
        //         break;
        //     case '5':
        //         $listOrder->status="chờ xử lý hủy";
        //         break;
        // }
        // switch ($listOrder->type) {
        //     case '1':
        //         $listOrder->type="bán buôn";
        //         break;
        //     case '2':
        //         $listOrder->type="bán lẻ";
        //         break;
        // }
        return [
            $listMotor->chassic_no,
            $listMotor->engine_no,
            $listMotor->model_code,
            $listMotor->color,
            $listMotor->price,
            $listMotor->model_list,
            $listMotor->model_type,
            !empty($listMotor->Supplier) ? $listMotor->Supplier->name : '',
        ];
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
        }];
    }
}
