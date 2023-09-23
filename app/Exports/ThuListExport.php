<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\User;
use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enum\EOrder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class ThuListExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithCustomStartCell, ShouldAutoSize, WithColumnFormatting
{
    use RegistersEventListeners;
    public $stt = 0;

    protected $customerID;
    protected $fromDateBefore;
    protected $toDateAfter;
    protected $customerName;
    protected $customerAddress;
    protected $customerCode;
    protected $customerPhone;
    protected $key_name;
    protected $sortingName;
    protected $type;

    protected $fromDate;
    protected $toDate;

    protected $countRow = 0;
    function __construct($customerID, $fromDateBefore, $toDateAfter, $customerName, $customerAddress, $customerCode, $customerPhone, $fromDate, $toDate, $key_name, $sortingName, $type)
    {
        $this->customerID = trim($customerID);
        $this->fromDateBefore = trim($fromDateBefore);
        $this->toDateAfter = trim($toDateAfter);
        $this->customerName = trim($customerName);
        $this->customerAddress = trim($customerAddress);
        $this->customerCode = trim($customerCode);
        $this->customerPhone = trim($customerPhone);
        $this->key_name = trim($key_name);
        $this->sortingName = trim($sortingName);
        $this->type = $type;
        $this->fromDate = trim($fromDate);
        $this->toDate = trim($toDate);
    }

    public function collection()
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $sql = 'SELECT 0';
        $userMoney2 = Customer::leftJoin('orders', function ($leftJoin) {
            $leftJoin->on('orders.customer_id', '=', 'customers.id')
                ->where('orders.order_type', 1)
                ->where('orders.created_at', '>=', $this->fromDateBefore)
                ->where('orders.created_at', '<=', $this->toDateAfter)
                ->where('orders.category', '<>', EOrder::OTHER)
                ->where('orders.isvirtual', false)
                ->where(function ($query) {
                    $query->where('orders.status', '=', 1);
                    $query->orWhere('orders.status', '=', 2);
                });
        })
            ->groupBy('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->selectSub($sql, 'total_money1')
            ->addSelect(DB::raw('SUM(total_money) AS total_money2'))
            ->selectSub($sql, 'total_money3')
            ->selectSub($sql, 'total_money4');
        $userMoney2 = $this->getQuerySearch($userMoney2);
        // dd($userMoney2->get());
        $userMoney3 = Customer::leftJoin('receipts', function ($leftJoin) {
            $leftJoin->on('receipts.customer_id', '=', 'customers.id')
                ->where('receipts.receipt_date', '>=', $this->fromDateBefore)
                ->where('receipts.receipt_date', '<=', $this->toDateAfter);
        })
            ->groupBy('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->selectSub($sql, 'total_money1')
            ->selectSub($sql, 'total_money2')
            ->addSelect(DB::raw('SUM(money) AS total_money3'))
            ->selectSub($sql, 'total_money4');
        $userMoney3 = $this->getQuerySearch($userMoney3);
        // dd($userMoney3->get());
        $userMoney1a = Customer::leftJoin('receipts', function ($leftJoin) {
            $leftJoin->on('receipts.customer_id', '=', 'customers.id')
                ->where('receipts.receipt_date', '<', $this->fromDateBefore);
        })
            ->groupBy('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address', DB::raw('SUM(money) AS money1a'))
            ->selectSub($sql, 'total_money1b');
        $userMoney1a = $this->getQuerySearch($userMoney1a);
        $userMoney1b = Customer::leftJoin('orders', function ($leftJoin) {
            $leftJoin->on('orders.customer_id', '=', 'customers.id')
                ->where('orders.order_type', 1)
                ->where('orders.created_at', '<', $this->fromDateBefore)
                ->where('orders.category', '<>', EOrder::OTHER)
                ->where('orders.isvirtual', false)
                ->where(function ($query) {
                    $query->where('orders.status', '=', 1);
                    $query->orWhere('orders.status', '=', 2);
                });
        })
            ->groupBy('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->selectSub($sql, 'total_money1a')
            ->addSelect(DB::raw('SUM(total_money) AS total_money1b'))
            ->unionAll($userMoney1a);
        $userMoney1b = $this->getQuerySearch($userMoney1b);
        $userMoney1 = DB::table(DB::raw("({$userMoney1b->toSql()}) as sub"))
            ->mergeBindings($userMoney1b->getQuery())
            ->groupBy('id', 'code', 'name', 'phone', 'address')
            ->select('id', 'code', 'name', 'phone', 'address', DB::raw('SUM(total_money1b) - SUM(total_money1a) AS total_money1'))
            ->selectSub($sql, 'total_money2')
            ->selectSub($sql, 'total_money3')
            ->selectSub($sql, 'total_money4');
        // dd($userMoney1->get());
        $userMoney4a = Customer::leftJoin('receipts', function ($leftJoin) {
            $leftJoin->on('receipts.customer_id', '=', 'customers.id')
                ->where('receipts.receipt_date', '<', $this->toDateAfter);
        })
            ->groupBy('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address', DB::raw('SUM(money) AS total_money4a'))
            ->selectSub($sql, 'total_money4b');
        $userMoney4a = $this->getQuerySearch($userMoney4a);
        $userMoney4b = Customer::leftJoin('orders', function ($leftJoin) {
            $leftJoin->on('orders.customer_id', '=', 'customers.id')
                ->where('orders.order_type', 1)
                ->where('orders.created_at', '<', $this->toDateAfter)
                ->where('orders.category', '<>', EOrder::OTHER)
                ->where('orders.isvirtual', false)
                ->where(function ($query) {
                    $query->where('orders.status', '=', 1);
                    $query->orWhere('orders.status', '=', 2);
                });
        })
            ->groupBy('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->selectSub($sql, 'total_money4a')
            ->addSelect(DB::raw('SUM(total_money) AS total_money4b'))
            ->unionAll($userMoney4a);
        $userMoney4b = $this->getQuerySearch($userMoney4b);
        $userMoney4 = DB::table(DB::raw("({$userMoney4b->toSql()}) as sub4"))
            ->mergeBindings($userMoney4b->getQuery())
            ->groupBy('id', 'code', 'name', 'phone', 'address')
            ->select('id', 'code', 'name', 'phone', 'address')
            ->selectSub($sql, 'total_money1')
            ->selectSub($sql, 'total_money2')
            ->selectSub($sql, 'total_money3')
            ->addSelect(DB::raw('(SUM(total_money4b) - SUM(total_money4a)) AS total_money4'));
        // dd($userMoney4->get());

        $userMoney2->unionAll($userMoney1)
            ->unionAll($userMoney3)
            ->unionAll($userMoney4);
        // dd($userMoney2->get());
        $userOrders = DB::table(DB::raw("({$userMoney2->toSql()}) as sub5"))
            ->mergeBindings($userMoney2->getQuery())
            ->groupBy('id', 'code', 'name', 'phone', 'address')
            ->select('id', 'code', 'name', 'phone', 'address', DB::raw('SUM(total_money1) AS total_money1'), DB::raw('SUM(total_money2) AS total_money2'), DB::raw('SUM(total_money3) AS total_money3'), DB::raw('SUM(total_money4) AS total_money4'));
        if ($this->type == 1) {
            $userOrders = $userOrders->havingRaw('SUM(total_money4) > 0');
        }
        if ($this->type == 2) {
            $userOrders = $userOrders->havingRaw('SUM(total_money4) = 0');
        }

        $userOrders = $userOrders->orderBy($this->key_name, $this->sortingName)->get();
        $this->countRow = count($userOrders);
        return $userOrders;
    }
    public function headings(): array
    {
        return [
            'STT',
            'Mã khách hàng',
            'Tên khách hàng',
            'Số điện thoại',
            'Địa chỉ',
            'Số dư nợ đầu kỳ',
            'Số tiền mua hàng trong kỳ',
            'Đã thanh toán trong kỳ',
            'Dự nợ còn lại phải thu',
        ];
    }
    public function getQuerySearch($query)
    {
        if ($this->customerID) {
            $query->where('customers.id', $this->customerID);
        }
        if ($this->customerName) {
            $query->where('customers.name', 'like', '%' . $this->customerName . '%');
        }
        if ($this->customerAddress) {
            $query->where('customers.address', 'like', '%' . $this->customerAddress . '%');
        }
        return $query;
    }

    public function map($user): array
    {
        $map = [
            ++$this->stt,
            $user->code,
            $user->name,
            (string)$user->phone,
            getAddressByUserId($user->id),
            $user->total_money1,
            $user->total_money2,
            $user->total_money3,
            $user->total_money4,
        ];
        return $map;
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
                $default_font_style2 = [
                    'font' => ['name' => 'Times New Roman', 'size' => 12],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ];
                $default_font_style1 = [
                    'font' => ['name' => 'Times New Roman', 'size' => 12, 'bold' => true],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ];
                $default_font_style_title = [
                    'font' => ['name' => 'Times New Roman', 'size' => 12, 'bold' =>  true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => '00d6d6c2'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                    'wrapText' => true,
                ];
                $active_sheet = $event->sheet->getDelegate();
                $event->sheet->getStyle('A4:I4')->getAlignment()->setWrapText(true);
                $active_sheet->getStyle('A4:I4')->getAlignment()->applyFromArray(
                    array('horizontal' => 'center', 'vertical' => 'center')
                );
                $active_sheet->getStyle('A4:I4')->applyFromArray($default_font_style_title);
                $arrayAlphabet = [
                    'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'
                ];
                foreach ($arrayAlphabet as $alphabet) {
                    $event->sheet->getColumnDimension($alphabet)->setWidth(25);
                }
                $event->sheet->getColumnDimension('A')->setWidth(5);
                if ($this->countRow) {
                    $active_sheet->getStyle('A5:I' . ($this->countRow + 4))->applyFromArray($default_font_style2);
                }
                // $active_sheet->getStyle('C1:C2')->applyFromArray($default_font_style2);
                // $active_sheet->getStyle('A1:B2')->applyFromArray($default_font_style1);
            },
        ];
    }
    public function startCell(): string
    {
        return 'A4';
    }
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER
        ];
    }
}
