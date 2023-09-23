<?php

namespace App\Http\Livewire\Kho;

use App\Models\District;
use App\Models\Province;
use App\Models\Warehouse;
use App\Models\GiftWarehouse;
use App\Models\PositionInWarehouse;
use App\Models\GiftPositionInWarehouse;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $StorageName, $StorageEstablished, $Address, $warehouseProvince, $warehouseDistrict;
    public $position_name = [];
    public $positions = [];
    public $i = 1;
    public $warehouseType = 1; //1: global; 2: gift

    protected $listeners = ['setStorageEstablished'];

    public function render()
    {
        if (isset($_GET['type']) && $_GET['type'] == 'gift') {
            $this->warehouseType = 2;
        }

        $district = [];
        $province = Province::orderBy('name')->pluck('name', 'province_code');
        if ($this->warehouseProvince) {
            $district = District::where('province_code', $this->warehouseProvince)->orderBy('name')->pluck('name', 'district_code');
        }

        $this->StorageEstablished = date('Y-m-d');

        $this->dispatchBrowserEvent('setSelect2');
        $this->dispatchBrowserEvent('setStorageEstablishedPicker');
        return view('livewire.kho.create', compact('province', 'district'));
    }

    public function store()
    {
        $this->validate(
            [
                'StorageName' => 'required|unique:warehouse,name,NULL,id,deleted_at,NULL',
                'Address' => 'required',
                'StorageEstablished' => 'required',
                'warehouseProvince' => 'required',
                'warehouseDistrict' => 'required',
            ],
            [
                'StorageName.required' => 'Bắt buộc nhập tên kho',
                'StorageName.unique' => 'Tên kho đã tồn tại',
                'Address.required' => 'Bắt buộc nhập địa chỉ',
                'StorageEstablished.required' => 'Bắt buộc nhập ngày thành lập',
                'warehouseProvince.required' => 'Bắt buộc nhập Thành phố/Tỉnh',
                'warehouseDistrict.required' => 'Bắt buộc nhập Quận/Huyện',
            ]
        );

        if ($this->warehouseType == 1) {
            $warehouse = new Warehouse();
        }
        if ($this->warehouseType == 2) {
            $warehouse = new GiftWarehouse();
        }

        $warehouse->name = $this->StorageName;
        $warehouse->address = $this->Address;
        $warehouse->established_date = $this->StorageEstablished;
        $warehouse->province_id = $this->warehouseProvince;
        $warehouse->district_id = $this->warehouseDistrict;

        DB::beginTransaction();
        if ($warehouse->save()) {
            $dataPos = [];

            foreach ($this->position_name as $item) {
                if ($this->warehouseType == 1) {
                    $dataPos[] = [
                        'warehouse_id' => $warehouse->id,
                        'name' => $item,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }
                if ($this->warehouseType == 2) {
                    $dataPos[] = [
                        'gift_warehouse_id' => $warehouse->id,
                        'name' => $item,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }
            }

            if ($this->warehouseType == 1) {
                if (!PositionInWarehouse::insert($dataPos)) {
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Thêm mới không thành công']);
                    return;
                }
            }
            if ($this->warehouseType == 2) {
                if (!GiftPositionInWarehouse::insert($dataPos)) {
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Thêm mới không thành công']);
                    return;
                }
            }

            DB::commit();
        }

        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Thêm mới thành công']);
        $this->resetInput();
    }

    public function setStorageEstablished($time)
    {
        $this->StorageEstablished = date('Y-m-d', strtotime($time['StorageEstablished']));
    }

    public function resetInput()
    {
        $this->StorageName = '';
        $this->Address = '';
        $this->StorageEstablished = '';
        $this->warehouseProvince = '';
        $this->warehouseDistrict = '';
        $this->position_name = [];
        $this->positions = [];
        $this->i = 1;
    }

    public function addPos($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->positions, $i);
    }

    public function removePos($i)
    {
        unset($this->positions[$i]);
    }
}
