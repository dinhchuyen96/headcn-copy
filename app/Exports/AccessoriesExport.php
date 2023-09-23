<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;

class AccessoriesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $Warehouses;
    protected $PositionInWarehouse;
    protected $AccessoriesCode;
    protected $FromDate;
    protected $ToDate;
    protected $key_name;
    protected $sortingName;



    protected $data, $rests;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($data, $rests)
    {
        $this->data = $data;
        $this->rests = $rests;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Vị trí kho',
            'Mã phụ tùng',
            'Tồn đầu',
            'Nhập mua',
            'Giá nhập',
            'Nhập chuyển kho',
            'Xuất bán',
            'Giá bán',
            'Xuất chuyển kho',
            'Tồn cuối'
        ];
    }

    public function map($accessories): array
    {
        $beginQty  = @$this->rests[$accessories->id] ?: '0';
        $buyQty =$accessories->quantity_buy_input ?: '0';
        $transInQty = $accessories->quantity_input_trans ?: '0';
        $sellQty = $accessories->quantity_sell_output ?: '0';
        $transOutQty = $accessories->quantity_output_trans ?: '0';

        $stockQty =  $beginQty+$buyQty +$transInQty- $sellQty - $transOutQty;
        return [
            @$accessories->warehouse->name . ' - ' . @$accessories->positionInWarehouse->name,
            $accessories->code,
            $beginQty,
            $buyQty,
            $accessories->price_in ?: '0',
            $transInQty,
            $sellQty,
            $accessories->price_out ?: '0',
            $transOutQty,
            $stockQty,
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
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H','I','J'
            ];
            foreach ($arrayAlphabet as $alphabet) {
                $event->sheet->getColumnDimension($alphabet)->setAutoSize(true);
            }

            // title
            $cellRange = 'A1:J1';
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
