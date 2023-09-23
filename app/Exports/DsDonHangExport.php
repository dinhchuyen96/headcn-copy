<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class DsDonHangExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
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
            'Mã đơn hàng',
            'Tên khách hàng',
            'Tên đệm hàng',
            'Họ khách hàng',
            'Địa Chỉ',
            'Mã phụ tùng',
            'Tên phụ tùng',
            'Số lượng',
            'Đơn giá',
            'Thành tiền',
            'Trạng thái',
            'Phân loại mua hàng',
            'Ngày tạo'
        ];
    }

    public function map($data): array
    {
        $fname = $lname = $middleName = "";
        $nameList = (string)$data->customer->name;
        if ($nameList != null) {
            $arrName = explode(" ", $nameList);
            $totalArrName = count($arrName);
            $fname = $arrName[$totalArrName - 1]; // ten
            if ($totalArrName > 1) {
                $lname = $arrName[0];
                $middleName = trim(str_replace($fname, " ", str_replace($lname, "", $nameList)));
            }
        }
        $data->customer->fname = $fname;
        $data->customer->mid = $middleName;
        $data->customer->lname = $lname;
        $status = '';
        if ($data->status == 1) $status = 'Đã thanh toán';
        else if ($data->status == 2) $status = 'Chưa thanh toán';
        else if ($data->status == 3) $status = 'Chờ xử lý';
        else if ($data->status == 4) $status = 'Đã hủy';
        else if ($data->status == 5) $status = 'Chờ xử lý hủy';
        $type = '';
        if ($data->type == 1) $type = 'Bán buôn';
        else if ($data->type == 2) $type = 'Bán lẻ';
        return [
            $data->id,
            $data->customer->fname,
            $data->customer->mid,
            $data->customer->lname,
            $data->customer->address
                . (isset($data->customer->wardCustomer) ? ', ' . $data->customer->wardCustomer->name : '')
                . (isset($data->customer->districtCustomer) ? ', ' . $data->customer->districtCustomer->name : '')
                . (isset($data->customer->provinceCustomer) ? ', ' . $data->customer->provinceCustomer->name : ''),
                $data->part_no,
                $data->part_name,
                $data->qty,
                $data->actual_price,
                numberFormat($data->amount),
            //numberFormat($data->total_money),
            $status,
            $type,
            reFormatDate($data->created_at, 'd/m/Y'),
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
