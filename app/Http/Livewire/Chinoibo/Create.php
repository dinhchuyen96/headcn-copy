<?php

namespace App\Http\Livewire\Chinoibo;

use App\Http\Livewire\Base\BaseLive;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use App\Enum\EOrder;
use App\Enum\ListServiceType;

use App\Models\FeeOut;
use App\Models\ListService;
use App\Models\Order;
use App\Models\Supplier;


class Create extends BaseLive
{
    public $content;
    public $price;
    public $serviceType;
    public $supplier_id;

    public function render()
    {
        $suppliers = Supplier::query()->select('name', 'id')->get();
        $listService = ListService::select('id', 'title')->where('type', ListServiceType::OUT)->get();
        return view('livewire.chinoibo.create', ['listService' => $listService, 'suppliers' => $suppliers]);
    }

    public function store()
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

        $order = new Order;
        $feeOut = new FeeOut;

        $order->category = EOrder::SERVICE_OTHER;
        $order->created_by = Auth::user()->id;
        $order->total_items = 1;
        $order->status = 2;
        $order->type = EOrder::TYPE_NHAP;
        $order->order_type = EOrder::ORDER_TYPE_BUY;
        $order->supplier_id = $this->supplier_id;
        $order->total_money = $this->price;
        $order->save();

        $feeOut->order_id = $order->id;
        $feeOut->list_service_id = $this->serviceType;
        $feeOut->content = $this->content;
        $feeOut->price = $this->price;
        $feeOut->save();

        $this->resetInput();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Tạo mới thành công']);
    }

    public function resetInput()
    {
        $this->content = '';
        $this->price = '';
        $this->serviceType = '';
        $this->supplier_id = '';
    }
}
