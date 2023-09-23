<?php

namespace App\Http\Livewire\Gift;

use App\Http\Livewire\Base\BaseLive;
use Livewire\Component;
use App\Models\Gift;
use App\Models\GiftWarehouse;
use App\Models\GiftPositionInWarehouse;

class GiftCreate extends Component
{
    public $giftName; // Tên quà tặng
    public $giftPoint; // Điểm quà tặng
    public $giftQuantity = 0;
    public $giftItem;
    public $isAdd = true;
    public $warehouseProvince;
    public $giftWarehouseList = [];

    public function mount()
    {
        if ($this->giftItem) {
            $this->giftName = $this->giftItem->gift_name;
            $this->giftPoint = $this->giftItem->gift_point;
            $this->giftQuantity = $this->giftItem->quantity;
            $this->isAdd = false;
        }
    }

    public function render()
    {
        $giftWarehouse = GiftWarehouse::with('province', 'district')->orderBy('name')->get();
        foreach ($giftWarehouse as $key => $warehouse) {
            $this->giftWarehouseList[] = [
                'gift_warehouse_id' => $warehouse->id,
                'gift_warehouse_name' => $warehouse->name . ', ' . $warehouse->district->short_name . ', ' . $warehouse->province->short_name,
            ];
        }

        $this->dispatchBrowserEvent('setSelect2');

        return view('livewire.gift.gift-create');
    }
    public function store()
    {
        $this->validate([
            'giftName' => 'required|max:255',
            'giftPoint' => 'required|numeric|min:1|max:9999999999',
            'giftQuantity' => 'numeric|min:0|max:9999999999',
            'warehouseProvince' => 'required',
        ], [
            'giftName.required' => 'Tên quà tặng là bắt buộc',
            'giftName.max' => 'Tên quà tặng tối đa là 255 kí tự',
            'giftPoint.required' => 'Điểm quà tặng là bắt buộc',
            'giftPoint.numeric' => 'Điểm quà tặng phải là số',
            'giftPoint.min' => 'Điểm quà tặng tối thiểu là 1',
            'giftPoint.max' => 'Điểm quà tặng tối đa là 9999999999',
            'giftQuantity.numeric' => 'Số lượng quà tặng phải là số',
            'giftQuantity.min' => 'Số lượng quà tặng tối thiểu là 0',
            'giftQuantity.max' => 'Số lượng quà tặng tối đa là 9999999999',
            'warehouseProvince.required' => 'Kho quà tặng là bắt buộc',
        ]);
        if (!$this->giftItem) {
            $this->giftItem = new Gift();
        }
        $this->giftItem->gift_name = $this->giftName;
        $this->giftItem->gift_point = $this->giftPoint;
        $this->giftItem->quantity = $this->giftQuantity;
        $this->giftItem->gift_warehouse_id = $this->warehouseProvince;
        $this->giftItem->save();
        $this->resetInput();
        if ($this->isAdd) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Tạo mới thành công']);
        } else {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhật thành công']);
        }
    }
    public function resetInput()
    {
        $this->giftName = '';
        $this->giftPoint = '';
        $this->giftQuantity = '';
        $this->warehouseProvince = '';
    }
}
