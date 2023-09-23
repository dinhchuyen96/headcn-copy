<?php

namespace App\Http\Livewire\Component;

use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Models\Accessory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Service\Community;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ListInput extends Component
{
    public $addStatus = false;
    public $type, $order_id, $status = false;
    public $vat_price, $actual_price, $listed_price;
    public $accessaryNumber, $quantity, $total, $buyDate, $statusInput = true;
    public $quantityRequest, $backQuantity, $quantityRequestedEdit, $orderNumberEdit, $backQuantityEdit;
    public $accessaryNumberEdit, $quantityEdit, $totalEdit, $buyDateEdit, $vat_priceEdit, $actual_priceEdit, $listed_priceEdit;
    public $itemEditID = '';
    public $code, $order_number, $name, $nameEdit;
    public $phone;
    protected $listeners = [
        'addNew',
        'loadListInput' => 'render',
        'updateCode',
        'updatePO',
        'resetListInput' => 'resetInput',
        'addInputRow',
        'updateSupPhone',
        'setbuyDate',
        'setbuyDateEdit',
    ];

    public function mount()
    {

        if (isset($_GET['id'])) {
            $this->order_id = $_GET['id'];
        }
        if (isset($_GET['show'])) {
            $this->status = true;
        }
        if ($this->type == EOrder::TYPE_NHAP) {
            $this->code = 'HVN';
        }
        $accessory_draft = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('category', EOrderDetail::CATE_ACCESSORY)->where('type', $this->type)->where('admin_id', auth()->id());
        if ($accessory_draft) {
            $accessory_draft->delete();
        }
    }

    public function setbuyDate($time)
    {
        $this->buyDate = date('Y-m-d', strtotime($time['buyDate']));
    }

    public function setbuyDateEdit($time)
    {
        $this->buyDateEdit = date('Y-m-d', strtotime($time['buyDateEdit']));
    }

    public function render()
    {
        $order_number_item = '';
        $this->buyDateEdit = reFormatDate($this->buyDateEdit, 'Y-m-d');
        if ($this->order_id) {
            $order = Order::find($this->order_id);
            if ($order) {
                $this->code = $order->code;
                $this->order_number = $order->order_no;
                $order_number_item = $order->order_no;
            }

            $query = OrderDetail::query();
            $query->where('order_id', $this->order_id);
            $accessory_draft = $query->orWhere(function ($q) {
                $q->where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('category', EOrderDetail::CATE_ACCESSORY)->where('type', $this->type)->where('admin_id', auth()->id());
            })->get();
        } else {
            if ($this->type == 3 && $this->code == 'HVN') {
                if ($this->order_number) {
                    $accessory_draft = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('category', EOrderDetail::CATE_ACCESSORY)->where('supplier_type', EOrderDetail::SUPPLIER_TYPE)->where('order_number', $this->order_number)->where('type', $this->type)->where('admin_id', auth()->id())->get();
                } else {
                    $accessory_draft = [];
                }
            } else {
                $accessory_draft = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('category', EOrderDetail::CATE_ACCESSORY)->where('type', $this->type)->where('supplier_type', '!=', EOrderDetail::SUPPLIER_TYPE)->where('admin_id', auth()->id())->get();
            }
        }
        if ($this->quantity && $this->listed_price) {
            $this->total = number_format((int)Community::getAmount($this->quantity) * (int)Community::getAmount($this->listed_price));
        }
        if ($this->quantityEdit && $this->listed_priceEdit) {
            $this->totalEdit = number_format((int)Community::getAmount($this->quantityEdit) * (int)Community::getAmount($this->listed_priceEdit));
        }
        $this->dispatchBrowserEvent('setDatePickerNow');
        $this->dispatchBrowserEvent('setDatePickerUpdate');
        if ($this->statusInput && $this->itemEditID && $this->buyDateEdit) {
            $this->dispatchBrowserEvent('setDatePickerEdit', ['date' => $this->buyDateEdit]);
        }
        return view('livewire.component.list-input', compact('accessory_draft', 'order_number_item'));
    }

    public function updatedAccessaryNumber()
    {
        if ($this->order_number) {
            $accessory_plan = DB::table('hms_part_order_plan_detail')->where('order_number', $this->order_number)->where('part_no', $this->accessaryNumber)->first();
            if ($accessory_plan) {
                $this->quantityRequest = $accessory_plan->quantity_requested;
                $this->backQuantity = $accessory_plan->back_order_qty;
                $this->name = $accessory_plan->part_description;
                $this->listed_price = number_format($accessory_plan->dnp);
            }
        } else {
            if ($this->accessaryNumber) {
                $accessory = Accessory::where('code', $this->accessaryNumber)->first();
                if ($accessory) {
                    $this->listed_price = number_format($accessory->price);
                    $this->actual_price = $this->listed_price;
                    $this->vat_price = $this->listed_price;
                    $this->name = $accessory->name;
                }
            }
        }
    }

    public function updatePO($order_number)
    {
        $this->order_number = $order_number;
        $this->render();
    }

    public function updatedOrderNumber()
    {
        $this->orderNumberEdit = $this->order_number;
    }

    public function updateCode($code)
    {
        $this->code = $code;
    }

    public function updateNotNumeric($input)
    {
        if ($input) {
            $input = numberFormat((int)Community::getAmount($input));
        }
        return $input;
    }

    public function updateSupPhone($phone)
    {
        $this->phone = $phone;
    }

    public function updatedVatPrice()
    {
        $this->vat_price = $this->updateNotNumeric($this->vat_price);
    }

    public function updatedQuantity()
    {
        $this->quantity = $this->updateNotNumeric($this->quantity);
    }

    public function updatedActualPrice()
    {
        $this->actual_price = $this->updateNotNumeric($this->actual_price);
    }

    public function updatedListedPrice()
    {
        $this->listed_price = $this->updateNotNumeric($this->listed_price);
    }

    public function updatedVatPriceEdit()
    {
        $this->vat_priceEdit = $this->updateNotNumeric($this->vat_priceEdit);
    }

    public function updatedQuantityEdit()
    {
        $this->quantityEdit = $this->updateNotNumeric($this->quantityEdit);
    }

    public function updatedActualPriceEdit()
    {
        $this->actual_priceEdit = $this->updateNotNumeric($this->actual_priceEdit);
    }

    public function updatedListedPriceEdit()
    {
        $this->listed_priceEdit = $this->updateNotNumeric($this->listed_priceEdit);
    }

    public function updatedAccessaryNumberEdit()
    {
        $order_detail_edit = DB::table('hms_part_order_plan_detail')->where('order_number', $this->order_number)->where('part_no', $this->accessaryNumberEdit)->first();
        if ($order_detail_edit) {
            $this->quantityEdit = $order_detail_edit->quantity_requested ? number_format($order_detail_edit->quantity_requested) : '';
            $this->orderNumberEdit = $this->order_number;
            $this->nameEdit = $order_detail_edit->part_description;
            $this->quantityRequestedEdit = $order_detail_edit->quantity_requested ? number_format($order_detail_edit->quantity_requested) : '';
            $this->backQuantityEdit = $order_detail_edit->back_order_qty ? number_format($order_detail_edit->back_order_qty) : '';
            $this->buyDateEdit = date('Y-m-d');
            $this->listed_priceEdit = $order_detail_edit->dnp ? number_format($order_detail_edit->dnp) : '';
        } else {
            if ($this->accessaryNumberEdit) {
                $accessory = Accessory::where('code', $this->accessaryNumber)->first();
                if ($accessory) {
                    $this->listed_priceEdit = numberFormat($accessory->price);
                    $this->actual_priceEdit = $this->listed_price;
                    $this->vat_priceEdit = $this->listed_price;
                    $this->nameEdit = $accessory->name;
                }
            }
        }

    }

    public function cancelNew()
    {
        $this->addStatus = false;
        $this->order_number = '';
        $this->resetInput();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->emit('setBtnAddStatus');
    }

    public function addNew()
    {
        $this->buyDate = Carbon::now()->format('Y-m-d');
        $this->addStatus = true;
    }

    public function addItem()
    {
        $this->statusInput = true;
        if (!is_integer($this->listed_price))
            $this->listed_price = (int)Community::getAmount($this->listed_price);
        if (!is_integer($this->vat_price))
            $this->vat_price = (int)Community::getAmount($this->vat_price);
        if (!is_integer($this->actual_price))
            $this->actual_price = (int)Community::getAmount($this->actual_price);
        if (!is_integer($this->quantity))
            $this->quantity = (int)Community::getAmount($this->quantity);
        if (in_array($this->type, [EOrder::TYPE_BANBUON, EOrder::TYPE_BANLE])) {
            $qtyLimit = Accessory::where('code', $this->accessaryNumber)->first();
            if ($qtyLimit) {
                $qtyVali = 'required|numeric|min:1|max:' . $qtyLimit->quantity;
            } else {
                $qtyVali = 'required|numeric|min:1';
            }
            $codeValidator = ['required', Rule::exists('accessories', 'code')];
            $validator = [
                'accessaryNumber' => $codeValidator,
                'quantity' => $qtyVali,
                'listed_price' => 'required|numeric|min:1|max:9999999999',
                'actual_price' => 'required|numeric|min:1|max:9999999999',
                'vat_price' => 'required|numeric|min:1|max:9999999999',
            ];
        } else {
            $codeValidator = ['required'];
            $validator = [
                'accessaryNumber' => $codeValidator,
                'listed_price' => 'required|numeric|min:1|max:9999999999',
                'quantity' => 'required|numeric|min:1'
            ];
        }
        if ($this->code == 'HVN' && $this->type == EOrder::TYPE_NHAP) {
            $codeInVali = DB::table('hms_part_order_plan_detail')->where('part_no', '!=', '')->pluck('part_no')->toArray();
            $codeValidator = ['required', Rule::in($codeInVali)];
            $validator = [
                'accessaryNumber' => $codeValidator,
            ];
        }
        $this->validate($validator, [
            'accessaryNumber.in' => 'Mã phụ tùng không tồn tại trong kế hoạch',
            'accessaryNumber.required' => 'Mã phụ tùng bắt buộc',
            'accessaryNumber.exists' => 'Mã phụ tùng không tồn tại',
            'quantity.required' => 'Số lượng bắt buộc',
            'quantity.max' => 'Số lượng vượt quá trong kho',
            'quantity.min' => 'Số lượng phải lớn hơn 0',
            'listed_price.required' => 'Giá niêm yết bắt buộc',
            'listed_price.min' => 'Giá niêm yết phải lớn hơn 0',
            'listed_price.max' => 'Giá niêm yết không quá 10000000000',
            'actual_price.required' => 'Giá in hóa đơn bắt buộc ',
            'actual_price.min' => 'Giá in hóa đơn phải lớn hơn 0',
            'vat_price.required' => 'Giá thực tế bắt buộc',
            'vat_price.min' => 'Giá thực tế phải lớn hơn 0',
        ], []);
        $order_detail = new OrderDetail();
        $order_detail->code = $this->accessaryNumber;
        if ($this->code == 'HVN' && $this->type == EOrder::TYPE_NHAP) {
            $order_detail->quantity = (int)Community::getAmount($this->quantityRequest);
            $order_detail->supplier_type = EOrderDetail::SUPPLIER_TYPE;
            $order_detail->order_number = $this->order_number;
            $order_detail->actual_price = (int)Community::getAmount($this->listed_price);
        } else {
            $order_detail->quantity = (int)Community::getAmount($this->quantity);
            $order_detail->supplier_type = 0;
        }
        if ($this->type == EOrder::TYPE_NHAP)
            $order_detail->buy_date = $this->buyDate ?? null;
        $order_detail->status = EOrderDetail::STATUS_SAVE_DRAFT;
        $order_detail->name = $this->name;
        $order_detail->admin_id = auth()->id();
        $order_detail->category = EOrderDetail::CATE_ACCESSORY;
        $order_detail->type = $this->type;
        if (in_array($this->type, [EOrderDetail::TYPE_BANBUON, EOrderDetail::TYPE_BANLE])) {
            $order_detail->listed_price = (int)Community::getAmount($this->listed_price);
            $order_detail->vat_price = (int)Community::getAmount($this->vat_price);
            $order_detail->actual_price = (int)Community::getAmount($this->actual_price);
        } else {
            $order_detail->listed_price = (int)Community::getAmount($this->listed_price);
            $order_detail->actual_price = $order_detail->listed_price;
        }
        $order_detail->save();
        $this->resetInput();
        $this->emit('setBtnAddStatus');
        $this->addStatus = false;
        $this->render();
    }

    public function editItem($id)
    {
        $this->statusInput = true;
        $this->itemEditID = $id;
        $order_detail_edit = OrderDetail::findOrFail($id);
        $this->accessaryNumberEdit = $order_detail_edit->code;
        $this->quantityEdit = number_format($order_detail_edit->quantity);
        $this->orderNumberEdit = $this->order_number;
        $this->nameEdit = $order_detail_edit->name;
        $this->quantityRequestedEdit = number_format($order_detail_edit->quantity);
        $this->buyDateEdit = $order_detail_edit->buy_date;
        $this->vat_priceEdit = number_format($order_detail_edit->vat_price);
        $this->actual_priceEdit = number_format($order_detail_edit->actual_price);
        $this->listed_priceEdit = number_format($order_detail_edit->listed_price);
        $this->emit('resetBtnAddStatus');
    }

    public function updateItem($id)
    {
        $order_detail_edit = OrderDetail::findOrFail($id);
        $this->statusInput = true;
        $this->listed_priceEdit = (int)Community::getAmount($this->listed_priceEdit);
        $this->vat_priceEdit = (int)Community::getAmount($this->vat_priceEdit);
        $this->actual_priceEdit = (int)Community::getAmount($this->actual_priceEdit);
        $this->quantityEdit = (int)Community::getAmount($this->quantityEdit);
        if (in_array($this->type, [EOrder::TYPE_BANBUON, EOrder::TYPE_BANLE])) {
            $qtyLimit = Accessory::where('code', $this->accessaryNumberEdit)->first();
            if ($qtyLimit) {
                if ($order_detail_edit->status == EOrderDetail::STATUS_SAVED) {
                    $qtyVali = 'required|numeric|min:1|max:' . ($qtyLimit->quantity + $order_detail_edit->quantity);
                } else {
                    $qtyVali = 'required|numeric|min:1|max:' . ($qtyLimit->quantity);
                }
            } else {
                $qtyVali = 'required|numeric';
            }
            $codeValidator = ['required', Rule::exists('accessories', 'code')];
            $validator = [
                'accessaryNumberEdit' => $codeValidator,
                'quantityEdit' => $qtyVali,
                'listed_priceEdit' => 'required|numeric|min:1|max:9999999999',
                'actual_priceEdit' => 'required|numeric|min:1|max:9999999999',
                'vat_priceEdit' => 'required|numeric|min:1|max:9999999999',
            ];
        } else {
            $codeValidator = ['required'];
            $validator = [
                'accessaryNumberEdit' => $codeValidator,
            ];
        }
        if ($this->code == 'HVN' && $this->type == EOrder::TYPE_NHAP) {
            $codeInVali = DB::table('hms_part_order_plan_detail')->where('part_no', '!=', '')->pluck('part_no')->toArray();
            $codeValidator = ['required', Rule::in($codeInVali)];
            $validator = [
                'accessaryNumberEdit' => $codeValidator,
            ];
        }
        $this->validate($validator, [
            'accessaryNumberEdit.exists' => 'Mã phụ tùng không tồn tại',
            'accessaryNumberEdit.in' => 'Mã phụ tùng không tồn tại trong kế hoạch',
            'quantityEdit.required' => 'Số lượng bắt buộc',
            'quantityEdit.max' => 'Số lượng vượt quá trong kho',
            'quantityEdit.min' => 'Số lượng phải lớn hơn 0',
            'listed_priceEdit.required' => 'Giá niêm yết bắt buộc',
            'listed_priceEdit.min' => 'Giá niêm yết phải lớn hơn 0',
            'listed_priceEdit.max' => 'Giá niêm yết không quá 10000000000',
            'actual_priceEdit.required' => 'Giá in hóa đơn bắt buộc ',
            'actual_priceEdit.min' => 'Giá in hóa đơn phải lớn hơn 0',
            'vat_priceEdit.required' => 'Giá thực tế bắt buộc',
            'vat_priceEdit.min' => 'Giá thực tế phải lớn hơn 0',
        ], []);
        $order_detail = OrderDetail::findOrFail($id);
        $order_detail->code = $this->accessaryNumberEdit;
        $quantity_update = (int)Community::getAmount($this->quantityEdit) - $order_detail->quantity;
        $order_detail->quantity = (int)Community::getAmount($this->quantityEdit);
        $order_detail->buy_date = $this->buyDateEdit ? $this->buyDateEdit : null;
        $order_detail->name = $this->nameEdit;
        $order_detail->admin_id = auth()->id();
        if (in_array($this->type, [EOrder::TYPE_BANBUON, EOrder::TYPE_BANLE])) {
            $order_detail->vat_price = (int)Community::getAmount($this->vat_priceEdit);
            $order_detail->actual_price = (int)Community::getAmount($this->actual_priceEdit);
        } else {
            $order_detail->listed_price = (int)Community::getAmount($this->listed_priceEdit);
            $order_detail->actual_price = $order_detail->listed_price;
        }
        $order_detail->save();
        if ($this->order_id && $quantity_update != 0 && $order_detail->status == EOrderDetail::STATUS_SAVED) {
            $accessoryUpdate = Accessory::where('code', $order_detail->code)->first();
            if ($this->type == 3) {
                $accessoryUpdate->quantity = $accessoryUpdate->quantity + $quantity_update;
            } else {
                $accessoryUpdate->quantity = $accessoryUpdate->quantity - $quantity_update;
            }
            $accessoryUpdate->save();
            $order = Order::findOrFail($this->order_id);
            $order->total_items = $order->totalItem();
            $order->total_money = $order->totalPriceByType();
            $order->save();
        }
        $this->emit('setBtnAddStatus');
        $this->itemEditID = '';
        $this->render();
    }

    public function cancel()
    {
        $this->itemEditID = '';
        $this->emit('setBtnAddStatus');
    }

    public function delete($id)
    {
        $this->itemEditID = '';
        $order_detail = OrderDetail::findOrFail($id);
        $order_detail->delete();
        if ($this->order_id && $order_detail->status == EOrderDetail::STATUS_SAVED) {
            $accessoryUpdate = Accessory::where('code', $order_detail->code)->first();
            if ($this->type == 3) {
                $accessoryUpdate->quantity = $accessoryUpdate->quantity - $order_detail->quantity;
            } else {
                $accessoryUpdate->quantity = $accessoryUpdate->quantity + $order_detail->quantity;
            }
            $accessoryUpdate->save();
            $order = Order::findOrFail($this->order_id);
            $order->total_items = $order->totalItem();
            $order->total_money = $order->totalPriceByType();
            $order->save();
        }
    }

    public function resetInput()
    {
        $this->accessaryNumber = '';
        $this->quantity = '';
        $this->buyDate = Carbon::now()->format('Y-m-d');
        $this->vat_price = '';
        $this->actual_price = '';
        $this->listed_price = '';
        $this->total = '';
        $this->name = '';
        $this->backQuantity = '';
        $this->quantityRequest = '';

    }

    public function addInputRow($code)
    {
        $this->buyDate = Carbon::now()->format('Y-m-d');
        $accessory = Accessory::where('code', $code)->first();
        if ($this->type == EOrder::TYPE_NHAP) {
            if ($this->code == 'HVN') {

                $order_detail_plan = DB::table('hms_part_order_plan_detail')->where('order_number', $this->order_number)->where('part_no', $code)->first();
                if ($order_detail_plan) {
                    $this->addStatus = true;
                    $this->emit('resetBtnAddStatus');
                    $this->statusInput = false;
                    $this->accessaryNumber = $order_detail_plan->part_no;
                    $this->name = $order_detail_plan->part_description;
                    $this->quantityRequest = (int)Community::getAmount($order_detail_plan->quantity_requested);
                    $this->backQuantity = (int)Community::getAmount($order_detail_plan->back_order_qty);
                    $this->listed_price = numberFormat((int)Community::getAmount($order_detail_plan->dnp));
                }
            } else {
                $this->addStatus = true;
                $this->emit('resetBtnAddStatus');
                $this->accessaryNumber = $code;
            }
        } elseif (in_array($this->type, [EOrder::TYPE_BANBUON, EOrder::TYPE_BANLE])) {
            $this->addStatus = true;
            $this->emit('resetBtnAddStatus');
            if ($accessory) {
                $this->accessaryNumber = $accessory->code;
                $this->name = $accessory->name;
                $this->listed_price = numberFormat($accessory->price);
                $this->actual_price = $this->listed_price;
                $this->vat_price = $this->listed_price;
            }
        }
    }
}
