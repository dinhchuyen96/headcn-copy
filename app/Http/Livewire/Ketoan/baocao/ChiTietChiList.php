<?php

namespace App\Http\Livewire\Ketoan\baocao;

use App\Http\Livewire\Base\BaseLive;
use App\Http\Livewire\Traits\WithPagination;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Order;
use App\Exports\ChiDetailExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChiTietChiList extends BaseLive
{
    // use WithPagination;
    public $customerCode;

    public $fromDate;
    public $toDate;
    public $fromDateAfter;
    public $toDateAfter;
    public $fromDateBefore;
    public $toDateBefore;
    public $timeStartDay = ' 00:00:00';
    public $timeEndDay = ' 23:59:59';
    protected $listeners = ['setfromDate1', 'settoDate1'];
    public $showId;
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
            $this->customerCode = null;
        }
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
                    ->whereNull('orders.deleted_at')
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
            ->whereNull('orders.deleted_at')
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
        $userOrders = $userOrders->paginate($this->perPage);

        return view('livewire.ketoan.baocao.chi-tiet-chi', ['data' => $userOrders]);
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
    public function updatedCustomerCode()
    {
        $this->customerCode = trim($this->customerCode);
        $this->resetPage();
    }
    public function export()
    {
        $today = date("d_m_Y");
        $this->emit('setDatePicker');
        return Excel::download(new ChiDetailExport($this->fromDateBefore, $this->toDateAfter, $this->customerCode, $this->fromDate, $this->toDate, $this->key_name, $this->sortingName, $this->key_name2, $this->sortingName2), 'Chi-Tiết-Phải-Trả-' . $today . '.xlsx');
    }
}
