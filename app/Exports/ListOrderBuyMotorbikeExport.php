<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Motorbike;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class ListOrderBuyMotorbikeExport implements WithColumnFormatting,  FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $dataExport;
    function __construct($dataExport)
    {
        $this->dataExport = $dataExport;
    }
    public function collection()
    {
        return $this->dataExport;
    }

    public function headings(): array
    {
        return [
            'Mã khách hàng',
            'Họ khách hàng',
            'Tên đệm khách hàng',
            'Tên khách hàng',
            'Ngày sinh',
            'Giới tính',
            'CMTHC',
            'Số điện thoại',
            'Tỉnh/TP',
            'Quận/Huyện',
            'Phường/Xã',
            'Địa chỉ',
            'Số khung',
            'Số máy',
            'Danh mục đời xe',
            'Tên đời xe',
            'Phân loại đời xe',
            'MTOC',
            'Số tiền thanh toán',
            'Mã nhân viên phụ trách',
            'NV bán hàng',
            'NV kĩ thuật',
        ];
    }

    public function map($item): array
    {

        $fname = $lname = $middleName = "";
        $nameList = (string)$item['name'];
        if ($nameList != null) {
            $arrName = explode(" ", $nameList);
            $totalArrName = count($arrName);
            $fname = $arrName[$totalArrName - 1]; // ten
            if ($totalArrName > 1) {
                $lname = $arrName[0];
                $middleName = trim(str_replace($fname, " ", str_replace($lname, "", $nameList)));
            }
        }
        switch ($item['status']) {
            case '1':
                $item['status'] = "đã thanh toán";
                break;
            case '2':
                $item['status'] = "chưa thanh toán";
                break;
            case '3':
                $item['status'] = "chờ xử lý";
                break;
            case '4':
                $item['status'] = "đã hủy";
                break;
            case '5':
                $item['status'] = "chờ xử lý hủy";
                break;
        }
        switch ($item['type']) {
            case '1':
                $item['type'] = "bán buôn";
                break;
            case '2':
                $item['type'] = "bán lẻ";
                break;
        }
        switch ($item['sex']) {
            case '1':
                $item['sex'] = "Nam";
                break;
            case '2':
                $item['type'] = "Nữ";
                break;
        }

        return [
            $item['code'],
            $lname,
            $middleName,
            $fname,
            reformatDate($item['birthday'], 'm/d/Y'),
            $item['sex'],
            (string)($item['cmt']),
            $item['phone'],
            $item['city'],
            $item['district'],
            $item['ward'],
            $item['address'],
            $item['chassic_no'],
            $item['engine_no'],
            $item['model_list'],
            $item['model_code'],
            $item['model_type'],
            $item['mtoc_code'],
            $item['total_money'],
            $item['exporter'],
            $item['seller_name'],
            $item['assembler_name'],
        ];
    }
    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_TEXT,
            'Q' => NumberFormat::FORMAT_NUMBER
        ];
    }

    public function registerEvents(): array
    {
        return [AfterSheet::class => function (AfterSheet $event) {
        }];
    }
}
