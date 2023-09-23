<?php

namespace App\Http\Livewire\Service\Other;

use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Base\BaseLive;

use App\Enum\EOrder;
use App\Enum\EOrderDetail;

use App\Models\Accessory;
use App\Models\Customer;
use App\Models\PositionInWarehouse;
use App\Models\ListService;
use App\Models\OtherService;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use DB;
use Carbon\Carbon;

class Show extends BaseLive
{
    public $orderId;

    public $service_content = [];
    public $service_price = [];
    public $service_promotion = [];
    public $service_total = [];
    public $list_service = [];
    public $services = [];
    public $i = 0;
    public $disabled = false;
    public $disabled_customer = true;
    public $customer = '';

    public $fixerId;
    public $users;

    public $accessory_code = [];
    public $accessory_warehouse_pos = [];
    public $accessory_name = [];
    public $accessory_supplier = [];
    public $accessory_quantity = [];
    public $accessory_available_quantity = [];
    public $accessory_available_quantity_root = [];
    public $accessory_price = [];
    public $accessory_promotion = [];
    public $accessory_total = [];
    public $accessory_price_vat = [];
    public $accessory_price_actual = [];
    public $accessory_product = [];
    public $accessories = [];
    public $j = 0;

    public $accessories_list = [];
    public $accessories_select = [];
    public $positions_list = [];
    public $positions_select = [];
    public $o_service_list = [];
    public $o_service_select = [];

    protected $listeners = ['countServicePrice', 'changeListService', 'changeAccessoryCode', 'changeWarehousePos', 'countAccessoryPrice'];

    public function mount()
    {
        $orderInfo = Order::where('id', $this->orderId)->first();
        if ($orderInfo) {
            $this->fixerId = $orderInfo->fixer;
            $this->customer = $orderInfo->customer_id;
        }
    }
    public function render()
    {
        $customers = Customer::get();
        $this->users = User::select(['id', 'name'])->get();
        if (empty($this->orderId)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Không tồn tại đơn hàng']);
            redirect()->route('xemay.dichvukhac.index');
        }
        $orderInfo = Order::where('id', $this->orderId)->with(['customer'])->first();
        if (empty($orderInfo)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Không tồn tại đơn hàng']);
            redirect()->route('xemay.dichvukhac.index');

        }

        $serviceList = OtherService::where('order_id', $this->orderId)->get();
        foreach ($serviceList as $key => $service) {
            $this->services[$key + 1] = $key + 1;
            $this->list_service[$key + 1] = $service->list_service_id ?? '';
            $this->service_content[$key + 1] = $service->content ?? '';
            $this->service_price[$key + 1] = $service->price ?? 0;
            $this->service_promotion[$key + 1] = $service->promotion ?? 0;
            $this->service_total[$key + 1] = $this->showServicePrice($service->price, $service->promotion);

            $this->o_service_list[$key + 1] = ListService::select('id', 'title')->get();
        }

        $accessoryList = OrderDetail::with(['accessorie', 'accessorie.supplier', 'order', 'positioninwarehouse'])
            ->where('order_id', $this->orderId)
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->get();

        foreach ($accessoryList as $key => $accessory) {
            $this->accessories[$key + 1] = $key + 1;
            $this->accessory_code[$key + 1] = $accessory->accessorie->code ?? '';
            $this->accessory_warehouse_pos[$key + 1] = $accessory->positioninwarehouse->id ?? '';
            $this->accessory_name[$key + 1] = $accessory->accessorie->name ?? '';
            $this->accessory_supplier[$key + 1] = $accessory->accessorie->supplier->code ?? '';
            $this->accessory_quantity[$key + 1] = $accessory->quantity ?? 0;
            $this->accessory_price[$key + 1] = $accessory->price ?? 0;
            $this->accessory_promotion[$key + 1] = $accessory->promotion ?? 0;
            $this->accessory_total[$key + 1] = $this->showAccessoryPrice($accessory->quantity, $accessory->price, $accessory->promotion);
            $this->accessory_price_vat[$key + 1] = $accessory->vat_price ?? 0;
            $this->accessory_price_actual[$key + 1] = $accessory->actual_price ?? 0;

            $this->accessory_product[$key + 1] = OrderDetail::where('id',  $accessory->id)
                ->pluck('product_id')
                ->first();

            $this->accessory_available_quantity[$key + 1] = $accessory->accessorie->quantity ?? 0;
            $this->accessory_available_quantity_root[$key + 1] = $accessory->accessorie->quantity ?? 0;

            $this->accessories_list[$key + 1] = Accessory::whereNotNull('code')
                ->whereNotIn('code', $this->accessories_select)
                ->where('quantity', '>', 0)
                ->pluck('code')
                ->unique();
            $this->accessories_select[] = $accessory->code;



            $positionIds = Accessory::where('code', $accessory->accessorie->code)
                ->where('quantity', '>', 0)
                ->select('position_in_warehouse_id')
                ->pluck('position_in_warehouse_id');

            $this->positions_list[$key + 1] = PositionInWarehouse::with(['warehouse'])
                ->whereHas('warehouse', function ($q) {
                    $q->whereNull('deleted_at');
                })
                ->whereIn('id', $positionIds)
                ->get()
                ->map(function ($item, $key) {
                    return [
                        'id' => $item->id,
                        'name' => $item->warehouse->name . " - " . $item->name
                    ];
                });
        }

        $this->dispatchBrowserEvent('setSelect2');

        return view('livewire.service.other.show', [
            'customers' => $customers
        ]);
    }

    public function showServicePrice($price, $promotion)
    {
        $total = 0;

        if (empty($price)) {
            $total = 0;
        }

        if (empty($promotion)) {
            $total = $price;
        }

        if (!empty($price) && !empty($promotion)) {
            $total = $price - (($price / 100) * $promotion);
        }

        return number_format($total);
    }

    public function showAccessoryPrice($quantity, $price, $promotion)
    {
        $total = 0;
        $price = $price * $quantity;

        if (empty($promotion)) {
            $total = $price;
        }

        if (!empty($price) && !empty($promotion)) {
            $total = $price - (($price / 100) * $promotion);
        }

        return number_format($total);
    }
}
