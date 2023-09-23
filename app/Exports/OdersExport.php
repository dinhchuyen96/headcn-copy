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
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class OdersExport implements WithMultipleSheets, SkipsUnknownSheets
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
            new OdersExportService($this->data['odersExportService'], $this->data['fromDate'], $this->data['toDate']),
            new WorkContentExport($this->data['dataForWorkContent'], $this->data['fromDate'], $this->data['toDate']),
            new UserExport($this->data['dataForFixer'], $this->data['fromDate'], $this->data['toDate']),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
}

class OdersExportService implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithTitle, WithCustomStartCell
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
    /**
     * @return \Illuminate\Support\Collection
     */
    public function title(): string
    {
        return 'Báo cáo dịch vụ';
    }
    public function collection()
    {
        $repairList = $this->data;
        $result = collect([]);
        foreach ($repairList as $key => $itemRepair) {
            if ($itemRepair->category == EOrderDetail::CATE_MAINTAIN) {
                if ($itemRepair->details->isNotEmpty()) {
                    foreach ($itemRepair->details as $key => $detail) {
                        if ($key == 0) {
                            $item = (object)[
                                'id' => "DICHVU_" . $itemRepair->id,
                                'customer_name' => $itemRepair->customer->name ?? '',
                                'customer_address' => $itemRepair->customer->address,
                                'customer_ward' => (isset($itemRepair->customer->wardCustomer) ?  $itemRepair->customer->wardCustomer->name : ''),
                                'customer_district' => (isset($itemRepair->customer->districtCustomer) ?  $itemRepair->customer->districtCustomer->name : ''),
                                'customer_province' => (isset($itemRepair->customer->provinceCustomer) ?  $itemRepair->customer->provinceCustomer->name : ''),
                                'chassis_no' => $itemRepair->periodic->motorbike->chassic_no,
                                'engine_no' => $itemRepair->periodic->motorbike->engine_no,
                                'total_price' => (string)$itemRepair->total_money,
                                'status' => $itemRepair->status == EOrder::STATUS_PAID ? 'Đã thanh toán' : 'Chưa thanh toán',
                                'category' => 'Bảo dưỡng định kì',
                                'created_at' => $itemRepair->created_at,
                                'accesory_code' => '',
                                'possition_in_warehouse' =>  '',
                                'accesory_name' =>  '',
                                'supplier_name' =>   '',
                                'quatity' => '',
                                'price' => '',
                                'total_price_item' =>  '',
                                'vat_price' =>  '',
                                'actual_price' => '',
                            ];
                            $result->push($item);
                        } else {
                            $item = (object)[
                                'id' => '',
                                'customer_name' => '',
                                'customer_address' => '',
                                'customer_ward' => '',
                                'customer_district' => '',
                                'customer_province' => '',
                                'chassis_no' => '',
                                'engine_no' => '',
                                'total_price' => '',
                                'status' => '',
                                'category' => '',
                                'created_at' => '',
                                'accesory_code' => !empty($detail->accessorie) ? $detail->accessorie->code : '',
                                'possition_in_warehouse' => !empty($detail->accessorie) ? $detail->accessorie->positionInWarehouse->warehouse->name . '-' . $detail->accessorie->positionInWarehouse->name : '',
                                'accesory_name' => !empty($detail->accessorie) ? $detail->accessorie->name : '',
                                'supplier_name' =>  isset($detail->accessorie->supplier) ? $detail->accessorie->supplier->name : '',
                                'quatity' => $detail->quantity ?? '0',
                                'price' => $detail->price ?? '0',
                                'total_price_item' => isset($detail->quantity) && isset($detail->price) ? $detail->quantity * $detail->price : '0',
                                'vat_price' => $detail->vat_price ?? '0',
                                'actual_price' => $detail->actual_price ?? '0',
                            ];
                            $result->push($item);
                        }
                    }
                }
            }

            if ($itemRepair->category == EOrderDetail::CATE_REPAIR) {
                if ($itemRepair->details->isNotEmpty()) {
                    foreach ($itemRepair->details as $k => $detail) {
                        if ($k == 0) {
                            $item = (object)[
                                'id' => "DICHVU_" . $itemRepair->id,
                                'customer_name' => $itemRepair->customer->name ?? '',
                                'customer_address' => $itemRepair->customer->address,
                                'customer_ward' => (isset($itemRepair->customer->wardCustomer) ?  $itemRepair->customer->wardCustomer->name : ''),
                                'customer_district' => (isset($itemRepair->customer->districtCustomer) ?  $itemRepair->customer->districtCustomer->name : ''),
                                'customer_province' => (isset($itemRepair->customer->provinceCustomer) ?  $itemRepair->customer->provinceCustomer->name : ''),
                                'chassis_no' => $itemRepair->repairBill->motorbike->chassic_no,
                                'engine_no' => $itemRepair->repairBill->motorbike->engine_no,
                                'total_price' => (string)$itemRepair->total_money,
                                'status' => $itemRepair->status == EOrder::STATUS_PAID ? 'Đã thanh toán' : 'Chưa thanh toán',
                                'category' => 'Sửa chữa thông thường',
                                'created_at' => $itemRepair->created_at,
                                'accesory_code' => '',
                                'possition_in_warehouse' =>  '',
                                'accesory_name' =>  '',
                                'supplier_name' =>   '',
                                'quatity' =>  '',
                                'price' =>  '',
                                'total_price_item' =>  '',
                                'vat_price' =>  '',
                                'actual_price' =>  '',
                            ];
                            $result->push($item);
                        } else {
                            $item = (object)[
                                'id' => '',
                                'customer_name' => '',
                                'customer_address' => '',
                                'customer_ward' => '',
                                'customer_district' => '',
                                'customer_province' => '',
                                'chassis_no' => '',
                                'engine_no' => '',
                                'total_price' => '',
                                'status' => '',
                                'category' => '',
                                'created_at' => '',
                                'accesory_code' => $detail->accessorie->code,
                                'possition_in_warehouse' => $detail->accessorie->positionInWarehouse->warehouse->name . '-' . $detail->accessorie->positionInWarehouse->name,
                                'accesory_name' => $detail->accessorie->name ?? '',
                                'supplier_name' =>  isset($detail->accessorie->supplier) ? $detail->accessorie->supplier->name : '',
                                'quatity' => $detail->quantity ?? '0',
                                'price' => $detail->price ?? '0',
                                'total_price_item' => isset($detail->quantity) && isset($detail->price) ? $detail->quantity * $detail->price : '0',
                                'vat_price' => $detail->vat_price ?? '0',
                                'actual_price' => $detail->actual_price ?? '0',
                            ];
                            $result->push($item);
                        }
                    }
                } else {
                    $item = (object)[
                        'id' => "DICHVU_" . $itemRepair->id,
                        'customer_name' => $itemRepair->customer->name ?? '',
                        'customer_address' => $itemRepair->customer->address,
                        'customer_ward' => (isset($itemRepair->customer->wardCustomer) ?  $itemRepair->customer->wardCustomer->name : ''),
                        'customer_district' => (isset($itemRepair->customer->districtCustomer) ?  $itemRepair->customer->districtCustomer->name : ''),
                        'customer_province' => (isset($itemRepair->customer->provinceCustomer) ?  $itemRepair->customer->provinceCustomer->name : ''),
                        'chassis_no' => $itemRepair->repairBill->motorbike->chassic_no,
                        'engine_no' => $itemRepair->repairBill->motorbike->engine_no,
                        'total_price' => (string)$itemRepair->total_money,
                        'status' => $itemRepair->status == EOrder::STATUS_PAID ? 'Đã thanh toán' : 'Chưa thanh toán',
                        'category' => 'Sửa chữa thông thường',
                        'created_at' => $itemRepair->created_at,
                        'accesory_code' => '',
                        'possition_in_warehouse' =>  '',
                        'accesory_name' =>  '',
                        'supplier_name' =>   '',
                        'quatity' =>  '',
                        'price' =>  '',
                        'total_price_item' =>  '',
                        'vat_price' =>  '',
                        'actual_price' =>  '',
                    ];
                    $result->push($item);
                }
            }
        }
        return $result;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tên khách hàng',
            'Tên đệm khách hàng',
            'Họ khách hàng',
            'Địa chỉ',
            'Xã',
            'Quận/Huyện',
            'Tỉnh/Thành Phố',
            'Số khung',
            'Số máy',
            'Tổng tiền',
            'Trạng thái',
            'Phân loại',
            'Ngày tạo',
            'Mã phụ tùng',
            'Vị trí kho',
            'Tên phụ tùng',
            'Mã nhà cung cấp',
            'Số lượng',
            'Đơn giá',
            'Thành tiền',
            'Giá in hóa đơn',
            'Giá thực tế'
        ];
    }

    public function map($item): array
    {
        $fname = $lname = $middleName = "";
        $nameList = (string)$item->customer_name;
        if ($nameList != null) {
            $arrName = explode(" ", $nameList);
            $totalArrName = count($arrName);
            $fname = $arrName[$totalArrName - 1];
            $lname = $arrName[0];
            $middleName = trim(str_replace($fname, " ", str_replace($lname, "", $nameList)));
        }
        $item->fname = $fname;
        $item->mid = $middleName;
        $item->lname = $lname;

        return [
            $item->id,
            $item->fname,
            $item->mid,
            $item->lname,
            $item->customer_address,
            $item->customer_ward,
            $item->customer_district,
            $item->customer_province,
            $item->chassis_no,
            $item->engine_no,
            $item->total_price,
            $item->status,
            $item->category,
            $item->created_at,
            $item->accesory_code,
            $item->possition_in_warehouse,
            $item->accesory_name,
            $item->supplier_name,
            $item->quatity,
            $item->price,
            $item->total_price_item,
            $item->vat_price,
            $item->actual_price,
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
class WorkContentExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithTitle, WithCustomStartCell
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
    /**
     * @return \Illuminate\Support\Collection
     */
    public function title(): string
    {
        return 'Báo cáo nội dung công việc';
    }
    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Nội dung công việc',
            'Doanh thu'
        ];
    }

    public function map($item): array
    {
        return [
            $item->name,
            $item->total,
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
class UserExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithTitle, WithCustomStartCell
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
    /**
     * @return \Illuminate\Support\Collection
     */
    public function title(): string
    {
        return 'Báo cáo doanh thu nhân viên';
    }
    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Họ tên nhân viên',
            'Email',
            'Doanh thu'
        ];
    }

    public function map($item): array
    {
        return [
            $item->name,
            $item->email,
            $item->total,
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
