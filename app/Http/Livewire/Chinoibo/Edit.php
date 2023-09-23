<?php

namespace App\Http\Livewire\Chinoibo;

use Livewire\Component;

use App\Enum\ListServiceType;

use App\Models\FeeOut;
use App\Models\ListService;
use App\Models\Order;
use App\Models\Supplier;
use App\Http\Livewire\Base\BaseLive;

class Edit extends BaseLive
{
    public $feeOutId;
    public $content;
    public $price;
    public $serviceType;
    public $supplier_id;

    public function mount()
    {
        $data  = $feeOut = FeeOut::where('id', $this->feeOutId)->with(['listService', 'order'])->first();
        $this->serviceType = $data->list_service_id;
        $this->supplier_id = $data->supplier_id;
    }
    public function render()
    {
        $data  = $feeOut = FeeOut::where('id', $this->feeOutId)->with(['listService', 'order'])->first();
        $suppliers = Supplier::query()->select('name', 'id')->get();
        $listService = ListService::select('id', 'title')->where('type', ListServiceType::OUT)->get();

        $this->content = $data->content;
        $this->price = $data->price;

        return view('livewire.chinoibo.edit', ['listService' => $listService, 'suppliers' => $suppliers, 'data' => $data]);
    }

    public function update()
    {
        $this->validate([
            'serviceType' => 'required',
            'supplier_id' => 'required',
            'price' => 'required|numeric|min:1|max:9999999999',
        ], [
            'serviceType.required' => 'Loại dịch vụ là bắt buộc',
            'supplier_id.required' => 'Nhà cung cấp là bắt buộc',
            'price.required' => 'Chi phí là bắt buộc',
            'price.numeric' => 'Chi phí phải là số',
            'price.min' => 'Chi phí tối thiểu là 1',
            'price.max' => 'Chi phí tối đa là 9999999999',
        ]);

        $feeOut  = $feeOut = FeeOut::where('id', $this->feeOutId)->with(['listService', 'order'])->first();
        $order = Order::where('id', $feeOut->order->id)->first();

        $order->supplier_id = $this->supplier_id;
        $order->total_money = $this->price;
        $order->save();

        $feeOut->list_service_id = $this->serviceType;
        $feeOut->content = $this->content;
        $feeOut->price = $this->price;
        $feeOut->save();

        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhập thành công']);
    }
}
