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
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Edit extends BaseLive
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
    public $orderDetailForAccessory = [];
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
    public $fixerId;
    public $users;
    public $customers;
    public $accessories_list = [];
    public $accessories_select = [];
    public $positions_list = [];
    public $positions_select = [];
    public $o_service_list = [];
    public $o_service_select = [];

    protected $listeners = ['countServicePrice', 'changeListService', 'changeAccessoryCode', 'changeWarehousePos', 'countAccessoryPrice'];

    public function mount()
    {
        $this->users = User::select(['id', 'name'])->get();
        $this->customers = Customer::select('id', 'name', 'phone')->get();
        $orderInfo = Order::where('id', $this->orderId)->first();
        if ($orderInfo) {
            $this->fixerId = $orderInfo->fixer;
            $this->customer = $orderInfo->customer_id;
        }
        $serviceList = OtherService::where('order_id', $this->orderId)->get();
        $this->i = $serviceList->count();
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
        $this->j = $accessoryList->count();
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
            $this->orderDetailForAccessory[$key + 1] = $accessory->id;
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
    }
    public function render()
    {
        $this->dispatchBrowserEvent('setSelect2');
        return view('livewire.service.other.edit');
    }

    public function update()
    {
        $this->validate(
            [
                'customer' => 'required',
                'fixerId' => 'required',
            ],
            [
                'customer.required' => 'Bắt buộc chọn khách hàng',
                'fixerId.required' => 'Bắt buộc chọn NV sửa chữa',
            ]
        );

        if (!empty($this->services)) {
            foreach ($this->services as $service) {
                $this->validate(
                    [
                        'service_content.' . $service => 'required',
                        'service_price.' . $service => 'required|numeric|min:0|max:9999999999',
                        'service_promotion.' . $service => 'required|numeric|min:0|max:100',
                        'list_service.' . $service => 'required',
                    ],
                    [
                        'service_content.' . $service . '.required' => 'Bắt buộc nhập nội dung dịch vụ',
                        'service_price.' . $service . '.required' => 'Bắt buộc nhập tiền công',
                        'service_price.' . $service . '.min' => 'Tiền công tối thiếu là 0',
                        'service_price.' . $service . '.max' => 'Tiền công tối đa là 9999999999',
                        'service_promotion.' . $service . '.required' => 'Bắt buộc nhập khuyến mại',
                        'service_promotion.' . $service . '.min' => 'Khuyến mãi tối thiếu là 0',
                        'service_promotion.' . $service . '.max' => 'Khuyến mãi tối đa là 100',
                        'list_service.' . $service . '.required' => 'Bắt buộc chọn dịch vụ',
                    ]
                );
            }
        }

        if (!empty($this->accessories)) {
            foreach ($this->accessories as $accessory) {
                $this->validate(
                    [
                        'accessory_code.' . $accessory => 'required',
                        'accessory_warehouse_pos.' . $accessory => 'required',
                    ],
                    [
                        'accessory_code.' . $accessory . '.required' => 'Bắt buộc nhập mã phụ tùng',
                        'accessory_warehouse_pos.' . $accessory . '.required' => 'Bắt buộc nhập vị trí kho',
                    ]
                );
            }
        }

        DB::beginTransaction();

        if (empty($this->customer)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Chỉnh sửa không thành công']);
        }

        $customerInfo = Customer::where('id', $this->customer)->first();
        if (empty($customerInfo)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Chỉnh sửa không thành công']);
        }

        $order = Order::where('id', $this->orderId)->first();

        if (!empty($this->services)) {
            $serviceData = [];
            foreach ($this->services as $key => $service) {
                $serviceData[] = [
                    'list_service_id' => $this->list_service[$service],
                    'content' => $this->service_content[$service],
                    'price' => $this->service_price[$service],
                    'promotion' => isset($this->service_promotion[$service]) ? $this->service_promotion[$service] : 0,
                    'order_id' => $this->orderId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            $service = OtherService::where('order_id', $this->orderId)->delete();
            if (!OtherService::insert($serviceData)) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Chỉnh sửa không thành công']);
                return;
            }
        }

        if (!empty($this->accessories)) {
            $accessoryData = [];
            foreach ($this->accessories as $key => $accessory) {
                if ($this->accessory_available_quantity[$accessory] < 0) {
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Số lượng phụ tùng không đủ bán']);
                    return;
                }

                $accessoryData[] = [
                    'code' => $this->accessory_code[$accessory],
                    'quantity' => $this->accessory_quantity[$accessory],
                    'price' => $this->accessory_price[$accessory],
                    'product_id' => $this->accessory_product[$accessory],
                    'vat_price' => $this->accessory_price_vat[$accessory],
                    'actual_price' => $this->accessory_price_actual[$accessory],
                    'position_in_warehouse_id' => $this->accessory_warehouse_pos[$accessory],
                    'warehouse_id' => PositionInWarehouse::findOrFail($this->accessory_warehouse_pos[$accessory])->warehouse_id,
                    'promotion' => $this->accessory_promotion[$accessory],
                    'status' => EOrderDetail::STATUS_SAVED,
                    'admin_id' => auth()->id(),
                    'category' => EOrderDetail::CATE_REPAIR,
                    'type' => EOrderDetail::TYPE_BANLE,
                    'order_id' => $this->orderId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            // Cộng trả lại tất cả số lượng phụ tùng cũ trước khi clear order
            $orderOldForAccessory = OrderDetail::where('order_id', $this->orderId)
                ->where('category', EOrderDetail::CATE_REPAIR)
                ->where('type', EOrderDetail::TYPE_BANLE)
                ->where('status', EOrderDetail::STATUS_SAVED)
                ->get();
            foreach ($orderOldForAccessory as $key => $accessoryOld) {
                $accessory = Accessory::where('id', $accessoryOld->product_id)
                    ->where('position_in_warehouse_id', $accessoryOld->position_in_warehouse_id)
                    ->first();
                if ($accessory) {
                    $accessory->quantity += $accessoryOld->quantity;
                    $accessory->save();
                }
            }
            OrderDetail::where('order_id', $this->orderId)
                ->where('category', EOrderDetail::CATE_REPAIR)
                ->where('type', EOrderDetail::TYPE_BANLE)
                ->where('status', EOrderDetail::STATUS_SAVED)->delete();

            if (!OrderDetail::insert($accessoryData)) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Chỉnh sửa không thành công']);
                return;
            }

            foreach ($this->accessory_product as $key => $accessoryId) {
                $accessory = Accessory::where('id', $accessoryId)
                    ->where('position_in_warehouse_id', $this->accessory_warehouse_pos[$key])
                    ->first();

                if ($accessory) {
                    if ($accessory->quantity >= (int)$this->accessory_quantity[$key]) {
                        $accessory->quantity -= (int)$this->accessory_quantity[$key];
                        $accessory->save();
                    } else {
                        DB::rollBack();
                        $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Tạo phiếu KTĐK thất bại']);
                        return;
                    }
                }
            }
        }
        $order->fixer = $this->fixerId;
        $order->total_items = $order->totalItem();
        $order->total_money = $order->totalPriceForOtherService();
        $order->save();

        DB::commit();

        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Chỉnh sửa thành công']);
        // $this->resetInputFields();
        // $this->resetServiceInputFields();
        // $this->resetAccessoryInputFields();
    }

    public function addService($i)
    {
        $i = $this->i + 1;
        $this->i = $i;
        $this->disabled = false;
        $this->service_promotion[$i] = 0;
        $this->o_service_list[$i] = ListService::select('id', 'title')->get();
        array_push($this->services, $i);
    }

    public function removeService($i)
    {
        unset($this->services[$i]);
        unset($this->list_service[$i]);
        unset($this->service_content[$i]);
        unset($this->service_price[$i]);
        unset($this->service_promotion[$i]);
        unset($this->service_total[$i]);
        unset($this->o_service_list[$i]);
        $this->i = $this->i - 1;
    }

    public function changeListService($data)
    {
        $this->list_service[$data['index']] = (int)$data['value'];

        $this->o_service_select[] = $data['value'];
    }

    public function countServicePrice($index)
    {
        $price = 0;
        $promotion = 0;
        $total = 0;

        if (isset($this->service_price[$index])) {
            $price = $this->service_price[$index];
        }
        if (isset($this->service_promotion[$index])) {
            $promotion = $this->service_promotion[$index];
        }

        if (empty($price)) {
            $total = 0;
        }

        if (empty($promotion)) {
            $total = $price;
        }

        if (!empty($price) && !empty($promotion)) {
            $total = $price - (($price / 100) * $promotion);
        }

        $this->service_total[$index] = number_format($total);
    }

    public function addAccessory($j)
    {
        $j = $this->j + 1;
        $this->j = $j;
        $this->disabled = false;
        $this->accessory_available_quantity[$j] = 0;
        $this->accessory_promotion[$j] = 0;
        $this->accessories_list[$j] = Accessory::whereNotNull('code')
            ->whereNotIn('code', $this->accessories_select)
            ->where('quantity', '>', 0)
            ->pluck('code')
            ->unique();
        $this->positions_list[$j] = [];
        array_push($this->accessories, $j);
    }

    public function removeAccessory($j)
    {
        unset($this->accessories[$j]);
        unset($this->accessory_code[$j]);
        unset($this->accessory_warehouse_pos[$j]);
        unset($this->accessory_name[$j]);
        unset($this->accessory_supplier[$j]);
        unset($this->accessory_quantity[$j]);
        unset($this->accessory_price[$j]);
        unset($this->accessory_promotion[$j]);
        unset($this->accessory_total[$j]);
        unset($this->accessory_price_vat[$j]);
        unset($this->accessory_price_actual[$j]);
        unset($this->accessory_available_quantity[$j]);
        unset($this->accessory_available_quantity_root[$j]);
        unset($this->accessory_available_quantity[$j]);
        unset($this->accessories_list[$j]);
        $this->j = $this->j - 1;
    }

    public function changeAccessoryCode($data)
    {
        $this->accessory_code[$data['index']] = $data['value'];
        $this->accessories_select[] = $data['value'];

        if (!empty($data['value'])) {
            $accessory = Accessory::where('code', $data['value'])->where('quantity', '>', 0)->first();
            $this->accessory_supplier[$data['index']] = isset($accessory->supplier) ? $accessory->supplier->code : '';
            $this->accessory_price[$data['index']] = $accessory->price;
            $this->accessory_name[$data['index']] = $accessory->name;
            $this->accessory_promotion[$data['index']] = 0;

            $positionIds = Accessory::where('code', $data['value'])
                ->where('quantity', '>', 0)
                ->select('position_in_warehouse_id')
                ->pluck('position_in_warehouse_id');

            $this->positions_list[$data['index']] = PositionInWarehouse::with(['warehouse'])
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
    }

    public function changeWarehousePos($data)
    {
        $this->accessory_warehouse_pos[$data['index']] = $data['value'];

        if (!empty($data['value'])) {
            $record = Accessory::with(['supplier', 'positionInWarehouse', 'positionInWarehouse.warehouse'])
                ->whereHas(
                    'warehouse',
                    function ($q) {
                        $q->whereNull('deleted_at');
                    }
                )
                ->where('code', $this->accessory_code[$data['index']])
                ->where('quantity', '>', 0)
                ->where('position_in_warehouse_id', $this->accessory_warehouse_pos[$data['index']])
                ->first();

            if ($record) {
                $oldAccessoryQuatity = 0;
                if (count($this->orderDetailForAccessory) + 1 > $data['index']) {
                    $orderDetailId = $this->orderDetailForAccessory[$data['index']];
                    $oldAccessory = OrderDetail::where('id', $orderDetailId)->first();
                    $oldAccessoryQuatity = 0;
                    if ($oldAccessory) {
                        $oldAccessoryQuatity = $oldAccessory->quantity;
                    }
                }
                $this->accessory_product[$data['index']] = $record->id;
                $this->accessory_available_quantity[$data['index']] = $record->quantity + ($oldAccessoryQuatity - 1);
                $this->accessory_available_quantity_root[$data['index']] = $record->quantity;
                $this->accessory_quantity[$data['index']] = 1;
                $this->accessory_total[$data['index']] = $record->price;
                $this->accessory_price_vat[$data['index']] = $record->price;
                $this->accessory_price_actual[$data['index']] = $record->price;
            }
        }
    }

    public function countAccessoryPrice($index)
    {
        if (!isset($this->accessory_quantity[$index]) || !isset($this->accessory_price[$index])) {
            return;
        }

        $quantity = $this->accessory_quantity[$index];
        $price = $this->accessory_price[$index];

        $oldAccessoryQuatity = 0;
        if (count($this->orderDetailForAccessory) + 1 > $index) {
            $orderDetailId = $this->orderDetailForAccessory[$index];
            $oldAccessory = OrderDetail::where('id', $orderDetailId)->first();

            if ($oldAccessory) {
                $oldAccessoryQuatity = $oldAccessory->quantity;
            }
        }
        $this->accessory_available_quantity[$index] = $this->accessory_available_quantity_root[$index]  + ($oldAccessoryQuatity - (int)$quantity);

        $promotion = 0;
        $total = 0;

        $price = $price * $quantity;

        if (isset($this->accessory_promotion[$index])) {
            $promotion = $this->accessory_promotion[$index];
        }

        if (empty($promotion)) {
            $total = $price;
        }

        if (!empty($promotion)) {
            $total = $price - (($price / 100) * $promotion);
        }

        $this->accessory_total[$index] = number_format($total);
        $this->accessory_price_vat[$index] = $total;
        $this->accessory_price_actual[$index] = $total;
    }

    public function resetInputFields()
    {
        $this->customer = null;
        $this->disabled = true;
    }

    public function resetServiceInputFields()
    {
        $this->service_content = [];
        $this->service_price = [];
        $this->service_promotion = [];
        $this->service_total = [];
        $this->services = [];
        $this->i = 0;
    }

    public function resetAccessoryInputFields()
    {
        $this->accessory_code = [];
        $this->accessory_warehouse_pos = [];
        $this->accessory_name = [];
        $this->accessory_supplier = [];
        $this->accessory_quantity = [];
        $this->accessory_available_quantity = [];
        $this->accessory_price = [];
        $this->accessory_promotion = [];
        $this->accessory_total = [];
        $this->accessory_price_vat = [];
        $this->accessory_price_actual = [];
        $this->accessory_product = [];
        $this->accessories = [];
        $this->j = 0;
        $this->accessory_warehouse_pos_list = [];
        $this->positionsList = [];
        $this->fixerId = '';
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
