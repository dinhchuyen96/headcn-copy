<?php

namespace App\Http\Livewire\Utilities;

use App\Exports\WrongTimeExport;
use App\Http\Livewire\Base\BaseLive;
use App\Models\HMSReceivePlan;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class WrongTimeList extends BaseLive
{
    public $searchChassicNo;
    public $searchEngineNo;
    public $searchModel;
    public $searchColor;
    public $searchSupplier;
    public $fromDate;
    public $toDate;
    protected $listeners = ['setfromDate', 'settoDate'];

    public function render()
    {
        if ($this->reset) {
            $this->searchChassicNo = null;
            $this->searchEngineNo = null;
            $this->searchModel = null;
            $this->searchColor = null;
            $this->searchSupplier = null;
            $this->fromDate = null;
            $this->toDate = null;
            $this->emit('resetDateKendo');
        }
        $models = HMSReceivePlan::query()->pluck('model_type');
        $colors = HMSReceivePlan::query()->pluck('color');
        $this->searchChassicNo = trim($this->searchChassicNo);
        $this->searchEngineNo = trim($this->searchEngineNo);
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        $count_hmsRP = HMSReceivePlan::query()->where('eta', '>=', $get_first_day)->where('eta', '<=', $today)->where('arrival_date', '>', 'eta');
        if ($this->searchChassicNo) {
            $count_hmsRP->where('chassic_no', 'like', '%' . $this->searchChassicNo . '%');
        }
        if ($this->searchEngineNo) {
            $count_hmsRP->where('engine_no', 'like', '%' . $this->searchEngineNo . '%');
        }
        if ($this->searchModel) {
            $count_hmsRP->where('model_type', $this->searchModel);
        }
        if ($this->searchColor) {
            $count_hmsRP->where('color', $this->searchColor);
        }
        if ($this->fromDate) {
            $count_hmsRP->where('eta', '>=', $this->fromDate);
        }
        if ($this->toDate) {
            $count_hmsRP->where('eta', '<=', $this->toDate);
        }

        if ($this->fromDate) {
            $count_hmsRP->where('eta', '>=', $this->fromDate);
        }
        if ($this->toDate) {
            $count_hmsRP->where('eta', '<=', $this->toDate . ' 23:59:59');
        }
        $data = $count_hmsRP->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        return view('livewire.utilities.wrong-time-list', [
            'data' => $data,
            'models' => $models,
            'colors' => $colors
        ]);
    }
    public function export()
    {
        $this->listOrders = HMSReceivePlan::query()->get();
        $this->countRecords = $this->listOrders->count();
        if ($this->countRecords == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Không có bản ghi nào!']);
            $this->emit('close-modal-export');
        } else {
            return Excel::download(new WrongTimeExport, 'danhsachxenhaphangmuon_' . date('Y-m-d-His') . '.xlsx');
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
