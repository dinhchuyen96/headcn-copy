<?php

namespace App\Http\Livewire\Service;

use App\Http\Livewire\Base\BaseLive;
use Illuminate\Support\Facades\DB;
use App\Models\Motorbike;
use App\Models\Warehouse;
use App\Models\Supplier;
use App\Models\Customer;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MotorbikeListExport;
use App\Enum\EMotorbike;
// 4. danh sách xe ngoài ( 1 . họ tên , 2. sđt , 3. địa chỉ ) ( vd :motobike -> customer -> get )
class ListOutMotorbike extends BaseLive
{
    public $searchChassic;
    public $searchEngine;
    public $searchColor;
    public $searchModel;
    public $searchSupplier;
    public $searchStatus;
    public $searchFromDate;
    public $searchToDate;
    public $searchByName;
    public $searchByNumber;
    public $searchByAddress;

    public $seachWarehouse;

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
        $this->warehouseList = Warehouse::get()->pluck('name', 'id')->sortBy('name');
    }

    public function render()
    {
        // $query = Motorbike::where('is_out', EMotorbike::OUT);
        $query = DB::table('motorbikes')
            ->leftJoin('customers', 'customers.id', '=', 'motorbikes.customer_id')->where('is_out', EMotorbike::OUT)->select('motorbikes.*','name','phone');
        if ($this->searchChassic)
            $query->where('motorbikes.chassic_no', 'like', '%' . trim($this->searchChassic) . '%');
        if ($this->searchEngine)
            $query->where('motorbikes.engine_no', 'like', '%' . trim($this->searchEngine) . '%');
        if ($this->searchColor)
            $query->where('motorbikes.color', 'like', '%' . trim($this->searchColor) . '%');
        if ($this->searchModel)
            $query->where('motorbikes.model_code', 'like', '%' . trim($this->searchModel) . '%');
        if ($this->searchByName)
            $query->where('customers.name', 'like', '%' . trim($this->searchByName) . '%');
        if ($this->searchByNumber)
            $query->where('customers.phone', 'like', '%' . trim($this->searchByNumber) . '%');
        $supplierList = Supplier::query()->pluck('name', 'id');
        $supplierList->prepend('--Chọn nhà cung cấp--', '0');
        $statusList = collect();
        $statusList->prepend('Đã xuất', 3);
        $statusList->prepend('Chờ xử lý', 2);
        $statusList->prepend('Mới nhập', 1);
        $statusList->prepend('--Trạng thái--', 0);
        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage)->setPath(route('dichvu.dsxengoai.index'));
        $this->updateUI();
        return view('livewire.service.list-out-motorbike', compact('data', 'supplierList', 'statusList'));
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
