<?php

namespace App\Http\Livewire\Ketoan\baocao;

use App\Http\Livewire\Base\BaseLive;
use App\Http\Livewire\Traits\WithPagination;
use Livewire\Component;
use App\Models\Customer;
use App\Models\User;
use App\Models\Order;
use App\Exports\ThuDetailExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Enum\EOrder;
use Carbon\Carbon;

class ChiTietThuList extends BaseLive
{
    // use WithPagination;
    public $customerPhone;

    public $fromDate;
    public $toDate;
    public $fromDateAfter;
    public $toDateAfter;
    public $fromDateBefore;
    public $toDateBefore;
    public $timeStartDay = ' 00:00:00';
    public $timeEndDay = ' 23:59:59';

    public $showId;

    public $chassicNo;
    public $engineNo;
    public $motorNumbers;
    protected $listeners = ['setfromDate1', 'settoDate1'];
    public function mount()
    {
        $this->fromDate =  Carbon::create( Carbon::now()->year, Carbon::now()->month,1)->format('Y-m-d');
        // $this->fromDate =  Carbon::now()->format('Y-m-01');
        $this->toDate = Carbon::now()->format('Y-m-d');
        $this->fromDateAfter = $this->fromDate . $this->timeEndDay;
        $this->toDateAfter = $this->toDate . $this->timeEndDay;
        $this->fromDateBefore = $this->fromDate . $this->timeStartDay;
        $this->toDateBefore = $this->toDate . $this->timeStartDay;
        $this->key_name = 'code';
        $this->sortingName = 'desc';
        $this->key_name2 = 'created_at';
        $this->sortingName2 = 'asc';
    }
    public function setfromDate1($time)
    {
        $this->fromDate = date('Y-m-d', strtotime($time['fromDate1']));
        $this->updatedFromDate();
    }
    public function settoDate1($time)
    {
        $this->toDate = date('Y-m-d', strtotime($time['toDate1']));
        $this->updatedToDate();
    }
    public function render()
    {
        $this->emit('setDatePicker');
        if ($this->reset) {
            $this->reset = null;
            $this->mount();
            $this->customerPhone = null;
            $this->chassicNo = null;
            $this->engineNo = null;
            $this->motorNumbers = null;
        }
        $fromDateBefore = $this->fromDateBefore;
        $toDateAfter = $this->toDateAfter;
        // type_table = 1 => receipts, type_table = 2 => orders
        $userIDOrders = Customer::leftJoin('orders', 'orders.customer_id', 'customers.id')
            ->leftJoin('receipts', 'receipts.customer_id', 'customers.id')
            ->where(function ($query) use ($fromDateBefore, $toDateAfter) {
                $query->where('orders.created_at', '>=', $fromDateBefore)
                    ->where(function ($query2) {
                        $query2->where('orders.status', 1);
                        $query2->orwhere('orders.status', 2);
                    })
                    ->where('orders.created_at', '<=', $toDateAfter)
                    ->where('orders.category', '<>', EOrder::OTHER)
                    ->whereNull('orders.deleted_at')
                    ->where('orders.isvirtual', false)
                    ->where('order_type', 1);
                $query->orwhere('receipts.receipt_date', '>=', $fromDateBefore)->where('receipts.receipt_date', '<=', $toDateAfter);
            });
        if ($this->customerPhone) {
            $userIDOrders = $userIDOrders->where('customers.phone', $this->customerPhone);
        }
        if ($this->chassicNo || $this->engineNo || $this->motorNumbers) {
            $userIDOrders = $userIDOrders->leftJoin('motorbikes', 'motorbikes.customer_id', 'customers.id');
            if ($this->chassicNo) {
                $userIDOrders = $userIDOrders->where('motorbikes.chassic_no', $this->chassicNo);
            }
            if ($this->engineNo) {
                $userIDOrders = $userIDOrders->where('motorbikes.engine_no', $this->engineNo);
            }
            if ($this->motorNumbers) {
                $userIDOrders = $userIDOrders->where('motorbikes.motor_numbers', $this->motorNumbers);
            }
        }
        $userIDOrders = $userIDOrders->select('customers.id')
            ->distinct()
            ->pluck('customers.id')
            ->toArray();

        $sql = 'SELECT 3';
        $sql2 = 'SELECT 1';
        $sql3 = 'SELECT -1';
        $userUnPaidOrdersBefore = Customer::whereIn('customers.id', $userIDOrders)
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone')
            ->selectSub($sql3, 'total_money')
            ->selectSub($sql3, 'note')
            ->selectSub($sql3, 'created_at')
            ->selectSub($sql, 'type_table')
            ->selectSub($sql2, 'type_table2');
        // dd($userUnPaidOrdersBefore->get());
        $sql = 'SELECT 0';
        $sql2 = 'SELECT 4';
        $sql3 = 'SELECT -1';
        $userUnPaidOrdersAfter = Customer::whereIn('customers.id', $userIDOrders)
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone')
            ->selectSub($sql3, 'total_money')
            ->selectSub($sql3, 'note')
            ->selectSub($sql3, 'created_at')
            ->selectSub($sql, 'type_table')
            ->selectSub($sql2, 'type_table2');


        $sql = 'SELECT 1';
        $sql2 = 'SELECT 2';
        $currentUserPaid = Customer::whereIn('customers.id', $userIDOrders)
            ->join('receipts', 'receipts.customer_id', 'customers.id')
            ->where('receipts.receipt_date', '>=', $this->fromDateBefore)
            ->where('receipts.receipt_date', '<=', $this->toDateAfter)
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'receipts.money as total_money', 'receipts.note', 'receipts.receipt_date as created_at')
            ->selectSub($sql, 'type_table')
            ->selectSub($sql2, 'type_table2');
        // dd($currentUserPaid->get());
        $sql = 'SELECT 2';
        $sql2 = 'SELECT 2';
        $userOrders = Customer::whereIn('customers.id', $userIDOrders)
            ->join('orders', 'orders.customer_id', 'customers.id')
            ->where('orders.created_at', '>=', $this->fromDateBefore)
            ->where('orders.created_at', '<=', $this->toDateAfter)
            ->where('orders.category', '<>', EOrder::OTHER)
            ->where('orders.isvirtual', false)
            ->where('orders.order_type', 1)
            ->whereNull('orders.deleted_at')
            ->where(function ($query) {
                $query->where('orders.status', '=', 1);
                $query->orWhere('orders.status', '=', 2);
            })
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'orders.total_money', 'orders.note', DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m-%d") as created_at'))
            ->selectSub($sql, 'type_table')
            ->selectSub($sql2, 'type_table2')
            ->unionAll($currentUserPaid)
            ->unionAll($userUnPaidOrdersBefore)
            ->unionAll($userUnPaidOrdersAfter)
            ->orderBy($this->key_name, $this->sortingName)
            ->orderBy('type_table2', 'ASC');
        // ->orderBy(DB::raw("DATE_FORMAT(created_at,'%d-%M-%Y')"), 'ASC')
        // ->orderBy('type_table','DESC');
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
        // dd($userOrders->get());
        $userOrders = $userOrders->paginate($this->perPage);

        return view('livewire.ketoan.baocao.chi-tiet-thu', ['data' => $userOrders]);
    }
    public function updatedFromDate()
    {
        $this->fromDateAfter = $this->fromDate . $this->timeEndDay;
        $this->fromDateBefore = $this->fromDate . $this->timeStartDay;
        $this->resetPage();
    }

    public function updatedTodate()
    {
        $this->toDateAfter = $this->toDate . $this->timeEndDay;
        $this->toDateBefore = $this->toDate . $this->timeStartDay;
        // $this->fromDate = $this->toDate;
        // $this->fromDateAfter = $this->fromDate.$this->timeEndDay;
        // $this->fromDateBefore = $this->fromDate.$this->timeStartDay;
        $this->resetPage();
    }
    public function updatedCustomerPhone()
    {
        $this->customerPhone = trim($this->customerPhone);
        $customer = Customer::where('customers.phone', $this->customerPhone)->get()->first();
        $this->resetPage();
    }
    public function updatedChassicNo()
    {
        $this->chassicNo = trim($this->chassicNo);
        $this->resetPage();
    }
    public function updatedEngineNo()
    {
        $this->engineNo = trim($this->engineNo);
        $this->resetPage();
    }
    public function updatedMotorNumbers()
    {
        $this->motorNumbers = trim($this->motorNumbers);
        $this->resetPage();
    }
    public function export()
    {
        $today = date("d_m_Y");
        $this->emit('setDatePicker');
        return Excel::download(new ThuDetailExport($this->fromDateBefore, $this->toDateAfter, $this->customerPhone, $this->chassicNo, $this->engineNo, $this->motorNumbers, $this->fromDate, $this->toDate, $this->key_name, $this->sortingName, $this->key_name2, $this->sortingName2), 'Chi-Tiết-Phải-Thu-' . $today . '.xlsx');
    }
}
