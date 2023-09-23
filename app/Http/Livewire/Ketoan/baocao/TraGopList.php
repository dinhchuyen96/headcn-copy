<?php

namespace App\Http\Livewire\Ketoan\Baocao;

use App\Http\Livewire\Base\BaseLive;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ListOutcomeExport;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Installment;
use App\Models\Customer;
use Livewire\Component;
use App\Exports\InstallmentExport;

class TraGopList extends BaseLive
{
    public $searchfromDate;
    public $searchtoDate;
    public $key_name = "customer_name";
    public $sortingName = "asc";
    public $customerPhone;
    public $contractNumber;
    public $status;
    public $listSelectCustomer;
    protected $listeners = ['setfromDate', 'settoDate'];

    public function mount()
    {
        $this->searchfromDate = reFormatDate(now(), 'Y-m-d');
        $this->searchtoDate = reFormatDate(now(), 'Y-m-d');
        $this->listSelectCustomer = Customer::whereNotNull('phone')->whereNotNull('name')->select('id', 'name', 'phone')->get();
    }
    public function render()
    {
        $data = $this->getQuerySearch()->paginate($this->perPage);
        $this->updateUI();
        return view('livewire.ketoan.baocao.tra-gop-list', compact('data'));
    }
    public function setfromDate($time)
    {
        $this->searchfromDate = date('Y-m-d', strtotime($time['fromDate']));
    }
    public function settoDate($time)
    {
        $this->searchtoDate = date('Y-m-d', strtotime($time['toDate']));
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
    public function export()
    {
        $dataExport =  $this->getQuerySearch()->get();
        if ($dataExport->isEmpty()) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Không có bản ghi nào!']);
            $this->emit('close-modal-export');
        } else {
            return Excel::download(new InstallmentExport($dataExport), 'dstragop_' . date('Y-m-d-His') . '.xlsx');
        }
    }
    public function getQuerySearch()
    {
        $query = Installment::join('orders', 'installment.order_id', 'orders.id')
            ->join('customers', 'orders.customer_id', 'customers.id')
            ->join('installment_company', 'installment.installment_company_id', 'installment_company.id');

        if ($this->customerPhone) {
            $query = $query->where('customers.phone',  $this->customerPhone);
        }
        if ($this->contractNumber) {
            $query =  $query->where('installment.contract_number', 'like', '%' . $this->contractNumber . '%');
        }
        if ($this->searchfromDate) {
            $query =  $query->where('installment.created_at', '>=', $this->searchfromDate);
        }
        if ($this->searchtoDate) {
            $query =  $query->where('installment.created_at', '<=', $this->searchtoDate);
        }
        if ($this->status) {
            $query =  $query->where('orders.status', $this->status);
        }
        $query = $query->select(DB::raw('installment.contract_number as contract_number
        ,customers.name as customer_name
        ,customers.phone as customer_phone
        ,installment.created_at as created_at
        ,installment.money as money
        ,installment_company.company_name as company_name
        ,installment.id as installment_id
        ,orders.status as orders_status
        '))->orderBy($this->key_name, $this->sortingName);
        return $query;
    }
}
