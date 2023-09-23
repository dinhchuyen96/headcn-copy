<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Motorbike;
use App\Enum\EMotorbike;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportMotorbikesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithTitle
{
    protected $Warehouses;
    protected $Model;
    protected $Color;
    protected $ChassicNumber;
    protected $EngineNumber;
    protected $FromDate;
    protected $ToDate;
    protected $key_name;
    protected $sortingName;
    protected $totalMoney;
    public function title(): string
    {
        return 'Danh sách xe trong kho';
    }

    function __construct($Warehouses, $Model, $Color, $ChassicNumber, $EngineNumber, $FromDate, $ToDate, $key_name, $sortingName)
    {
        $this->Warehouses = trim($Warehouses);
        $this->Model = trim($Model);
        $this->Color = trim($Color);
        $this->ChassicNumber = trim($ChassicNumber);
        $this->EngineNumber = trim($EngineNumber);
        $this->FromDate = trim($FromDate);
        $this->ToDate = trim($ToDate);
        $this->key_name = trim($key_name);
        $this->sortingName = trim($sortingName);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $fromDate = $this->FromDate;
        $toDate = $this->ToDate;

        $query = Motorbike::where('is_out', EMotorbike::NOT_OUT)->select(
            'motorbikes.*',
        )->with(['tranferWarehouse', 'warehouse'])
            ->whereHas('warehouse');

        $query->where(function () use ($query, $fromDate, $toDate) {
            if (!empty($this->Warehouses)) {
                $query->where('motorbikes.warehouse_id', '=', $this->Warehouses);
            }
            if (!empty($this->Model)) {
                $query->where('motorbikes.model_code', 'like', '%' . $this->Model . '%');
            }
            if (!empty($this->Color)) {
                $query->where('motorbikes.color', 'like', '%' . $this->Color . '%');
            }
            if (!empty($this->ChassicNumber)) {
                $query->where('motorbikes.chassic_no', 'like', '%' . $this->ChassicNumber . '%');
            }
            if (!empty($this->EngineNumber)) {
                $query->where('motorbikes.engine_no', 'like', '%' . $this->EngineNumber . '%');
            }
            if (!empty($fromDate) && empty($toDate)) {
                $query->where('motorbikes.buy_date', '>=', $fromDate);
            }
            if (empty($fromDate) && !empty($toDate)) {
                $query->where('motorbikes.buy_date', '<=', $toDate);
            }
            if (!empty($fromDate) && !empty($toDate)) {
                $query->where('motorbikes.buy_date', '>=', $fromDate);
                $query->where('motorbikes.buy_date', '<=', $toDate);
            }
        });

        if ($this->key_name) {
            $query->orderBy($this->key_name, $this->sortingName);
        }

        $data = $query->get();

        return $data;
    }

    public function headings(): array
    {
        return [
            'Kho',
            'Số khung',
            'Số máy',
            'Model',
            'Màu xe',
            'Tồn đầu',
            'Nhập mua',
            'Nhập chuyển kho',
            'Xuất bán',
            'Xuất chuyển kho',
            'Tồn'
        ];
    }

    public function map($data): array
    {
        return [
            $data->warehouse->name,
            $data->chassic_no,
            $data->engine_no,
            $data->model_code,
            $data->color,
            $data->quantity != 0 ? $data->quantity : '0',
            $data->quantity != 0 ? $data->quantity : '0',
            (!empty($data->tranferWarehouse) && !empty($data->tranferWarehouse->from_warehouse_id)) ? '1' : '0',
            ($data->sell_date != 0 ? '1' : '0'),
            (!empty($data->tranferWarehouse) && !empty($data->tranferWarehouse->to_warehouse_id)) ? '1' : '0',
            ($data->sell_date != 0 ? '0' : '1')
        ];
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $default_font_style = [
                'font' => [
                    'name' => 'Times New Roman', 'size' => 12, 'color' => ['argb' => '#FFFFFF'],
                    'background' => [
                        'color' => '#5B9BD5'
                    ]
                ]
            ];

            $active_sheet = $event->sheet->getDelegate();
            $active_sheet->getParent()->getDefaultStyle()->applyFromArray($default_font_style);
            $arrayAlphabet = [
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'
            ];
            foreach ($arrayAlphabet as $alphabet) {
                $event->sheet->getColumnDimension($alphabet)->setAutoSize(true);
            }

            // title
            $cellRange = 'A1:G1';
            $active_sheet->getStyle($cellRange)->applyFromArray($default_font_style);
            $active_sheet->getStyle($cellRange)->getFont()
                ->getColor()->setRGB('000000');
            $active_sheet->getStyle($cellRange)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('00d6d6c2');
            $active_sheet->getStyle($cellRange)->getAlignment()->applyFromArray(
                array('horizontal' => 'center', 'vertical' => 'center')
            );
            $active_sheet->getStyle($cellRange)->getFont()->setBold(true);
        },];
    }
}
