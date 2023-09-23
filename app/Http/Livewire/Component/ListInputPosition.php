<?php

namespace App\Http\Livewire\Component;

use App\Http\Livewire\Base\BaseLive;
use App\Models\PositionInWarehouse;
use App\Models\GiftPositionInWarehouse;

class ListInputPosition extends BaseLive
{
    public $addStatus = false;
    public $warehouse_id;
    public $PositionName;
    public $itemEditID;
    public $isHVN = false;
    public $status = false;
    public $warehouseType = 1;

    protected $listeners = [
        'addNew',
    ];

    public function mount()
    {
    }

    public function render()
    {
        if (isset($_GET['type']) && $_GET['type'] == 'gift') {
            $data = GiftPositionInWarehouse::where('gift_warehouse_id', $this->warehouse_id)->get();
        } else {
            $data = PositionInWarehouse::where('warehouse_id', $this->warehouse_id)->get();
        }

        return view('livewire.component.list-input-position', compact('data'));
    }

    public function addNew($type)
    {
        $this->warehouseType = $type;
        $this->PositionName = null;
        $this->resetData();
        $this->resetValidation();
        $this->addStatus = true;
    }

    public function addItem()
    {
        $this->validate(
            [
                'PositionName' => 'required',
            ],
            [
                'PositionName.required' => 'Bắt buộc nhập tên vị trí',
            ]
        );

        if ($this->warehouseType == 1) {
            $position = new PositionInWarehouse();
            $position->warehouse_id = $this->warehouse_id;
        }
        if ($this->warehouseType == 2) {
            $position = new GiftPositionInWarehouse();
            $position->gift_warehouse_id = $this->warehouse_id;
        }

        $position->name = $this->PositionName;

        $position->save();

        $this->autoFill = false;
        $this->addStatus = false;
        $this->resetData();
        $this->emit('setBtnAddStatus');
    }

    public function editItem($id)
    {
        $this->resetValidation();
        $this->itemEditID = $id;

        if ($this->warehouseType == 1) {
            $position = PositionInWarehouse::findOrFail($id);
        }
        if ($this->warehouseType == 2) {
            $position = GiftPositionInWarehouse::findOrFail($id);
        }

        $this->PositionName = $position->name;
    }

    public function updateItem($id)
    {
        $this->validate(
            [
                'PositionName' => 'required',
            ],
            [
                'PositionName.required' => 'Bắt buộc nhập tên vị trí',
            ]
        );

        if (isset($_GET['type']) && $_GET['type'] == 'gift') {
            $position = GiftPositionInWarehouse::findOrFail($id);
        } else {
            $position = PositionInWarehouse::findOrFail($id);
        }

        $position->name = $this->PositionName;

        $position->save();
        $this->itemEditID = '';
    }

    public function cancel()
    {
        $this->itemEditID = '';
    }

    public function cancelAdd()
    {
        $this->autoFill = false;
        $this->addStatus = false;
        $this->resetData();
        $this->emit('setBtnAddStatus');
    }

    public function delete($id)
    {
        $this->itemEditID = '';
        if (isset($_GET['type']) && $_GET['type'] == 'gift') {
            $position = GiftPositionInWarehouse::findOrFail($id);
        } else {
            $position = PositionInWarehouse::findOrFail($id);
        }
        $position->delete();
    }

    public function resetDataEdit()
    {
        $this->PositionName = null;
    }

    public function resetData()
    {
        $this->PositionName = null;
    }
}
