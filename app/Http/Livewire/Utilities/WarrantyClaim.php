<?php

namespace App\Http\Livewire\Utilities;

use App\Exports\WarrantyClaimExport;
use App\Http\Controllers\Api\SmsGatewayController;
use App\Http\Livewire\Base\BaseLive;
use App\Models\HMSReceivePlan;
use App\Models\HMSServiceResults;
use Illuminate\Http\Request;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class WarrantyClaim extends BaseLive
{
    public $searchCode;
    public $fromDate;
    public $toDate;
    protected $listeners = ['setfromDate', 'settoDate'];

    public function render()
    {
        if ($this->reset) {
            $this->searchCode = null;
            $this->fromDate = null;
            $this->toDate = null;
            $this->emit('resetDateKendo');
        }
        $countWarrantyClaim = HMSServiceResults::query()->whereRaw('DATEDIFF(sr_closed_date_time,sr_created_date_time) > 5');
        if ($this->searchCode) {
            $countWarrantyClaim->where('sr', 'LIKE', '%' . $this->searchCode . '%');
        }
        if ($this->fromDate) {
            $countWarrantyClaim->where('sr_created_date_time', '>=', $this->fromDate);
        }
        if ($this->toDate) {
            $countWarrantyClaim->where('sr_closed_date_time', '<=', $this->toDate);
        }
        if ($this->fromDate) {
            $countWarrantyClaim->where('sr_created_date_time', '>=', $this->fromDate);
        }
        if ($this->toDate) {
            $countWarrantyClaim->where('sr_closed_date_time', '<=', $this->toDate . ' 23:59:59');
        }
        $data = $countWarrantyClaim->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        return view('livewire.utilities.warranty-claim', ['data' => $data]);
    }
    public function export()
    {
        $this->listOrders = HMSServiceResults::query()->get();
        $this->countRecords = $this->listOrders->count();
        if ($this->countRecords == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Không có bản ghi nào!']);
            $this->emit('close-modal-export');
        } else {
            return Excel::download(new WarrantyClaimExport, 'danhsachkhieunaibaohanh' . date('Y-m-d-His') . '.xlsx');
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
