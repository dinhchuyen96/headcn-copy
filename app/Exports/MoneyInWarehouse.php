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
use App\Models\Warehouse;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;

class MoneyInWarehouse implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithTitle
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
    protected $nameWarehouse;
    public function title(): string
    {
        return 'Tổng giá trị xe trong kho';
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

        $query = Warehouse::leftJoin('motorbikes', function ($query) use ($toDate, $fromDate) {
            $query->on('motorbikes.warehouse_id', '=', 'warehouse.id')
                ->whereNull('motorbikes.sell_date')
                ->where('motorbikes.is_out', EMotorbike::NOT_OUT);
            if (!empty($wareHouse)) {
                $query->where('motorbikes.warehouse_id', '=', $wareHouse);
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
        })->groupBy('warehouse.id', 'warehouse.name')->select(DB::raw('sum(motorbikes.price) as totalMoney'), 'warehouse.name', DB::raw('count(motorbikes.id) as totalItem'));
        $query->where(function () use ($query, $fromDate, $toDate) {
            if (!empty($this->Warehouses)) {
                $query->where('motorbikes.warehouse_id', '=', $this->Warehouses);
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
            'Tổng tiền',
            'Số lượng',
            'Giá trị trung bình'
        ];
    }

    public function map($data): array
    {
        return [
            $data->name,
            numberFormat($data->totalMoney),
            numberFormat($data->totalItem),
            $data->totalItem == 0 ? '0' : numberFormat(round($data->totalMoney / $data->totalItem))
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
                'A', 'B', 'C', 'D'
            ];
            foreach ($arrayAlphabet as $alphabet) {
                $event->sheet->getColumnDimension($alphabet)->setAutoSize(true);
            }

            // title
            $cellRange = 'A1:D1';
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
