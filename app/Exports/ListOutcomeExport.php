<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class ListOutcomeExport implements FromCollection, WithHeadings,WithMapping, ShouldAutoSize, WithEvents
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
            'Ngày hạch toán',
            'Ngày tạo',
            'Nội dung',
            'Loại',
            'Hạng mục',
            'Mã TK Nhận',
            'Số TK Nhận',
            'Mã TK Chi',
            'Số TK Chi',
            'Số tiền'
        ];
    }

    public function getPaidType($paidType)
    {
        $returval = '';
        switch ($paidType) {
            case 8: // Dịch vụ khác
                $returval= 'Nhập phụ tùng';
                break;
            case 9: // Dịch vụ khác
                $returval= 'Nhập xe';
                break;
            case 10: // Dịch vụ khác
                $returval= 'Chi nội bộ';
                break;
            case 11: // Dịch vụ khác
                $returval= 'Chi phí khác';
                break;
            case 100: //'Nộp tiền ngân hàng'
                $returval= 'Nộp tiền ngân hàng';
                break;
            case 101: //'Rút tiền về quỹ'
                $returval= 'Rút tiền về quỹ';
                break;
            case 102: //'Rút tiền về quỹ'
                $returval= 'Chuyển tiền nội bộ';
                break;
            default:
                # code...
                $returval= '';
                break;
        }
        return $returval;
    }


    public function map($data): array
    {
        $type =$this->getPaidType($data->type);
        return [
            $data->id,
            reFormatDate($data->payment_date, 'd/m/Y'),
            reFormatDate($data->created_at, 'd/m/Y'),
            $data->note,
            $type,
            $data->title,
            $data->to_account_code,
            $data->to_account_number,
            $data->account_code,
            $data->account_number,
            $data->money
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
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I','J','K'
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
