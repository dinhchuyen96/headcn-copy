<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class ListContactCustomerExport implements FromCollection, WithHeadings,WithMapping, ShouldAutoSize, WithEvents
{

    protected $data;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tên khách hàng',
            'Số điện thoại',
            'Địa chỉ',
            'Doanh thu',
            'Ngày phát sinh',
            'Lần KTDK/Lí do',
            'Tình trạng liên hệ',
            'HT Liên hệ'
        ];
    }

    public function getPeriodItem($periodlevel)
    {
        $returval = '';
        if ($periodlevel==-1) {
            # code...
            $returval= 'Mua xe';
        }elseif ($periodlevel==0) {
            # code...
            $returval='Sửa chữa /Mua PT';
        }elseif ($periodlevel>0) {
            # code...
            $returval='Lần KTĐK '. $periodlevel;
        }else {
            # code...
            $returval='';
        }
        return $returval;
    }

    public function map($data): array
    {
        if(isset($data->contact_method_id) ){
            $contactstatus ='Đã LH' ;
        }else{
            $contactstatus ='' ;
        }
        if (isset($data->method_name)) {
            # code...
            $contactmethod = $data->method_name;
        } else{ $contactmethod = '';}

        return [
            $data->id,
            $data->name,
            $data->phone,
            $data->address,
            $data->total_revenue,
            $data->sell_date,
            $this->getPeriodItem($data->periodic_level),
            $contactstatus,
            $contactmethod
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
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'
            ];
            foreach ($arrayAlphabet as $alphabet) {
                $event->sheet->getColumnDimension($alphabet)->setAutoSize(true);
            }
            // title
            $cellRange = 'A1:I1';
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
