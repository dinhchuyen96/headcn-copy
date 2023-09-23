<?php

namespace App\Http\Livewire\Ketoan\baocao;

use App\Http\Livewire\Base\BaseLive;
use App\Http\Livewire\Traits\WithPagination;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Order;
use App\Exports\ChiListExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class FormChiList extends BaseLive
{
    public $customerId;
    public $fromDateBefore;
    public $toDateAfter;
    protected $listeners = ['setCustomer'];
    public function render()
    {
        $currentCustomer = '';
        $currentCustomerOrder = '';
        $ordersUnPaidBefore = '';
        if ($this->customerId) {
            // type_table = 1 => receipts, type_table = 2 => orders
            $sql = 'SELECT 1';
            $currentUserPaid = Supplier::join('payments', 'payments.supplier_id', 'suppliers.id')
                ->where('suppliers.id', $this->customerId)
                ->where('payments.payment_date', '>=', $this->fromDateBefore)
                ->where('payments.payment_date', '<=', $this->toDateAfter)
                ->select('payments.payment_date as created_at', 'payments.note', 'payments.money as total_money')
                ->selectSub($sql, 'type_table');
            // dd($currentUserPaid->get());
            $sql = 'SELECT 2';
            $currentCustomerOrder = Supplier::find($this->customerId)
                ->ordersDuring($this->fromDateBefore, $this->toDateAfter)
                ->where(function ($query) {
                    $query->where('orders.status', '=', 1);
                    $query->orWhere('orders.status', '=', 2);
                })
                ->select(DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m-%d") as created_at'), 'orders.note', 'orders.total_money')
                ->selectSub($sql, 'type_table')
                ->unionAll($currentUserPaid)
                ->orderBy('created_at', 'ASC')
                ->orderBy('type_table', 'DESC')
                ->paginate(10);
            $currentCustomer = Supplier::find($this->customerId);

            $ordersUnPaidBefore = $currentCustomer->ordersUnPaidBefore($this->fromDateBefore);
        }
        return view('livewire.ketoan.baocao.form-chi', ['currentCustomer' => $currentCustomer, 'currentCustomerOrder' => $currentCustomerOrder, 'ordersUnPaidBefore' => $ordersUnPaidBefore]);
    }
    public function setCustomer($id, $fromDateBefore, $toDateAfter)
    {
        $this->customerId = $id;
        $this->fromDateBefore = $fromDateBefore;
        $this->toDateAfter = $toDateAfter;
    }
    public function clickCancel()
    {
        $this->resetPage();
    }
}
