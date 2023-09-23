<?php

namespace App\Http\Livewire\Kho;

use App\Models\District;
use App\Models\Province;
use App\Models\Warehouse;
use App\Models\GiftWarehouse;
use Livewire\Component;

class Edit extends Component
{
    public $warehouse_id;
    public $supplyWard, $warehouseProvince, $warehouseDistrict;
    public $StorageName, $StorageEstablished, $Address;
    public $status = false;
    public $addBtn = true;
    public $warehouseType = 1;
    protected $listeners = [
        'setBtnAddStatus',
        'setStorageEstablished'
    ];

    public function mount()
    {
        if (isset($_GET['type']) && $_GET['type'] == 'gift') {
            $this->warehouseType = 2;
            $warehouse = GiftWarehouse::findOrFail($this->warehouse_id);
        } else {
            $warehouse = Warehouse::findOrFail($this->warehouse_id);
        }

        $this->StorageName = $warehouse->name;
        $this->Address = $warehouse->address;
        $this->StorageEstablished = $warehouse->established_date;
        $this->warehouseProvince = $warehouse->province_id;
        $this->warehouseDistrict = $warehouse->district_id;
    }

    public function render()
    {
        $warehouse_id = $this->warehouse_id;
        $district = [];
        $province = Province::orderBy('name')->pluck('name', 'province_code');
        if ($this->warehouseProvince) {
            $district = District::where('province_code', $this->warehouseProvince)->orderBy('name')->pluck('name', 'district_code');
        }

        $this->dispatchBrowserEvent('setSelect2');
        $this->dispatchBrowserEvent('setStorageEstablishedPicker');
        return view('livewire.kho.edit', compact('district', 'province', 'warehouse_id'));
    }

    public function update()
    {
        $this->validate(
            [
                'StorageName' => 'required|unique:warehouse,name,' . $this->warehouse_id . ',id,deleted_at,NULL',
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
            $warehouse = Warehouse::findOrFail($this->warehouse_id);
        }
        if ($this->warehouseType == 2) {
            $warehouse = GiftWarehouse::findOrFail($this->warehouse_id);
        }
        $warehouse->name = $this->StorageName;
        $warehouse->address = $this->Address;
        $warehouse->established_date = $this->StorageEstablished;
        $warehouse->province_id = $this->warehouseProvince;
        $warehouse->district_id = $this->warehouseDistrict;
        $warehouse->save();

        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhật thành công']);
    }

    public function setStorageEstablished($time)
    {
        $this->StorageEstablished = date('Y-m-d', strtotime($time['StorageEstablished']));
    }

    public function add()
    {
        $this->addBtn = false;
        $this->emit('addNew', $this->warehouseType);
    }
    public function setBtnAddStatus()
    {
        $this->addBtn = true;
    }
}
