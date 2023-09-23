<?php

namespace App\Http\Livewire\Utilities;

use App\Exports\OverdueCustomerExport;
use App\Http\Livewire\Base\BaseLive;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class OverdueCustomerList extends BaseLive
{
    public $searchCode;
    public $searchName;
    public $searchPhone;


    public function render()
    {
        $this->searchCode = trim($this->searchCode);
        $this->searchName = trim($this->searchName);
        $this->searchPhone = trim($this->searchPhone);
        if ($this->reset) {
            $this->searchCode = null;
        }
        $today = date('Y-m-d');
        $day =  strtotime('-7 day', strtotime($today));
        $diffDate  = date('Y-m-d', $day);
        $OverdueCustomer = DB::table('customers')
            ->whereNull('customers.deleted_at')
            ->leftJoin('orders', 'orders.customer_id', '=', 'customers.id')
            ->leftJoin('receipts', 'receipts.customer_id', '=', 'customers.id')
            ->whereNull('orders.deleted_at')
            ->whereDate('orders.created_at', '<=', $today)
            //->whereRaw("((receipt_date <= '{$today}' AND receipt_date > '{$diffDate}') OR receipt_date IS NULL)")
            ->selectRaw("max(receipts.receipt_date) as receipt_date, SUM(orders.total_money) as sum_total_money, SUM(orders.total_money) - SUM(receipts.money) AS remainAmount")
            ->selectRaw('customers.id, customers.name,customers.code,customers.phone')
            ->groupByRaw('customers.id, customers.name, customers.code ,customers.phone')
            ->havingRaw("max(receipts.receipt_date) <= '{$diffDate}'")
            ->havingRaw('remainAmount > 0');
            //->orHavingRaw('remainAmount is null');
        if ($this->searchCode) {
            $OverdueCustomer->where('customers.code', $this->searchCode);
        }
        if ($this->searchName) {
            $OverdueCustomer->where('customers.name', 'LIKE', '%' . $this->searchName . '%');
        }
        if ($this->searchPhone) {
            $OverdueCustomer->where('customers.phone', $this->searchPhone);
        }
        $data = $OverdueCustomer->paginate($this->perPage);
        return view('livewire.utilities.overdue-customer-list', ['data' => $data]);
    }
    public function export()
    {
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        $day =  strtotime('-7 day', strtotime($today));
        $diffDate  = date('Y-m-d', $day);
        $this->listOrders = Order::leftJoin('receipts', 'receipts.customer_id', '=', 'orders.customer_id')
            ->with('customer')
            ->whereDate('orders.created_at', '<=', $today)
            ->whereDate('receipt_date', '<=', $today)
            ->whereDate('receipt_date', '>', $diffDate)
            ->select(DB::raw('count(orders.customer_id) as countCustomer'), 'orders.customer_id', DB::raw('SUM(total_money) - SUM(money) AS remainAmount'))
            ->groupBy('orders.customer_id')->get();
        $this->countRecords = $this->listOrders->count();
        if ($this->countRecords == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Không có bản ghi nào!']);
            $this->emit('close-modal-export');
        } else {
            return Excel::download(new OverdueCustomerExport, 'danhsachkhachhangnoquahan' . date('Y-m-d-His') . '.xlsx');
        }
    }
}
