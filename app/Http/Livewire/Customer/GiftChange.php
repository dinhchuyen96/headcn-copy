<?php

namespace App\Http\Livewire\Customer;

use App\Http\Livewire\Base\BaseLive;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\Gift;
use App\Models\GiftLog;

class GiftChange extends BaseLive
{
    public $customer;
    public $checkGift = [];
    public function mount()
    {
        $this->key_name = 'gift_name';
        $this->sortingName = 'asc';
    }
    public function render()
    {
        $data = Gift::orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);

        $history = GiftLog::with(['gift'])
            ->where('customer_id', $this->customer->id)
            ->orderBy('created_at', $this->sortingName)
            ->paginate($this->perPage);

        return view('livewire.customer.gift-change', compact('data', 'history'));
    }
    public function store()
    {
        if ($this->customer->point <= 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => "Khách hàng không có điểm để đổi quà"]);
            return;
        }
        if (count($this->checkGift) == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => "Hãy check vào quà tặng cần đổi"]);
            return;
        }

        $giftList = Gift::whereIn('id', $this->checkGift)->get();

        $giftListName = [];
        $giftData = [];
        $giftLog = [];

        foreach ($giftList as $gift) {
            if ($gift->quantity < 1) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => "Không còn đủ quà tặng để đổi"]);
                return;
            }

            $giftListName[] = $gift->gift_name;
            $giftData[] = [
                'id' => $gift->id,
                'quantity' => $gift->quantity - 1,
                'updated_at' => Carbon::now()
            ];
            $giftLog[] = [
                'gift_point_id' => $gift->id,
                'customer_id' => $this->customer->id,
                'action' => '-',
                'quantity' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        $totalPoint = Gift::whereIn('id', $this->checkGift)->sum('gift_point');
        if ($this->customer->point < $totalPoint) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => "Số điểm point không đủ để đổi"]);
            return;
        }

        $this->customer->point -= $totalPoint;
        $this->customer->save();

        $gift = new Gift;
        $updated = \Batch::update($gift, $giftData, 'id');
        if (!$updated) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Đổi điểm không thành công']);
            return;
        }
        if (!GiftLog::insert($giftLog)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Thêm mới không thành công']);
            return;
        }

        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => "Đã quy đổi thành công thành (" . implode(",", $giftListName) . ")"]);
        $this->customer = Customer::findOrFail($this->customer->id);
    }
}
