<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class ListIncomeExport implements FromCollection, WithHeadings,WithMapping, ShouldAutoSize, WithEvents
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
            'Khách hàng',
            'Nội dung',
            'Loại',
            'Mã TK Nộp',
            'Số TK Nộp',
            'Mã TK Nhận',
            'Số TK Nhận',
            'Số tiền'
        ];
    }

    public function getOrderReceiptType($receiptType)
    {
        $returval = '';
        switch ($receiptType) {
            case 1: // bán lẻ xe máy
                $returval= 'Bán lẻ xe máy';
                break;
            case 2: // bán buôn xe máy
                $returval='Bán buôn xe máy';
                break;
            case 3: //Bán lẻ phụ tùng
                $returval= 'Bán lẻ phụ tùng';
                break;
            case 4: //Bán buôn phụ tùng
                $returval= 'Bán buôn phụ tùng';
                break;
            case 5: // bảo dưỡng xe máy
                $returval= 'Dịch vụ KTĐK';
                break;
            case 6: //sửa chữa xe máy
                $returval= 'Dịch vụ sửa chữa';
                break;
            case 7: // Nợ tồn
                $returval= 'Nợ tồn';
                break;
            case 8: // Dịch vụ khác
                $returval= 'Dịch vụ khác';
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
        $type =$this->getOrderReceiptType($data->type);
        return [
            $data->id,
            reFormatDate($data->receipt_date, 'd/m/Y'),
            reFormatDate($data->created_at, 'd/m/Y'),
            $data->customer_name,
            $data->note,
            $type,
            $data->from_account_code,
            $data->from_account_number,
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
            $cellRange = 'A1:K1';
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
