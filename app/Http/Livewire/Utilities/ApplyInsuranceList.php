<?php

namespace App\Http\Livewire\Utilities;

use App\Exports\ApplyInsuranceExport;
use App\Http\Livewire\Base\BaseLive;
use App\Models\HMSServiceResults;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ApplyInsuranceList extends BaseLive
{
    public $fromDate;
    public $toDate;
    public $searchCode;
    protected $listeners = ['setfromDate', 'settoDate'];

    public function render()
    {
        $this->searchCode = trim($this->searchCode);
        if ($this->reset) {
            $this->fromDate = null;
            $this->toDate = null;
            $this->searchCode = null;
            $this->emit('resetDateKendo');
        }
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        $applyInsurance = HMSServiceResults::query()
            ->where('sr_created_date_time', '>=', $get_first_day)
            ->where('sr_closed_date_time', '<=', $today)
            ->whereNotNull('sr_closed_date_time')
            ->where('reason_for_cancellation', '!=', '')
            ->where('sr_created_date_time', '>=', $get_first_day)
            ->where('sr_created_date_time', '<=', $today);

        if ($this->searchCode) {
            $applyInsurance->where('sr', 'LIKE', '%' . $this->searchCode . '%');
        }
        if ($this->fromDate) {
            $applyInsurance->whereDate('sr_created_date_time', '>=', $this->fromDate);
        }
        if ($this->toDate) {
            $applyInsurance->whereDate('sr_closed_date_time', '<=', $this->toDate);
        }
        if ($this->fromDate) {
            $applyInsurance->whereDate('sr_created_date_time', '>=', $this->fromDate);
        }
        if ($this->toDate) {
            $applyInsurance->whereDate('sr_closed_date_time', '<=', $this->toDate . ' 23:59:59');
        }

        $data = $applyInsurance->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);

        return view('livewire.utilities.apply-insurance-list', ['data' => $data]);
    }

    public function export()
    {
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        $this->listOrders = HMSServiceResults::all();
        $this->countRecords = $this->listOrders->count();
        if ($this->countRecords == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Không có bản ghi nào!']);
            $this->emit('close-modal-export');
        } else {
            return Excel::download(new ApplyInsuranceExport, 'danhsachchapthuanbaohanh' . date('Y-m-d-His') . '.xlsx');
        }
    }
    public function setfromDate($time)
    {
        $this->fromDate = date('Y-m-d', strtotime($time['fromDate']));
    }
    public function settoDate($time)
    {
        $this->toDate = date('Y-m-d', strtotime($time['toDate']));
    }
}
