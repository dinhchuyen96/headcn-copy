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

class AtrophyExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
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
            'Mã phụ tùng',
            'Tên phụ tùng',
            'Số lượng',
            'Tên khách hàng',
            'Biến số xe',
            'Ngày làm dịch vụ'
        ];
    }

    public function map($item): array
    {
        $index = $this->data->search(function ($itemData) use ($item) {
            return $itemData->order_details_id === $item->order_details_id;
        });
        $index = $index + 1;
        return [
            $index,
            $item->accessories_code,
            $item->accessories_name,
            empty($item->order_details_quantity) ? 0 : numberFormat($item->order_details_quantity),
            $item->customer_name.' - '.$item->customer_phone,
            $item->mortorbike_number,
            $item->repair_date
        ];
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
            $event->sheet->getDelegate()
                ->setMergeCells([
                    'A1:D1',
                ]);
            $event->sheet->getDelegate()->setCellValue('A1', 'THỐNG KÊ SỐ LƯỢNG PHỤ TÙNG CHỜ THAY THẾ');
            $event->sheet->getDelegate()->setCellValue('A' . (4 + $this->data->count()), 'Tổng số lượng phụ tùng');
            $event->sheet->getDelegate()->setCellValue('D' . (4 + $this->data->count()), numberFormat($this->data->sum('order_details_quantity')));
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
