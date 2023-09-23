<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class ListCustomerExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $data;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($data)
    {
        $this->data = $data;
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
            'ID',
            'Họ Tên',
            'Địa Chỉ',
            'SĐT',
            'Ngày Sinh',
            'Giới Tính',
            'Nghề Nghiệp',
            'Tích Điểm',
            'Ngày Tạo'
        ];
    }



    public function map($CustomerData): array
    {
       $address= $CustomerData->address . (isset($CustomerData->wardCustomer) ? ', ' . $CustomerData->wardCustomer->name : '') . (isset($CustomerData->districtCustomer) ? ', ' . $CustomerData->districtCustomer->name : '') . (isset($CustomerData->provinceCustomer) ? ', ' . $CustomerData->provinceCustomer->name : '');
        return [
            $CustomerData->code ,
            $CustomerData->name ,
            $address
           ,
            $CustomerData->phone ,
            formatBirthday($CustomerData->birthday) ,
            getSexName($CustomerData->sex) ,
            $CustomerData->job ,
            numberFormat($CustomerData->point) ,
            $CustomerData->created_at ,
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
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H','I'
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
