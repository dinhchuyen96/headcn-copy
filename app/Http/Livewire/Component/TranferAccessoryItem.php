<?php

namespace App\Http\Livewire\Component;

use Livewire\Component;

class TranferAccessoryItem extends Component
{

    public $index;
    public $accessory_code;
    public $name;
    public $position;
    public $amount_in_warehouse;
    public $quatity_tranfer;
    public $remain;

    protected $listeners = ['quatityTranferChange', 'updateItem'];

    public function mount($accessoryItem, $index)
    {
        $this->index = $index;
        $this->accessory_code = $accessoryItem['accessory_code'];
        $this->name = $accessoryItem['name'];
        $this->position = $accessoryItem['position'];
        $this->quatity_tranfer = (int)$accessoryItem['quatity_tranfer'];
        $this->amount_in_warehouse = (int)$accessoryItem['amount_in_warehouse'];
        $this->remain = (int)$accessoryItem['remain'];
    }

    public function render()
    {
        return view('livewire.component.phutung.tranfer-accessory-item');
    }
    public function quatityTranferChange()
    {
        $this->emitUp('updateToParent', [
            'quatity_tranfer' => $this->quatity_tranfer,
            'index' => $this->index
        ]);
        $this->validate([
            'quatity_tranfer' => 'required|numeric|min:1|max:' . $this->amount_in_warehouse
        ], [
            'quatity_tranfer.required' => 'Hãy nhập số lượng',
            'quatity_tranfer.numeric' => 'Sô lượng phải là số',
            'quatity_tranfer.min' => 'Số lượng tối thiểu là 1',
            'quatity_tranfer.max' => 'Số lượng vượt quá số lượng trong kho (' . $this->amount_in_warehouse . ')',
        ]);
        $this->remain = $this->amount_in_warehouse - $this->quatity_tranfer;

    }
    public function remove($index)
    {
        $this->emitUp('removeFromChild', $index);
    }
    public function updateItem($tranferParameter)
    {
        if ($this->index == $tranferParameter['index']) {
            $this->quatity_tranfer += (int)$tranferParameter['quatity_tranfer'];
            $this->remain = $this->amount_in_warehouse - $this->quatity_tranfer;
        }
    }
}
