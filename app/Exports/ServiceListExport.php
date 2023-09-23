<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Motorbike;
use App\Enum\EMotorbike;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ServiceListExport implements WithMultipleSheets, SkipsUnknownSheets
{
    protected $data;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function sheets(): array
    {
        return [
            new DataForListServiceExport($this->data['dataForListService'], $this->data['fromDate'], $this->data['toDate']),
            new DataForListServiceUserExport($this->data['dataForListServiceAndUser'], $this->data['fromDate'], $this->data['toDate']),
            new DataForListServiceUserDetailExport($this->data['dataForListServiceAndUserDetail'], $this->data['fromDate'], $this->data['toDate']),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
}
class DataForListServiceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithTitle, WithCustomStartCell
{
    protected $fromDate;
    protected $toDate;
    protected $data;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($data, $fromDate, $toDate)
    {
        $this->data = $data;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }
    public function collection()
    {
        return $this->data;
    }

    public function title(): string
    {
        return 'Báo cáo dịch vụ khác';
    }

    public function headings(): array
    {
        return [
            'Dịch vụ khác',
            'Doanh thu'
        ];
    }

    public function map($item): array
    {
        return [
            $item->title,
            empty($item->total) ? 0 : $item->total
        ];
    }
    public function startCell(): string
    {
        return 'A4';
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()
                    ->setMergeCells([
                        'A1:B1',
                        'A2:B2',
                    ]);
                $event->sheet->getDelegate()->setCellValue('A1', 'Ngày bắt đầu:');
                $event->sheet->getDelegate()->setCellValue('A2', 'Ngày kết thúc:');
                $event->sheet->getDelegate()->setCellValue('C1', empty($this->fromDate) ? '' : reFormatDate($this->fromDate));
                $event->sheet->getDelegate()->setCellValue('C2', empty($this->toDate) ? '' : reFormatDate($this->toDate));
            },
        ];
    }
}
class DataForListServiceUserExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithTitle, WithCustomStartCell
{
    protected $fromDate;
    protected $toDate;
    protected $data;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($data, $fromDate, $toDate)
    {
        $this->data = $data;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }
    public function collection()
    {
        return $this->data;
    }

    public function title(): string
    {
        return 'Báo cáo dịch vụ khác theo nhân viên';
    }

    public function headings(): array
    {
        return [
            'Dịch vụ khác',
            'Tên nhân viên',
            'Email',
            'Doanh thu'
        ];
    }

    public function map($item): array
    {
        return [
            $item->title,
            $item->name,
            $item->email,
            empty($item->total) ? 0 : $item->total
        ];
    }
    public function startCell(): string
    {
        return 'A4';
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()
                    ->setMergeCells([
                        'A1:B1',
                        'A2:B2',
                    ]);
                $event->sheet->getDelegate()->setCellValue('A1', 'Ngày bắt đầu:');
                $event->sheet->getDelegate()->setCellValue('A2', 'Ngày kết thúc:');
                $event->sheet->getDelegate()->setCellValue('C1', empty($this->fromDate) ? '' : reFormatDate($this->fromDate));
                $event->sheet->getDelegate()->setCellValue('C2', empty($this->toDate) ? '' : reFormatDate($this->toDate));
            },
        ];
    }
}
class DataForListServiceUserDetailExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithTitle, WithCustomStartCell
{
    protected $fromDate;
    protected $toDate;
    protected $data;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($data, $fromDate, $toDate)
    {
        $this->data = $data;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }
    public function collection()
    {
        return $this->data;
    }

    public function title(): string
    {
        return 'Báo cáo dịch vụ khác chi tiết';
    }

    public function headings(): array
    {
        return [
            'Dịch vụ khác',
            'Tên nhân viên',
            'Email',
            'Tiền công',
            'Khuyến mãi (%)'
        ];
    }

    public function map($item): array
    {
        return [
            $item->title,
            $item->name,
            $item->email,
            empty($item->price) ? 0 : $item->price,
            empty($item->promotion) ? 0 : $item->promotion
        ];
    }
    public function startCell(): string
    {
        return 'A4';
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()
                    ->setMergeCells([
                        'A1:B1',
                        'A2:B2',
                    ]);
                $event->sheet->getDelegate()->setCellValue('A1', 'Ngày bắt đầu:');
                $event->sheet->getDelegate()->setCellValue('A2', 'Ngày kết thúc:');
                $event->sheet->getDelegate()->setCellValue('C1', empty($this->fromDate) ? '' : reFormatDate($this->fromDate));
                $event->sheet->getDelegate()->setCellValue('C2', empty($this->toDate) ? '' : reFormatDate($this->toDate));
            },
        ];
    }
}
