<?php

namespace App\Http\Livewire\Sellbuyreturn;

use App\Http\Livewire\Base\BaseLive;
use App\Models\SellBuyReturn;

class SellIndex extends BaseLive
{
    public function mount()
    {
        $this->key_name = 'id';
        $this->sortingName = 'asc';
    }

    public function render()
    {

        $data = SellBuyReturn::with(['motobike', 'accessory'])
            ->where('return_type', 0)
            ->orderBy('created_at', $this->sortingName)
            ->paginate($this->perPage);

        return view('livewire.return.sell-list', ['data' => $data]);
    }
}
