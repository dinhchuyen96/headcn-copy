<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\User;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ChiListExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithCustomStartCell
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

    protected $fromDate;
    protected $toDate;

    protected $countRow = 0;
    function __construct($customerID, $fromDateBefore, $toDateAfter, $customerName, $customerAddress, $customerCode, $customerPhone, $fromDate, $toDate, $key_name, $sortingName)
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

        $this->fromDate = trim($fromDate);
        $this->toDate = trim($toDate);
    }

    public function collection()
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $sql = 'SELECT 0';
        $userMoney2 = Supplier::leftJoin('orders', function ($leftJoin) {
            $leftJoin->on('orders.supplier_id', '=', 'suppliers.id')
                ->where('orders.order_type', 2)
                ->where('orders.created_at', '>=', $this->fromDateBefore)
                ->where('orders.created_at', '<=', $this->toDateAfter)
                ->where(function ($query) {
                    $query->where('orders.status', '=', 1);
                    $query->orWhere('orders.status', '=', 2);
                });
        })
            ->groupBy('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->selectSub($sql, 'total_money1')
            ->addSelect(DB::raw('SUM(total_money) AS total_money2'))
            ->selectSub($sql, 'total_money3')
            ->selectSub($sql, 'total_money4');
        $userMoney2 = $this->getQuerySearch($userMoney2);
        // dd($userMoney2->get());
        $userMoney3 = Supplier::leftJoin('payments', function ($leftJoin) {
            $leftJoin->on('payments.supplier_id', '=', 'suppliers.id')
                ->where('payments.payment_date', '>=', $this->fromDateBefore)
                ->where('payments.payment_date', '<=', $this->toDateAfter);
        })
            ->groupBy('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->selectSub($sql, 'total_money1')
            ->selectSub($sql, 'total_money2')
            ->addSelect(DB::raw('SUM(money) AS total_money3'))
            ->selectSub($sql, 'total_money4');
        $userMoney3 = $this->getQuerySearch($userMoney3);
        // dd($userMoney3->get());
        $userMoney1a = Supplier::leftJoin('payments', function ($leftJoin) {
            $leftJoin->on('payments.supplier_id', '=', 'suppliers.id')
                ->where('payments.payment_date', '<', $this->fromDateBefore);
        })
            ->groupBy('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address', DB::raw('SUM(money) AS money1a'))
            ->selectSub($sql, 'total_money1b');
        $userMoney1a = $this->getQuerySearch($userMoney1a);
        $userMoney1b = Supplier::leftJoin('orders', function ($leftJoin) {
            $leftJoin->on('orders.supplier_id', '=', 'suppliers.id')
                ->where('orders.order_type', 2)
                ->where('orders.created_at', '<', $this->fromDateBefore)
                ->where(function ($query) {
                    $query->where('orders.status', '=', 1);
                    $query->orWhere('orders.status', '=', 2);
                });
        })
            ->groupBy('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
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
        $userMoney4a = Supplier::leftJoin('payments', function ($leftJoin) {
            $leftJoin->on('payments.supplier_id', '=', 'suppliers.id')
                ->where('payments.payment_date', '<', $this->toDateAfter);
        })
            ->groupBy('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address', DB::raw('SUM(money) AS money4a'))
            ->selectSub($sql, 'total_money4b');
        $userMoney4a = $this->getQuerySearch($userMoney4a);
        $userMoney4b = Supplier::leftJoin('orders', function ($leftJoin) {
            $leftJoin->on('orders.supplier_id', '=', 'suppliers.id')
                ->where('orders.order_type', 2)
                ->where('orders.created_at', '<', $this->toDateAfter)
                ->where(function ($query) {
                    $query->where('orders.status', '=', 1);
                    $query->orWhere('orders.status', '=', 2);
                });
        })
            ->groupBy('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
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
        $userOrders = $userOrders->orderBy($this->key_name, $this->sortingName)->get();
        $this->countRow = count($userOrders);
        return $userOrders;
    }
    public function headings(): array
    {
        return [
            'STT',
            'Mã NCC',
            'Tên NCC',
            'Số điện thoại',
            'Địa chỉ',
            'Số dư nợ đầu kỳ',
            'Số tiền mua hàng trong kỳ',
            'Đã thanh toán trong kỳ',
            'Dự nợ còn lại phải trả',
        ];
    }

    public function getQuerySearch($query)
    {
        if ($this->customerID) {
            $query->where('suppliers.id', $this->customerID);
        }
        if ($this->customerName) {
            $query->where('suppliers.name', 'like', '%' . $this->customerName . '%');
        }
        if ($this->customerAddress) {
            $query->where('suppliers.address', 'like', '%' . $this->customerAddress . '%');
        }
        return $query;
    }
    public function map($user): array
    {
        $map = [
            ++$this->stt,
            $user->code,
            $user->name,
            $user->phone,
            $user->address,
            numberFormat($user->total_money1),
            numberFormat($user->total_money2),
            numberFormat($user->total_money3),
            numberFormat($user->total_money4),

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
                    'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
                ];
                foreach ($arrayAlphabet as $alphabet) {
                    $event->sheet->getColumnDimension($alphabet)->setWidth(25);
                }
                $event->sheet->getColumnDimension('A')->setWidth(5);
                if ($this->countRow) {
                    $active_sheet->getStyle('A5:I' . ($this->countRow + 4))->applyFromArray($default_font_style2);
                }
                $active_sheet->getStyle('C1:C2')->applyFromArray($default_font_style2);
                $active_sheet->getStyle('A1:B2')->applyFromArray($default_font_style1);
            },
        ];
    }
    public function startCell(): string
    {
        return 'A4';
    }
}
