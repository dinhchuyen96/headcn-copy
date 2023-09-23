<?php

namespace App\Http\Livewire\Sellbuyreturn;

use App\Http\Livewire\Base\BaseLive;
use App\Models\SellBuyReturn;

class SellCreate extends BaseLive
{
    public function render()
    {

        $data = SellBuyReturn::with(['motobike', 'accessory'])
            ->where('return_type', 0)
            ->orderBy('created_at', $this->sortingName)
            ->paginate($this->perPage);

        return view('livewire.return.sell-create', ['data' => $data]);
    }
}
