<?php

namespace App\Http\Livewire\Kho;

use App\Http\Livewire\Base\BaseLive;
use App\Models\Gift;
use App\Models\GiftWarehouse;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GiftWarehouseExport;

class KhoGiftList extends BaseLive
{
    public $searchStorageName;
    public $searchStorageAddress;
    public $StorageEstablished;
    public $StorageCreated;
    protected $listeners = ['setStorageEstablished','setStorageCreated'];
    public function mount()
    {
        $this->StorageEstablished = null;
        $this->StorageCreated = null;
    }

    public function render()
    {
        if ($this->reset) {
            $this->reset = null;
            $this->searchStorageName = null;
            $this->searchStorageAddress = null;
            $this->StorageEstablished = null;
            $this->StorageCreated = null;
            $this->key_name = 'id';
            $this->sortingName = 'desc';
        }
        $this->searchStorageName = trim($this->searchStorageName);
        $this->searchStorageAddress = trim($this->searchStorageAddress);
        $this->StorageEstablished = trim($this->StorageEstablished);
        $this->StorageCreated = trim($this->StorageCreated);

        $query = GiftWarehouse::leftJoin('ex_province', 'ex_province.province_code', '=', 'gift_warehouse.province_id')
            ->leftJoin('ex_district', 'ex_district.district_code', '=', 'gift_warehouse.district_id')
            ->select(
                'gift_warehouse.*',
                DB::raw('ex_province.name as province_name'),
                DB::raw('ex_district.name as district_name')
            );

        $query->where(function () use ($query) {
            if ($this->searchStorageName) {
                $query->where('gift_warehouse.name', 'like', '%' . $this->searchStorageName . '%');
            }
            if ($this->searchStorageAddress) {
                $query->orWhere('gift_warehouse.address', 'like', '%' . $this->searchStorageAddress . '%');
            }
            if ($this->StorageEstablished) {
                $query->orWhere('gift_warehouse.established_date', 'like', '%' . $this->StorageEstablished . '%');
            }
            if ($this->StorageCreated) {
                $query->orWhere('gift_warehouse.created_at', 'like', '%' . $this->StorageCreated . '%');
            }
        });

        if ($this->key_name) {
            $query->orderBy($this->key_name, $this->sortingName);
        }

        $warehouses = $query->paginate($this->perPage);

        return view('livewire.kho.kho-gift-list', ['data' => $warehouses]);
    }

    public function delete()
    {
        $gift = Gift::where('gift_warehouse_id', $this->deleteId)->count();

        if ($gift > 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Trong kho có quà tặng nên không thể xóa']);
            return;
        }

        $warehouse = GiftWarehouse::findOrFail($this->deleteId);
        $warehouse->delete();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa kho thành công']);
    }

    public function export()
    {
        $warehouses = GiftWarehouse::all();
        if ($warehouses->count() == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'warning', 'message' => 'Ko có bản ghi nào!']);
            $this->emit('close-modal-export');
        } else {
            return Excel::download(new GiftWarehouseExport, 'khoquatang_' . date('Y-m-d-His') . '.xlsx');
        }
    }
    public function setStorageEstablished($timeSE)
    {
        $this->StorageEstablished = date('Y-m-d', strtotime($timeSE['StorageEstablished']));
    }
    public function setStorageCreated($timeSC)
    {
        $this->StorageCreated = date('Y-m-d', strtotime($timeSC['StorageCreated']));
    }
}
