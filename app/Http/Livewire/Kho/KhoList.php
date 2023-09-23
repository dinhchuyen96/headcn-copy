<?php

namespace App\Http\Livewire\Kho;

use App\Http\Livewire\Base\BaseLive;
use App\Models\Warehouse;
use App\Models\Motorbike;
use App\Models\Accessory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KhoListImport;
use App\Exports\WarehouseExport;
use App\Enum\EMotorbike;
use App\Models\GiftWarehouse;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class KhoList extends BaseLive
{
    public $keyword;
    public $showAdvancesearch =0 ;
    public $file;
    public $searchStorageName;
    public $searchStorageAddress;
    public $StorageEstablished;
    public $StorageCreated;
    public $tableWarehouse;
    protected $listeners = ['setStorageEstablished','setStorageCreated'];
    public function mount()
    {
        $this->StorageEstablished = null;
        $this->StorageCreated = null;
        $this->tableWarehouse = null;
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


        $query = Warehouse::leftJoin('ex_province', 'ex_province.province_code', '=', 'warehouse.province_id')
            ->leftJoin('ex_district', 'ex_district.district_code', '=', 'warehouse.district_id')
            ->select(
                'warehouse.*',
                DB::raw('ex_province.name as province_name'),
                DB::raw('ex_district.name as district_name')
            );
        $queryGiftWarehouse = GiftWarehouse::leftJoin('ex_province', 'ex_province.province_code', '=', 'gift_warehouse.province_id')
        ->leftJoin('ex_district', 'ex_district.district_code', '=', 'gift_warehouse.district_id')
        ->select(
            'gift_warehouse.*',
            DB::raw('ex_province.name as province_name'),
            DB::raw('ex_district.name as district_name')
        );

        //Build query search for warehouse and gift_warehouse table;
        $warehouse = $this->buildSearch($query, 'warehouse');
        $giftWarehouse = $this->buildSearch($queryGiftWarehouse, 'gift_warehouse');

        $mergeWarehouse = $warehouse->union($giftWarehouse);

        if ($this->key_name) {
            $mergeWarehouse->orderBy($this->key_name, $this->sortingName);
        }
    
     
        $warehouses = $mergeWarehouse->paginate($this->perPage);

        return view('livewire.kho.kho-list', [
            'data' => $warehouses,
        ]);
    }

    public function delete()
    {
        $accessories = Accessory::where('warehouse_id', $this->deleteId)->count();
        $motorbikes = Motorbike::where('warehouse_id', $this->deleteId)->where('is_out', EMotorbike::NOT_OUT)->count();

        if ($accessories > 0 || $motorbikes > 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Trong kho có xe hoặc phụ tùng nên không thể xóa']);
            return;
        }

        $warehouse = Warehouse::findOrFail($this->deleteId);
        $warehouse->delete();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa kho thành công']);
    }

    public function export()
    {
        $warehouses = Warehouse::all();
        if ($warehouses->count() == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'warning', 'message' => 'Ko có bản ghi nào!']);
            $this->emit('close-modal-export');
        } else {
            return Excel::download(new WarehouseExport, 'kho_' . date('Y-m-d-His') . '.xlsx');
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


    /**
     * Do import warehouse list
     * Import warehouse and position
     */
    public function import()
    {

        $this->validate([
            'file' => 'required'
        ], [
            'file.required' => 'Hãy chọn file để import',
        ]);
        try {
            DB::beginTransaction();
            Excel::import(new KhoListImport, $this->file);
            if (session()->has('error')) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => session()->get('error')]);
                session()->pull('error');
                return;
            }
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Import thành công']);
            $this->emit('close-modal-import');
            DB::commit();
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $ar = [];
            foreach ($failures as $failure) {
                $ar[] = $failure->errors()[0];
            }
            $ar = array_unique($ar);
            foreach ($ar as $item) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $item]);
            }
            return;
        }
    }

    public function downloadExample()
    {
        return Storage::disk('public')->download('mau_file_danh_muc_kho_vitri.xlsx');
    }

    private function buildSearch(EloquentBuilder $queryBuilder, string $modelName) {

        $this->tableWarehouse = $modelName;

        $queryMaster = $queryBuilder->where(function ($query) {
            if ($this->searchStorageName) {
                $query->where("$this->tableWarehouse.name", 'like', '%' . $this->searchStorageName . '%');
            }
            if ($this->searchStorageAddress) {
                $query->orWhere("$this->tableWarehouse.address", 'like', '%' . $this->searchStorageAddress . '%');
            }
            if ($this->StorageEstablished) {
                $query->orWhere("$this->tableWarehouse.established_date", 'like', '%' . $this->StorageEstablished . '%');
            }
            if ($this->StorageCreated) {
                $query->orWhere("$this->tableWarehouse.created_at", 'like', '%' . $this->StorageCreated . '%');
            }
        });
        return $queryMaster;
    }
}
