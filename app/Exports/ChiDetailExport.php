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

class ChiDetailExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithCustomStartCell
{
    use RegistersEventListeners;
    public $stt = 0;
    protected $fromDateBefore;
    protected $toDateAfter;
    protected $customerCode;

    protected $fromDate;
    protected $toDate;

    protected $key_name;
    protected $sortingName;
    protected $key_name2;
    protected $sortingName2;

    protected $userMoneyUnPaidOrdersBefore;
    protected $userOrders;

    protected $countRow = 0;
    function __construct($fromDateBefore, $toDateAfter, $customerCode, $fromDate, $toDate, $key_name, $sortingName, $key_name2, $sortingName2)
    {
        $this->fromDateBefore = trim($fromDateBefore);
        $this->toDateAfter = trim($toDateAfter);
        $this->customerCode = trim($customerCode);
        $this->fromDate = trim($fromDate);
        $this->toDate = trim($toDate);
        $this->key_name = trim($key_name);
        $this->sortingName = trim($sortingName);
        $this->key_name2 = trim($key_name2);
        $this->sortingName2 = trim($sortingName2);
    }

    public function collection()
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $fromDateBefore = $this->fromDateBefore;
        $toDateAfter = $this->toDateAfter;
        // type_table = 1 => receipts, type_table = 2 => orders
        $userIDOrders = Supplier::leftJoin('orders', 'orders.supplier_id', 'suppliers.id')
            ->leftJoin('payments', 'payments.supplier_id', 'suppliers.id')
            ->where(function ($query) use ($fromDateBefore, $toDateAfter) {
                $query->where('orders.created_at', '>=', $fromDateBefore)
                    ->where(function ($query2) {
                        $query2->where('orders.status', 1);
                        $query2->orwhere('orders.status', 2);
                    })
                    ->where('orders.created_at', '<=', $toDateAfter)
                    ->where('order_type', 2);
                $query->orwhere('payments.payment_date', '>=', $fromDateBefore)->where('payments.payment_date', '<=', $toDateAfter);
            });
        if ($this->customerCode) {
            $userIDOrders = $userIDOrders->where('suppliers.code', $this->customerCode);
        }
        $userIDOrders = $userIDOrders->select('suppliers.id')
            ->distinct()
            ->pluck('suppliers.id')
            ->toArray();
        $sql = 'SELECT 3';
        $sql2 = 'SELECT 1';
        $sql3 = 'SELECT -1';
        $userUnPaidOrdersBefore = Supplier::whereIn('suppliers.id', $userIDOrders)
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name')
            ->selectSub($sql3, 'total_money')
            ->selectSub($sql3, 'note')
            ->selectSub($sql3, 'created_at')
            ->selectSub($sql, 'type_table')
            ->selectSub($sql2, 'type_table2');
        // dd($userUnPaidOrdersBefore->get());
        $sql = 'SELECT 0';
        $sql2 = 'SELECT 4';
        $sql3 = 'SELECT -1';
        $userUnPaidOrdersAfter = Supplier::whereIn('suppliers.id', $userIDOrders)
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name')
            ->selectSub($sql3, 'total_money')
            ->selectSub($sql3, 'note')
            ->selectSub($sql3, 'created_at')
            ->selectSub($sql, 'type_table')
            ->selectSub($sql2, 'type_table2');


        $sql = 'SELECT 1';
        $sql2 = 'SELECT 2';
        $currentUserPaid = Supplier::whereIn('suppliers.id', $userIDOrders)
            ->Join('payments', 'payments.supplier_id', 'suppliers.id')
            ->where('payments.payment_date', '>=', $this->fromDateBefore)
            ->where('payments.payment_date', '<=', $this->toDateAfter)
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name', 'payments.money as total_money', 'payments.note', 'payments.payment_date as created_at')
            ->selectSub($sql, 'type_table')
            ->selectSub($sql2, 'type_table2');
        // dd($currentUserPaid->get());
        $sql = 'SELECT 2';
        $sql2 = 'SELECT 2';
        $userOrders = Supplier::whereIn('suppliers.id', $userIDOrders)
            ->join('orders', 'orders.supplier_id', 'suppliers.id')
            ->where('orders.created_at', '>=', $this->fromDateBefore)
            ->where('orders.created_at', '<=', $this->toDateAfter)
            ->where('orders.order_type', 2)
            ->where(function ($query) {
                $query->where('orders.status', '=', 1);
                $query->orWhere('orders.status', '=', 2);
            })
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name', 'orders.total_money', 'orders.note', DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m-%d") as created_at'))
            ->selectSub($sql, 'type_table')
            ->selectSub($sql2, 'type_table2')
            ->unionAll($currentUserPaid)
            ->unionAll($userUnPaidOrdersBefore)
            ->unionAll($userUnPaidOrdersAfter)
            ->orderBy($this->key_name, $this->sortingName)
            ->orderBy('type_table2', 'ASC');
        // ->orderBy(DB::raw("DATE_FORMAT(created_at,'%d-%M-%Y')"), 'ASC')
        // ->orderBy('type_table','DESC');
        // dd($userOrders->get());
        // dd($this->keyName);
        if ($this->key_name2 == 'created_at') {
            $userOrders->orderBy('created_at', $this->sortingName2);
            $userOrders->orderBy('type_table', 'DESC');
        } else {
            if ($this->key_name2 == 'total_money1') {
                $userOrders->orderBy('type_table', 'DESC');
                $userOrders->orderBy('total_money', $this->sortingName2);
            } elseif ($this->key_name2 == 'total_money2') {
                $userOrders->orderBy('type_table', 'ASC');
                $userOrders->orderBy('total_money', $this->sortingName2);
            } else {
                $userOrders->orderBy('total_money', $this->sortingName2);
                $userOrders->orderBy('type_table', 'DESC');
            }
        }
        $userOrders = $userOrders->get();
        $this->userOrders = $userOrders;
        $this->countRow = count($userOrders);
        return $userOrders;
    }
    public function headings(): array
    {
        return [
            'Mã NCC',
            'Tên nhà cung cấp',
            'Ngày',
            'Nội dung',
            'Dư nợ đầu kỳ',
            'Giá trị bán',
            'Giá trị thanh toán',
            'Dự nợ phải trả',
        ];
    }


    public function map($user): array
    {
        if ($user->type_table == 3) {
            $map = [
                $user->code,
                $user->name,
                '',
                '',
                numberFormat($user->ordersUnPaidBefore($this->fromDateBefore)), //
                '',
                '',
                '',
            ];
        } elseif ($user->type_table == 0) {
            $map = [
                'Dư nợ cuối phải trả',
                '',
                '',
                '',
                '',
                '',
                '',
                numberFormat($user->ordersUnPaid($this->toDateAfter)),
            ];
        } else {
            $map = [
                '',
                '',
                reFormatDate($user->created_at),
                $user->note,
                '', //
                $user->type_table == 2 ? numberFormat($user->total_money) : '',
                $user->type_table == 1 ? numberFormat($user->total_money) : '',
                '',
            ];
        }


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
                $event->sheet->getStyle('A4:H4')->getAlignment()->setWrapText(true);
                $active_sheet->getStyle('A4:H4')->getAlignment()->applyFromArray(
                    array('horizontal' => 'center', 'vertical' => 'center')
                );
                $active_sheet->getStyle('A4:h4')->applyFromArray($default_font_style_title);
                $arrayAlphabet = [
                    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
                ];
                foreach ($arrayAlphabet as $alphabet) {
                    $event->sheet->getColumnDimension($alphabet)->setWidth(25);
                }
                // $event->sheet->getColumnDimension('A')->setWidth(5);
                if ($this->countRow) {
                    $active_sheet->getStyle('A5:H' . ($this->countRow + 4))->applyFromArray($default_font_style2);
                }
                foreach ($this->userOrders as $key => $value) {
                    if ($value->type_table == 3) {
                        $active_sheet->getStyle('A' . ($key + 5) . ':H' . ($key + 5))->applyFromArray($default_font_style1);
                    } elseif ($value->type_table == 0) {
                        $active_sheet->getStyle('A' . ($key + 5) . ':H' . ($key + 5))->getFont()->getColor()->setARGB('E74C3C');
                    }
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
