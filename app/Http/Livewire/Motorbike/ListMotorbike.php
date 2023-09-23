<?php

namespace App\Http\Livewire\Motorbike;

use App\Http\Livewire\Base\BaseLive;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Motorbike;
use App\Models\Warehouse;
use App\Models\Supplier;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MotorbikeListExport;
use App\Enum\EMotorbike;

class ListMotorbike extends BaseLive
{
    public $searchChassic;
    public $searchEngine;
    public $searchColor;
    public $searchModel;
    public $searchSupplier;
    public $searchStatus = 3;
    public $searchFromDate;
    public $searchToDate;

    public $seachWarehouse;

    public $dataExport;
    public $warehouseList;

    public $key_name = "created_at";
    public $sortingName = "desc";
    protected $listeners = ['setfromDate', 'settoDate'];
    public function setfromDate($time)
    {
        $this->searchFromDate = date('Y-m-d', strtotime($time['fromDate']));
    }
    public function settoDate($time)
    {
        $this->searchToDate = date('Y-m-d', strtotime($time['toDate']));
    }
    public function mount()
    {
        $this->searchFromDate = $this->searchToDate = date('Y-m-d');
        $this->dataExport = collect([]);
        $this->warehouseList = Warehouse::get()->pluck('name', 'id')->sortBy('name');
    }

    public function render()
    {
        $query = Motorbike::where('is_out', EMotorbike::NOT_OUT)->leftJoin('warehouse', 'warehouse.id', 'motorbikes.warehouse_id')
            ->leftJoin('suppliers', 'suppliers.id', 'motorbikes.supplier_id');
        if ($this->searchChassic)
            $query->where('motorbikes.chassic_no', 'like', '%' . trim($this->searchChassic) . '%');
        if ($this->searchEngine)
            $query->where('motorbikes.engine_no', 'like', '%' . trim($this->searchEngine) . '%');
        if ($this->searchColor)
            $query->where('motorbikes.color', 'like', '%' . trim($this->searchColor) . '%');
        if ($this->searchModel)
            $query->where('motorbikes.model_code', 'like', '%' . trim($this->searchModel) . '%');
        if ($this->searchSupplier)
            $query->where('motorbikes.supplier_id', $this->searchSupplier);
        if ($this->seachWarehouse)
            $query->where('motorbikes.warehouse_id', $this->seachWarehouse);

        if ($this->searchStatus == EMotorbike::SOLD)
            $query->where('motorbikes.status', EMotorbike::SOLD)->whereNotNull('motorbikes.customer_id');
        if ($this->searchStatus == EMotorbike::VITUAL)
            $query->where('motorbikes.status', EMotorbike::VITUAL)->whereNull('motorbikes.customer_id');
        if ($this->searchStatus == EMotorbike::PROCESS)
            $query->where('motorbikes.status', EMotorbike::PROCESS)->whereNull('motorbikes.customer_id');
        if ($this->searchStatus ==  EMotorbike::NEW_INPUT)
            $query->whereNull('motorbikes.customer_id')->where('motorbikes.status', EMotorbike::NEW_INPUT);

        if (!empty($this->searchFromDate)) {
            $query->whereDate('motorbikes.buy_date', '>=', $this->searchFromDate);
        }
        if (!empty($this->searchToDate)) {
            $query->whereDate('motorbikes.buy_date', '<=', $this->searchToDate . ' 23:59:59');
        }
        $query->select(
            'motorbikes.*',
            'suppliers.name as supply_name',
            'warehouse.name as warehouse_name'
        );
        $supplierList = Supplier::query()->pluck('name', 'id');
        $supplierList->prepend('--Chọn nhà cung cấp--', '0');
        $statusList = collect();
        $statusList->prepend('Đã xuất', EMotorbike::SOLD);
        $statusList->prepend('Chờ xử lý', EMotorbike::PROCESS);
        $statusList->prepend('Mới nhập', EMotorbike::NEW_INPUT);
        $statusList->prepend('Bán ảo', EMotorbike::VITUAL);
        $statusList->prepend('--Trạng thái--', 0);
        $this->dataExport = $query->orderBy($this->key_name, $this->sortingName)->get();
        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage)->setPath(route('motorbikes.list.index'));
        $this->updateUI();
        return view('livewire.motorbike.list-motorbike', compact('data', 'supplierList', 'statusList'));
    }

    public function delete()
    {
        $motorbike = Motorbike::findOrFail($this->deleteId);
        if ($motorbike->customer_id) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Không được xóa xe đã bán']);
        } else {
            $motorbike->orderDetail()->delete();
            $motorbike->delete();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa thành công']);
        }
    }


    public function export()
    {
        $this->updateUI();
        $listMotor = Motorbike::query()->where('is_out', EMotorbike::NOT_OUT)->get();
        if ($listMotor->count() == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Không có bản ghi nào!']);
            $this->emit('close-modal-export');
        } else {
            return Excel::download(new MotorbikeListExport($this->dataExport), 'dsxenhap_' . date('Y-m-d-His') . '.xlsx');
        }
    }

    public function resetSearch()
    {
        $this->searchChassic = "";
        $this->searchEngine = "";
        $this->searchColor = "";
        $this->searchModel = "";
        $this->searchSupplier = "";
        $this->searchFromDate = "";
        $this->searchToDate = "";
        $this->emit('resetDateKendo');
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
}
