<?php

namespace App\Http\Livewire\Component;

use App\Models\Accessory;
use Livewire\Component;
use App\Models\OrderDetail;
use Illuminate\Validation\Rule;
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Enum\ERepairTask;
use App\Models\Warehouse;
use App\Service\Community;
use Illuminate\Support\Facades\DB;
use App\Models\PositionInWarehouse;
use App\Models\RepairTask;
use App\Models\Order;

class InputAccessories extends Component
{
    // Trạng thái page
    public $isShow = false;
    public $isEdit = false;
    public $isAdd = false;
    public $arrayAddedAccessory = [];
    public $isAddAccessory = false;
    public $type;
    public $accessories;
    public $accessory = [];
    public $positionWarehouseList = [];
    public $orderId;
    public $status = false;
    public $vat_price, $actual_price, $listed_price;
    public $accessaryNumber, $positionWarehouse, $accessaryName, $quantity, $availableQuantity, $price, $promotion, $total, $supplier, $isAtrophy;
    public $accessaryNumberEdit, $positionWarehouseEdit, $accessaryNameEdit, $quantityEdit, $availableQuantityEdit, $priceEdit, $promotionEdit, $totalEdit, $vat_priceEdit, $actual_priceEdit, $listed_priceEdit, $supplierEdit, $isAtrophyEdit;
    public $itemEditID;
    public $motorbikeId;
    public $orderDetailChangeToAtrophy = [];
    protected $listeners = [
        'loadListInput' => 'render',
        'loadMotobikeInfo' => 'loadMotobikeInfo'
    ];
    public function mount()
    {
        // if ($this->isAdd) {
        //     $this->promotion = 0;
        //     $this->isAtrophy = false;
        // }
        $this->promotion = 0;
        $this->isAtrophy = false;

        $positionSourceWarehouseListDb = PositionInWarehouse::with(['warehouse'])->whereHas('warehouse', function ($q) {
            $q->whereNull('deleted_at');
        })->get();
        $this->positionWarehouseList = $positionSourceWarehouseListDb->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'name' => $item->warehouse->name . "-" . $item->name
            ];
        });
    }
    public function render()
    {
        if ($this->isShow) {
            $this->status = true;
        }
        if ($this->isShow) {
            $accessoryDraft = OrderDetail::where('order_id', $this->orderId)->where(function ($q) {
                $q->where('order_id', $this->orderId)
                    ->where('category', EOrderDetail::CATE_REPAIR)
                    ->where('status', EOrderDetail::STATUS_SAVED);
            })->get();
        }
        if ($this->isEdit) {
            $orderId = $this->orderId;
            $accessoryDraft = OrderDetail::with(['order', 'accessorie.supplier'])->where(
                function ($q) use ($orderId) {
                    $q->where('order_id', $orderId);
                    $q->where('status', EOrderDetail::STATUS_SAVED);
                    $q->where('category', EOrderDetail::CATE_REPAIR);
                    $q->where('type', EOrderDetail::TYPE_BANLE);
                }
            )
                ->orWhere(
                    function ($q) {
                        $q->where('status', EOrderDetail::STATUS_SAVE_DRAFT);
                        $q->where('category', EOrderDetail::CATE_REPAIR);
                        $q->where('type', EOrderDetail::TYPE_BANLE);
                        $q->where('admin_id', auth()->id());
                    }
                )->get();
        }
        if ($this->isAdd) {
            $accessoryDraft = collect();
            if ($this->motorbikeId) {
                $listOrderBefore = Order::where('motorbikes_id', $this->motorbikeId)
                    ->where('category', EOrder::CATE_REPAIR)
                    ->where('type', EOrder::TYPE_BANLE)
                    ->where('order_type', EOrder::ORDER_TYPE_SELL)->get()->pluck('id');
                $accessoryDraft = OrderDetail::with(['order', 'order.motorbike', 'accessorie', 'accessorie.supplier'])
                    ->whereIn('order_id', $listOrderBefore)
                    ->where(
                        function ($q) {
                            $q->where('status', EOrderDetail::STATUS_SAVED);
                            $q->where('category', EOrderDetail::CATE_REPAIR);
                            $q->where('type', EOrderDetail::TYPE_BANLE);
                            $q->where('is_atrophy', EOrderDetail::ATROPHY_ACCESSORY);
                        }
                    )
                    ->orWhere(
                        function ($q) {
                            $q->where('status', EOrderDetail::STATUS_SAVE_DRAFT);
                            $q->where('category', EOrderDetail::CATE_REPAIR);
                            $q->where('type', EOrderDetail::TYPE_BANLE);
                            $q->where('admin_id', auth()->id());
                        }
                    )
                    ->orWhere(
                        function ($q) {
                            $q->whereIn('id', $this->orderDetailChangeToAtrophy);
                        }
                    )
                    ->get();
            }
        }
        if ($this->accessaryNumber && $this->positionWarehouse && $this->quantity) {
            $acessory = Accessory::where('code', $this->accessaryNumber)
                ->where('position_in_warehouse_id', $this->positionWarehouse)
                ->first();
            if ($acessory) {
                $this->availableQuantity = $acessory->quantity - $this->quantity;
            } else {
                $this->availableQuantity = 0;
            }
        }
        if ($this->accessaryNumberEdit && $this->positionWarehouseEdit && $this->quantityEdit) {
            $acessory = Accessory::where('code', $this->accessaryNumberEdit)
                ->where('position_in_warehouse_id', $this->positionWarehouseEdit)
                ->first();
            if ($acessory) {
                $this->availableQuantityEdit  = $acessory->quantity - $this->quantityEdit;
            } else {
                $this->availableQuantityEdit = 0;
            }
        }
        if ($this->quantity && $this->price) {
            if ($this->promotion <= 100)
                $this->total = number_format(round(Community::getAmount($this->quantity) * Community::getAmount($this->price) * (100 - $this->promotion) / 100));
        }
        if ($this->quantityEdit && $this->priceEdit) {
            if ($this->promotionEdit <= 100)
                $this->totalEdit = number_format(round(Community::getAmount($this->quantityEdit) * Community::getAmount($this->priceEdit) * (100 - $this->promotionEdit) / 100));
        }
        if ($accessoryDraft->isNotEmpty()) {
            $this->arrayAddedAccessory = $accessoryDraft->pluck('product_id');
        }

        $this->updateUI();
        return view('livewire.component.input-accessories', ['accessoryDraft' => $accessoryDraft]);
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
    public function cancelNew()
    {
        $this->isAddAccessory = false;
        $this->emit('setBtnisAddAccessory');
        $this->emitUp('enableButtonParentAccesory');
    }
    public function addNew()
    {
        $this->emitUp('disableButtonParentAccesory');
        $this->accessory = Accessory::whereNotNull('code')->whereNotIn('id', $this->arrayAddedAccessory)->where('quantity', '>', 0)->pluck('code')->unique();
        $this->isAddAccessory = true;
    }
    public function addItem()
    {
        $this->validate([
            'accessaryNumber' => 'required',
            'positionWarehouse' => 'required',
        ], [
            'accessaryNumber.required' => 'Mã phụ tùng bắt buộc',
            'positionWarehouse.required' => 'Vị trí kho bắt buộc'
        ]);
        $acessory = Accessory::where('code', $this->accessaryNumber)
            ->where('position_in_warehouse_id', $this->positionWarehouse)
            ->first();
        $this->validate([
            'quantity' => 'required|numeric|min:1|max:' . (empty($acessory) ? '0' : $acessory->quantity),
            'promotion' => 'required|numeric|min:0|max:100',
        ], [
            'quantity.required' => 'Số lượng bắt buộc nhập',
            'quantity.min' => 'Số lượng tối thiếu là 1',
            'quantity.max' => 'Số lượng tối đa ở trong kho là ' . (empty($acessory) ? '0' : $acessory->quantity),
            'promotion.required' => 'Khuyến mãi bắt buộc nhập',
            'promotion.min' => 'Khuyến mãi tối thiếu là 0',
            'promotion.max' => 'Khuyến mãi tối đa là 100',
            'promotion.numeric' => 'Khuyến mãi phải là số'
        ]);
        $order_detail = new OrderDetail();
        if ($this->isEdit) {
            $order_detail->order_id = $this->orderId;
        }
        $order_detail->code = $this->accessaryNumber ?? '';
        $order_detail->quantity = Community::getAmount($this->quantity);
        $order_detail->price = Community::getAmount($this->price);
        $order_detail->status = EOrderDetail::STATUS_SAVE_DRAFT;
        $order_detail->admin_id = auth()->id();
        $order_detail->category = EOrderDetail::CATE_REPAIR;
        $order_detail->type = EOrderDetail::TYPE_BANLE;
        $order_detail->product_id = $this->accessories->id;
        $order_detail->vat_price = null;
        $order_detail->actual_price = null;
        // $order_detail->vat_price = Community::getAmount($this->vat_price);
        // $order_detail->actual_price = Community::getAmount($this->actual_price);
        $order_detail->position_in_warehouse_id = $this->positionWarehouse;
        $order_detail->warehouse_id = PositionInWarehouse::findOrFail($this->positionWarehouse)->warehouse_id;
        $order_detail->promotion = $this->promotion ?? 0;
        $order_detail->is_atrophy = $this->isAtrophy;
        $order_detail->save();
        $this->isAddAccessory = false;

        $this->render();
        $this->resetInputFields();
        $this->emit('setBtnisAddAccessory');
        $this->emitUp('enableButtonParentAccesory');
    }
    public function editItem($id)
    {
        $this->emitUp('disableButtonParentAccesory');
        // if ($this->isEdit) {
        //     $this->dispatchBrowserEvent('setEventForSelectWhenEdit');
        // }
        $this->accessory = Accessory::whereNotNull('code')->pluck('code')->unique();
        $this->emit('setBtnisAddAccessory');
        $this->itemEditID = $id;
        $order_detail_edit = OrderDetail::findOrFail($id);
        $this->accessaryNumberEdit = $order_detail_edit->accessorie->code;
        $this->positionWarehouseEdit = $order_detail_edit->position_in_warehouse_id;

        $positionSourceWarehouseListDb = PositionInWarehouse::where('id', $order_detail_edit->position_in_warehouse_id)->with(['warehouse'])->whereHas('warehouse', function ($q) {
            $q->whereNull('deleted_at');
        })->get();
        $this->positionWarehouseList = $positionSourceWarehouseListDb->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'name' => $item->warehouse->name . "-" . $item->name
            ];
        });

        $accessories = Accessory::query()->with('supplier')
            ->where('code', $this->accessaryNumberEdit)
            ->where('position_in_warehouse_id', $this->positionWarehouseEdit)
            ->first();
        if ($accessories) {
            $this->supplierEdit = isset($accessories->supplier) ? $accessories->supplier->code : '';
            $this->accessaryNameEdit = isset($accessories->name) ? $accessories->name : '';
        }
        $this->quantityEdit = number_format($order_detail_edit->quantity);
        $this->priceEdit = number_format($order_detail_edit->price);
        $this->promotionEdit = number_format($order_detail_edit->promotion);
        $this->vat_priceEdit = number_format($order_detail_edit->vat_price);
        // $this->actual_priceEdit = number_format($order_detail_edit->actual_price);
        // $this->listed_priceEdit = number_format($order_detail_edit->listed_price);
        $this->isAtrophyEdit = $order_detail_edit->is_atrophy == EOrderDetail::ATROPHY_ACCESSORY;
        //$this->updatedAccessaryNumberEdit();
    }
    public function updateItem($id)
    {
        $this->validate([
            'accessaryNumberEdit' => 'required',
            'positionWarehouseEdit' => 'required',
            'promotionEdit' => 'required|numeric|min:0|max:100',
        ], [
            'accessaryNumberEdit.required' => 'Mã phụ tùng bắt buộc',
            'positionWarehouseEdit.required' => 'Vị trí kho bắt buộc',
            'promotionEdit.required' => 'Khuyến mãi bắt buộc nhập',
            'promotionEdit.min' => 'Khuyến mãi tối thiếu là 0',
            'promotionEdit.max' => 'Khuyến mãi tối đa là 100',
            'promotionEdit.numeric' => 'Khuyến mãi phải là số'
        ]);
        $order_detail = OrderDetail::findOrFail($id);
        $acessoryUpdate = Accessory::where('code', $this->accessaryNumberEdit)
            ->where('position_in_warehouse_id', $this->positionWarehouseEdit)
            ->first();

        if ($order_detail->status == EOrderDetail::STATUS_SAVED && $order_detail->is_atrophy == EOrderDetail::NOT_ATROPHY_ACCESSORY) {
            // Cập nhật lại số lượng phụ tùng khi update trạng thái không hao mòn
            // Nếu không chuyển trạng thái hao mòn vẫn giữ không hao mòn
            if ($this->isAtrophyEdit == EOrderDetail::NOT_ATROPHY_ACCESSORY) {
                if ($order_detail->position_in_warehouse_id == $this->positionWarehouseEdit && $this->accessaryNumberEdit == $order_detail->accessorie->code) {
                    $this->validate([
                        'quantityEdit' => 'required|numeric|min:1|max:' . ($order_detail->quantity + $acessoryUpdate->quantity),
                    ], [
                        'quantityEdit.required' => 'Số lượng bắt buộc nhập',
                        'quantityEdit.min' => 'Số lượng tối thiếu là 1',
                        'quantityEdit.max' => 'Số lượng lấy thêm vượt quá số lượng trong kho (' . $acessoryUpdate->quantity . ')'
                    ]);
                    $acessoryUpdate->quantity += ($order_detail->quantity - $this->quantityEdit);
                    $acessoryUpdate->save();
                } else {
                    $this->validate([
                        'quantityEdit' => 'required|numeric|min:1|max:' . $acessoryUpdate->quantity,
                    ], [
                        'quantityEdit.required' => 'Số lượng bắt buộc nhập',
                        'quantityEdit.min' => 'Số lượng tối thiếu là 1',
                        'quantityEdit.max' => 'Số lượng vượt quá số lượng trong kho' . $acessoryUpdate->quantity
                    ]);
                    $acessoryUpdate->quantity -= $this->quantityEdit;
                    $acessoryUpdate->save();
                    $acessoryOld = Accessory::where('code', $order_detail->accessorie->code)
                        ->where('position_in_warehouse_id', $order_detail->position_in_warehouse_id)
                        ->first();
                    $acessoryOld->quantity += $order_detail->quantity;
                    $acessoryOld->save();
                }
            } else {
                // Nếu chuyển từ không -> có hao mòn thì trả lại số lượng cho phụ tùng cũ
                $acessoryOld = Accessory::where('code', $order_detail->accessorie->code)
                    ->where('position_in_warehouse_id', $order_detail->position_in_warehouse_id)
                    ->first();
                $acessoryOld->quantity += $order_detail->quantity;
                $acessoryOld->save();
            }
        }
        if ($order_detail->status == EOrderDetail::STATUS_SAVED && $order_detail->is_atrophy == EOrderDetail::ATROPHY_ACCESSORY) {
            // Cập nhật lại số lượng phụ tùng khi update trạng thái hao mòn
            // Nếu chuyển từ có -> không hao mòn thì trừ số lượng cho phụ tùng
            if ($this->isAtrophyEdit == EOrderDetail::NOT_ATROPHY_ACCESSORY) {
                $this->validate([
                    'quantityEdit' => 'required|numeric|min:1|max:' . $acessoryUpdate->quantity,
                ], [
                    'quantityEdit.required' => 'Số lượng bắt buộc nhập',
                    'quantityEdit.min' => 'Số lượng tối thiếu là 1',
                    'quantityEdit.max' => 'Số lượng vượt quá số lượng trong kho' . $acessoryUpdate->quantity
                ]);
                $acessoryUpdate->quantity -= $this->quantityEdit;
                $acessoryUpdate->save();
                array_push($this->orderDetailChangeToAtrophy, $order_detail->id);
                $order_detail->status = EOrderDetail::STATUS_SAVE_DRAFT;
                $order_detail->order_id = null;
            }
        }

        $order_detail->product_id = $acessoryUpdate->id;
        $order_detail->code = $this->accessaryNumberEdit ?? '';
        $order_detail->quantity = Community::getAmount($this->quantityEdit);
        $order_detail->price = Community::getAmount($this->priceEdit);
        $order_detail->promotion = Community::getAmount($this->promotionEdit);
        $order_detail->admin_id = auth()->id();
        $order_detail->vat_price = null;
        $order_detail->actual_price = null;
        // $order_detail->vat_price = Community::getAmount($this->vat_priceEdit);
        // $order_detail->actual_price = Community::getAmount($this->actual_priceEdit);
        $order_detail->position_in_warehouse_id = $this->positionWarehouseEdit;
        $order_detail->warehouse_id = PositionInWarehouse::findOrFail($this->positionWarehouseEdit)->warehouse_id;
        $order_detail->is_atrophy = $this->isAtrophyEdit;
        $order_detail->save();
        $acessoryUpdate->name = $this->accessaryNameEdit;
        $acessoryUpdate->update();


        if ($order_detail->status == EOrderDetail::STATUS_SAVED) {
            // Cập nhật lại tổng giá hóa đơn khi update
            $orders = Order::where('id', $this->orderId)->first();
            if ($orders) {
                $orders->total_items = $orders->totalItem();
                $orders->total_money = $orders->totalPriceForGeneralRepair();
                $orders->save();
            }
        }
        $this->itemEditID = '';
        $this->resetInputFields();
        if ($this->isEdit && $order_detail->status == EOrderDetail::STATUS_SAVED) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhật phụ tùng thay thế thành công']);
        }
        $this->emitUp('enableButtonParentAccesory');
    }
    public function cancel()
    {
        $this->itemEditID = '';
        $this->emitUp('enableButtonParentAccesory');
    }
    public function delete($id)
    {
        $this->itemEditID = '';
        $order_detail = OrderDetail::findOrFail($id);
        $order_detail->delete();
        if ($order_detail->status == EOrderDetail::STATUS_SAVED && $order_detail->is_atrophy == EOrderDetail::NOT_ATROPHY_ACCESSORY) {
            // Cập nhật lại số lượng phụ tùng khi xóa
            $acessoryOld = Accessory::where('code', $order_detail->accessorie->code)
                ->where('position_in_warehouse_id', $order_detail->position_in_warehouse_id)
                ->first();
            $acessoryOld->quantity += $order_detail->quantity;
            $acessoryOld->save();
            // Cập nhật lại tổng giá hóa đơn khi xóa
            $orders = Order::where('id', $this->orderId)->first();
            $orders->total_items = $orders->totalItem();
            $orders->total_money = $orders->totalPriceForGeneralRepair();
            $orders->save();
        }
        if ($this->isEdit && $order_detail->status == EOrderDetail::STATUS_SAVED) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa phụ tùng thay thế thành công']);
        }
    }
    public function updatedAccessaryNumber()
    {
        if (!$this->accessaryNumber && !$this->positionWarehouse) {
            $this->resetInputFields();
        }
        if ($this->accessaryNumber) {
            $infoAccessory = Accessory::where('code', $this->accessaryNumber)->first();
            $this->supplier = isset($infoAccessory->supplier) ? $infoAccessory->supplier->code : '';
            $this->price = isset($infoAccessory->category_accessory) ? $infoAccessory->category_accessory->netprice : $infoAccessory->price;
            $this->accessaryName = $infoAccessory->name;
            $arrayPosition = Accessory::where('code', $this->accessaryNumber)
                ->where('quantity', '>', 0)
                ->select('position_in_warehouse_id')
                ->get()->pluck('position_in_warehouse_id');
            $positionWarehouse = PositionInWarehouse::with(['warehouse'])->whereHas('warehouse', function ($q) {
                $q->whereNull('deleted_at');
            })->whereIn('id', $arrayPosition)->get();
            $this->positionWarehouseList = $positionWarehouse->map(function ($item, $key) {
                return [
                    'id' => $item->id,
                    'name' => $item->warehouse->name . " - " . $item->name
                ];
            });
        } else {
            $arrayPosition = Accessory::select('position_in_warehouse_id')
                ->where('quantity', '>', 0)
                ->get()->pluck('position_in_warehouse_id');
            $positionWarehouse = PositionInWarehouse::with(['warehouse'])->whereHas('warehouse', function ($q) {
                $q->whereNull('deleted_at');
            })->whereIn('id', $arrayPosition)->get();
            $this->positionWarehouseList = $positionWarehouse->map(function ($item, $key) {
                return [
                    'id' => $item->id,
                    'name' => $item->warehouse->name . " - " . $item->name
                ];
            });
        }
        if ($this->accessaryNumber && $this->positionWarehouse) {
            $this->accessories = Accessory::with(['supplier', 'positionInWarehouse', 'positionInWarehouse.warehouse'])
                ->whereHas(
                    'warehouse',
                    function ($q) {
                        $q->whereNull('deleted_at');
                    }
                )
                ->where('code', $this->accessaryNumber)
                ->where('quantity', '>', 0)
                ->where('position_in_warehouse_id', $this->positionWarehouse)->first();
            if ($this->accessories) {
                $positionWarehouseListTmp = collect([]);
                $positionWarehouseListTmp->push([
                    'id' => $this->accessories->positionInWarehouse->id,
                    'name' => $this->accessories->positionInWarehouse->warehouse->name . "-" . $this->accessories->positionInWarehouse->name
                ]);
                $this->positionWarehouseList = $positionWarehouseListTmp;
                $this->supplier = isset($this->accessories->supplier) ? $this->accessories->supplier->code : '';
                //$this->quantity = $this->accessories->quantity;
                $this->quantity = 1;
                $this->price = isset($this->accessories->category_accessory) ? $this->accessories->category_accessory->netprice : $this->accessories->price;
                // $this->vat_price = $this->accessories->price;
                // $this->actual_price = $this->accessories->price;
                $this->accessaryName = $this->accessories->name;
            }
        }
    }
    public function updatedPositionWarehouse()
    {
        if (!$this->accessaryNumber && !$this->positionWarehouse) {
            $this->resetInputFields();
        }
        if ($this->positionWarehouse) {
            $this->accessory = Accessory::whereNotNull('code')
                ->whereNotIn('id', $this->arrayAddedAccessory)
                ->where('position_in_warehouse_id', $this->positionWarehouse)
                ->where('quantity', '>', 0)->pluck('code')->unique();
        } else {
            $this->accessory = Accessory::whereNotNull('code')
                ->whereNotIn('id', $this->arrayAddedAccessory)
                ->where('quantity', '>', 0)->pluck('code')->unique();
        }
        if ($this->accessaryNumber && $this->positionWarehouse) {
            $this->accessories = Accessory::with(['supplier', 'positionInWarehouse', 'positionInWarehouse.warehouse'])
                ->whereHas(
                    'warehouse',
                    function ($q) {
                        $q->whereNull('deleted_at');
                    }
                )
                ->where('code', $this->accessaryNumber)
                ->where('quantity', '>', 0)
                ->where('position_in_warehouse_id', $this->positionWarehouse)->first();

            if ($this->accessories) {
                $positionWarehouseListTmp = collect([]);
                $positionWarehouseListTmp->push([
                    'id' => $this->accessories->positionInWarehouse->id,
                    'name' => $this->accessories->positionInWarehouse->warehouse->name . "-" . $this->accessories->positionInWarehouse->name
                ]);
                $this->positionWarehouseList = $positionWarehouseListTmp;
                $this->supplier = isset($this->accessories->supplier) ? $this->accessories->supplier->code : '';
                //$this->quantity = $this->accessories->quantity;
                $this->quantity = 1;
                $this->price = isset($this->accessories->category_accessory) ? $this->accessories->category_accessory->netprice : $this->accessories->price;
                // $this->vat_price = $this->accessories->price;
                // $this->actual_price = $this->accessories->price;
                $this->accessaryName = $this->accessories->name;
            }
        }
    }
    public function updatedAccessaryNumberEdit()
    {
        if (!$this->accessaryNumberEdit && !$this->positionWarehouseEdit) {
            $this->resetEditInputFields();
        }
        $isEdit = $this->isEdit;
        if ($this->accessaryNumberEdit) {
            $infoAccessory = Accessory::where('code', $this->accessaryNumberEdit)->first();
            $this->supplierEdit = isset($infoAccessory->supplier) ? $infoAccessory->supplier->code : '';
            $this->priceEdit = isset($infoAccessory->category_accessory) ?  $infoAccessory->category_accessory->netprice : $infoAccessory->price;
            $this->accessaryNameEdit = $infoAccessory->name;
            $arrayPosition = Accessory::where('code', $this->accessaryNumberEdit)
                ->where(function ($q) use ($isEdit) {
                    if (!$isEdit) {
                        $q->where('quantity', '>', 0);
                    }
                })
                ->select('position_in_warehouse_id')->get()->pluck('position_in_warehouse_id');
            $positionWarehouse = PositionInWarehouse::with(['warehouse'])->whereHas('warehouse', function ($q) {
                $q->whereNull('deleted_at');
            })->whereIn('id', $arrayPosition)->get();
            $this->positionWarehouseList = $positionWarehouse->map(function ($item, $key) {
                return [
                    'id' => $item->id,
                    'name' => $item->warehouse->name . " - " . $item->name
                ];
            });
        } else {
            $arrayPosition = Accessory::select('position_in_warehouse_id')
                ->where(function ($q) use ($isEdit) {
                    if (!$isEdit) {
                        $q->where('quantity', '>', 0);
                    }
                })
                ->get()->pluck('position_in_warehouse_id');
            $positionWarehouse = PositionInWarehouse::with(['warehouse'])->whereHas('warehouse', function ($q) {
                $q->whereNull('deleted_at');
            })->whereIn('id', $arrayPosition)->get();
            $this->positionWarehouseList = $positionWarehouse->map(function ($item, $key) {
                return [
                    'id' => $item->id,
                    'name' => $item->warehouse->name . " - " . $item->name
                ];
            });
        }
        if ($this->accessaryNumberEdit && $this->positionWarehouseEdit) {
            $this->accessories = Accessory::with(['supplier', 'positionInWarehouse', 'positionInWarehouse.warehouse'])
                ->whereHas(
                    'warehouse',
                    function ($q) {
                        $q->whereNull('deleted_at');
                    }
                )
                ->where('code', $this->accessaryNumberEdit)
                ->where(function ($q) use ($isEdit) {
                    if (!$isEdit) {
                        $q->where('quantity', '>', 0);
                    }
                })
                ->where('position_in_warehouse_id', $this->positionWarehouseEdit)->first();
            if ($this->accessories) {
                $positionWarehouseListTmp = collect([]);
                $positionWarehouseListTmp->push([
                    'id' => $this->accessories->positionInWarehouse->id,
                    'name' => $this->accessories->positionInWarehouse->warehouse->name . "-" . $this->accessories->positionInWarehouse->name
                ]);
                $this->positionWarehouseList = $positionWarehouseListTmp;
                $this->supplierEdit = isset($this->accessories->supplier) ? $this->accessories->supplier->code : '';
                //$this->quantityEdit = $this->accessories->quantity;
                
                $this->priceEdit = isset($this->accessories->category_accessory) ? $this->accessories->category_accessory->netprice : $this->accessories->price;
                // $this->vat_priceEdit = $this->accessories->price;
                // $this->actual_priceEdit = $this->accessories->price;
                $this->accessaryNameEdit = $this->accessories->name;
            }
        }
    }
    public function updatedPositionWarehouseEdit()
    {

        if (!$this->accessaryNumberEdit && !$this->positionWarehouseEdit) {
            $this->resetEditInputFields();
        }
        $isEdit = $this->isEdit;
        if ($this->positionWarehouseEdit) {
            $this->accessory = Accessory::whereNotNull('code')
                ->whereNotIn('id', $this->arrayAddedAccessory)
                ->where('position_in_warehouse_id', $this->positionWarehouseEdit)
                ->where(function ($q) use ($isEdit) {
                    if (!$isEdit) {
                        $q->where('quantity', '>', 0);
                    }
                })

                ->pluck('code')->unique();
        } else {
            $this->accessory = Accessory::whereNotNull('code')
                ->whereNotIn('id', $this->arrayAddedAccessory)
                ->where(function ($q) use ($isEdit) {
                    if (!$isEdit) {
                        $q->where('quantity', '>', 0);
                    }
                })
                ->pluck('code')->unique();
        }
        if ($this->accessaryNumberEdit && $this->positionWarehouseEdit) {
            $this->accessories = Accessory::with(['supplier', 'positionInWarehouse', 'positionInWarehouse.warehouse'])
                ->whereHas(
                    'warehouse',
                    function ($q) {
                        $q->whereNull('deleted_at');
                    }
                )
                ->where('code', $this->accessaryNumberEdit)
                ->where(function ($q) use ($isEdit) {
                    if (!$isEdit) {
                        $q->where('quantity', '>', 0);
                    }
                })
                ->where('position_in_warehouse_id', $this->positionWarehouseEdit)->first();
            if ($this->accessories) {
                $positionWarehouseListTmp = collect([]);
                $positionWarehouseListTmp->push([
                    'id' => $this->accessories->positionInWarehouse->id,
                    'name' => $this->accessories->positionInWarehouse->warehouse->name . "-" . $this->accessories->positionInWarehouse->name
                ]);
                $this->positionWarehouseList = $positionWarehouseListTmp;
                $this->supplierEdit = isset($this->accessories->supplier) ? $this->accessories->supplier->code : '';
                $this->quantityEdit = $this->accessories->quantity;
                $this->priceEdit = isset($this->accessories->category_accessory) ? $this->accessories->category_accessory->netprice : $this->accessories->price;
                // $this->vat_priceEdit = $this->accessories->price;
                // $this->actual_priceEdit = $this->accessories->price;
                $this->accessaryNameEdit = $this->accessories->name;
            }
        }
    }

    public function resetInputFields()
    {
        $this->accessaryNumber = '';
        $this->accessaryName = '';
        $this->quantity = '';
        $this->price = '';
        $this->vat_price = '';
        // $this->actual_price = '';
        // $this->listed_price = '';
        $this->total = '';
        $this->supplier = '';
        $this->promotion = 0;
        $this->isAtrophy = false;
    }
    public function resetEditInputFields()
    {
        $this->accessaryNumberEdit = '';
        $this->accessaryNameEdit = '';
        $this->quantityEdit = '';
        $this->priceEdit = '';
        // $this->vat_priceEdit = '';
        // $this->actual_priceEdit = '';
        $this->listed_priceEdit = '';
        $this->totalEdit = '';
        $this->supplierEdit = '';
        $this->promotionEdit = 0;
        $this->isAtrophyEdit = false;
    }
    public function loadMotobikeInfo($motorbike)
    {
        $this->motorbikeId = $motorbike['motorbikeId'];
    }
}
