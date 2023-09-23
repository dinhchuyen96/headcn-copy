<?php

namespace App\Exports;

use App\Models\OrderDetail;
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Models\Motorbike;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class InstallmentExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
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
            'STT',
            'Số hợp đồng',
            'Họ tên KH',
            'Ngày mua',
            'Số tiền',
            'Tên công ty tài chính',
            'Trạng thái'
        ];
    }

    public function map($item): array
    {
        $index = $this->data->search(function ($itemData) use ($item) {
            return $itemData->installment_id === $item->installment_id;
        });
        $index = $index + 1;
        $status = '';
        if ($item->orders_status == 1)
            $status = 'Đã thanh toán';
        if ($item->orders_status == 2)
            $status = 'Chưa thanh toán';
        return [
            $index,
            $item->contract_number,
            $item->customer_name,
            $item->created_at,
            empty($item->money) ? 0 : numberFormat($item->money),
            $item->company_name,
            $status
        ];
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $event->sheet->getDelegate()
                ->setMergeCells([
                    'A1:D1',
                ]);
            $event->sheet->getDelegate()->setCellValue('A1', 'BÁO CÁO HỢP ĐỒNG TRẢ GÓP QUA NGÂN HÀNG');
            $event->sheet->getDelegate()->setCellValue('A' . (4 + $this->data->count()), 'Tổng số');
            $event->sheet->getDelegate()->setCellValue('B' . (4 + $this->data->count()), $this->data->count());
            $event->sheet->getDelegate()->setCellValue('E' . (4 + $this->data->count()), numberFormat($this->data->sum('money')));
            $active_sheet = $event->sheet->getDelegate();
            $active_sheet->getStyle('A1:D1')->getAlignment()->applyFromArray(
                array('horizontal' => 'center', 'vertical' => 'center')
            );
            $default_font_style1 = [
                'font' => ['name' => 'Times New Roman', 'size' => 12, 'bold' => false],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ];
            $active_sheet->getStyle('A3:G3')->applyFromArray($default_font_style1);
            foreach ($this->data as $key => $value) {
                $active_sheet->getStyle('A' . ($key + 4) . ':G' . ($key + 4))->applyFromArray($default_font_style1);
            }
            $active_sheet->getStyle('A' . ($this->data->count() + 4) . ':G' . ($this->data->count() + 4))->applyFromArray($default_font_style1);
        }];
    }
    public function startCell(): string
    {
        return 'A3';
    }
}
