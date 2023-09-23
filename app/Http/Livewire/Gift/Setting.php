<?php

namespace App\Http\Livewire\Gift;

use App\Http\Livewire\Base\BaseLive;
use Livewire\Component;
use App\Models\GiftSetting;


class Setting extends Component
{
    public $giftTranfer;
    public $giftTranferItem;
    public function mount()
    {
        if (GiftSetting::count() == 0) {
            $this->giftTranferItem = new GiftSetting();
            $this->giftTranferItem->gift_tranfer = 20000;
            $this->giftTranferItem->save();
        } else {
            $this->giftTranferItem = GiftSetting::first();
        }

        $this->giftTranfer = $this->giftTranferItem->gift_tranfer;
    }

    public function render()
    {

        return view('livewire.gift.gift-setting');
    }
    public function store()
    {
        $this->validate([
            'giftTranfer' => 'required|numeric|min:1|max:9999999999',

        ], [
            'giftTranfer.required' => 'Số tiền tương ứng 1 point là bắt buộc',
            'giftTranfer.numeric' => 'Số tiền tương ứng 1 point phải là số',
            'giftTranfer.min' => 'Số tiền tương ứng 1 point tối thiểu là 1',
            'giftTranfer.max' => 'Số tiền tương ứng 1 point tối đa là 9999999999',
        ]);

        $this->giftTranferItem->gift_tranfer = $this->giftTranfer;
        $this->giftTranferItem->save();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhật thành công']);
    }
}
