<?php

namespace App\Http\Livewire\Gift;

use Livewire\Component;
use App\Models\Gift;
use App\Models\GiftLog;
use App\Models\GiftWarehouse;

class GiftShow extends Component
{
    public $giftName;
    public $giftPoint;
    public $giftQuantity = 0;
    public $giftItem;
    public $isAdd = true;
    public $warehouseProvince;
    public $giftWarehouseList;

    public function mount()
    {
        $this->key_name = 'gift_name';
        $this->sortingName = 'asc';

        if ($this->giftItem) {
            $this->giftName = $this->giftItem->gift_name;
            $this->giftPoint = $this->giftItem->gift_point;
            $this->giftQuantity = $this->giftItem->quantity;
            $this->warehouseProvince = $this->giftItem->gift_warehouse_id;
            $this->isAdd = false;
        }
    }

    public function render()
    {
        $giftWarehouse = GiftWarehouse::with('province')->orderBy('name')->get();
        foreach ($giftWarehouse as $key => $warehouse) {
            $this->giftWarehouseList[] = [
                'gift_warehouse_id' => $warehouse->id,
                'gift_warehouse_name' => $warehouse->name . ' - ' . $warehouse->province->short_name,
            ];
        }

        $history = GiftLog::with(['gift', 'customer'])
            ->where('gift_point_id', $this->giftItem->id)
            ->orderBy('created_at', $this->sortingName)
            ->paginate(20);

        return view('livewire.gift.gift-show', compact('history'));
    }
}
