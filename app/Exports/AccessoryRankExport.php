<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;

class AccessoryRankExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $Warehouses;
    protected $PositionInWarehouse;
    protected $AccessoriesCode;
    protected $key_name;
    protected $sortingName;

    //TUDN
    protected $accessories;
    protected $total;
    protected $accumulateper = 0;

    function __construct($accessories,$total)
    {
        $this->accessorires = $accessories;
        $this->total =  $total;
    }

    public function collection()
    {
        return $this->accessorires;
    }

    public function headings(): array
    {
        return [
            'Mã phụ tùng',
            'Tên phụ tùng',
            'Tổng bán 6T',
            'Trung bình',
            'Tỉ lệ %',
            'Lũy kế %',
            'Rank'
        ];
    }

    public function map($accessories): array
    {
        $percentage = 0;
        if($this->total> 0 ){
            $percentage = ($accessories->quantity / $this->total ) * 100 ;
        }
        $this-> accumulateper += $percentage ;
        $accumulateper =$this->accumulateper ;
        if ($accumulateper <= 85)
             $rank = 'A';
        elseif ($accumulateper <=90)
             $rank = 'B';
        elseif($accumulateper <=95)
             $rank = 'C';
        elseif($accumulateper < 100)
             $rank = 'D';
        else
             $rank = 'E';

        $code =isset($accessories->code) ? $accessories->code : '';
        $name =isset($accessories->name )  ? $accessories->name : '';
        $quantity =isset($accessories->quantity )  ? $accessories->quantity : 0;

        return [
            $code,
            $name,
            $quantity,
            $quantity / 6,
            $percentage . '%',
            $accumulateper . '%',
            $rank
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
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'
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
