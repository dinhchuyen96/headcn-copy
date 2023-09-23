<?php

namespace App\Http\Livewire\Chinoibo;

use App\Http\Livewire\Base\BaseLive;

use App\Models\FeeOut;
use App\Models\Order;

class Index extends BaseLive
{
    public function mount()
    {
        $this->key_name = 'id';
        $this->sortingName = 'asc';
    }
    public function render()
    {
        $query = FeeOut::query();

        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        return view('livewire.chinoibo.index', ['data' => $data]);
    }

    public function delete()
    {
        $feeOut = FeeOut::findOrFail($this->deleteId);
        $order = Order::where('id', $feeOut->id);
        
        $order->delete();
        $feeOut->delete();

        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa thành công']);
    }
}
