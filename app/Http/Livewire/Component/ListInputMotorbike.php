<?php

namespace App\Http\Livewire\Component;

use App\Http\Livewire\Base\BaseLive;
use App\Models\Motorbike;
use App\Models\Order;
use App\Models\Mtoc;
use App\Models\Supplier;
use App\Models\OrderDetail;
use App\Models\HMSReceivePlan;
use App\Service\Community;
use Carbon\Carbon;
use Livewire\Component;
use Route;
use Illuminate\Support\Facades\DB;
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Enum\EMotorbike;

class ListInputMotorbike extends BaseLive
{
    public $addStatus = false;
    public $type;
    public $chassic, $engine, $model, $color, $modelList, $modelType, $price, $buyDate, $totalEdit = 0, $vat_price, $actual_price, $listed_price;
    public $chassicEdit, $engineEdit, $modelEdit, $modelListEdit, $modelTypeEdit, $colorEdit, $priceEdit, $buyDateEdit, $total = 0, $vat_priceEdit, $actual_priceEdit, $listed_priceEdit;
    public $status = false;
    public $itemEditID;
    public $autoFill;
    public $order_id;
    public $currentDay;
    public $isHVN = false;
    public $supplierCode;
    public $updateEngine;
    public $updateVat_price;
    public $updateActual_price;

    protected $listeners = [
        'addNew',
        'storeOrder',
        'loadListInput' => 'render',
        'checkSupplier',
        'setbuyDate',
        'setbuyDateEdit',
        'addInputRow',
        'updateCode',
        'delete',
        'deleteAll'
    ];

    public function mount()
    {
        $this->currentDay = date('Y-m-d');
        $this->buyDate = $this->currentDay;
        if ($this->type != EOrder::TYPE_NHAP) {
            $this->isHVN = true;
        } elseif (isset($_GET['id'])) {
            $supplier_id = Order::findOrFail($_GET['id'])->supplier_id;
            if ($supplier_id) {
                $this->isHVN = Supplier::find($supplier_id)->code == 'HVN';
            }
        }
        OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)
            ->where('category', EOrderDetail::CATE_MOTORBIKE)
            ->where('type', $this->type)->where('admin_id', auth()->id())->delete();

    }

    public function render()
    {
        if (isset($_GET['id'])) {
            $this->order_id = $_GET['id'];
        }
        if (isset($_GET['show'])) {
            $this->status = true;
        }
        if ($this->order_id) {
            $query = OrderDetail::query();
            if ($this->status)
                $data = $query->where('order_id', $this->order_id)->get();
            else {
                $query->where('order_id', $this->order_id);
                $data = $query->orWhere(function ($query) {
                    $query->where('status', EOrderDetail::STATUS_SAVE_DRAFT)
                        ->where('category', EOrderDetail::CATE_MOTORBIKE)
                        ->where('type', $this->type)->where('admin_id', auth()->id());
                })->get();
            }
        } else {
            $data = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)
                ->where('category', EOrderDetail::CATE_MOTORBIKE)
                ->where('type', $this->type)->where('admin_id', auth()->id())->get();
        }

        $totalMotorbike = $data->count();

        $this->updateVat_price = $this->vat_price;
        $this->updateActual_price = $this->actual_price;
        $engineList = [];
        $engineEditList = [];
        $chassicList = [];
        $chassicEditList = [];
        if ($this->type == EOrder::TYPE_NHAP) {
            $chassicHasBuyList = OrderDetail::query()->whereNotNull('chassic_no')->pluck('chassic_no')->unique()->toArray();
            $chassicList = HMSReceivePlan::whereNotIn('chassic_no', $chassicHasBuyList)->pluck('chassic_no')->unique();
            $engineHasBuyList = OrderDetail::query()->whereNotNull('engine_no')->pluck('engine_no')->unique()->toArray();
            $engineList = HMSReceivePlan::whereNotIn('engine_no', $engineHasBuyList)->pluck('engine_no')->unique();

            $chassicEditHasBuyList = OrderDetail::query()->whereNotNull('chassic_no')->pluck('chassic_no')->unique()->toArray();
            $chassicEditList = HMSReceivePlan::whereNotIn('chassic_no', $chassicEditHasBuyList)->pluck('chassic_no')->unique();
            $isContainChassic = $chassicEditList->contains(function ($value, $key) {
                return $value == $this->chassicEdit;
            });
            if (!$isContainChassic) {
                $chassicEditList->push($this->chassicEdit);
            }


            $engineEditHasBuyList = OrderDetail::query()->whereNotNull('engine_no')->pluck('engine_no')->unique()->toArray();
            $engineEditList = HMSReceivePlan::whereNotIn('engine_no', $engineEditHasBuyList)->pluck('engine_no')->unique();
            $isContainEngine = $engineEditList->contains(function ($value, $key) {
                return $value == $this->engineEdit;
            });
            if (!$isContainEngine) {
                $engineEditList->push($this->engineEdit);
            }
        } else {

            //$engineList = Motorbike::where('status', EMotorbike::NEW_INPUT)->where('is_out', EMotorbike::NOT_OUT)->pluck('engine_no')->unique();
            $engineList = Motorbike::where('is_out', EMotorbike::NOT_OUT)
            ->whereNull('customer_id')
            ->where('status', '!=', 1)
            ->pluck('engine_no')->unique();
            $chassicList = Motorbike::where('is_out', EMotorbike::NOT_OUT)
            ->whereNull('customer_id')
            ->where('status', '!=', 1)
            ->pluck('chassic_no')->unique();
            //TUDN remove where('status', EMotorbike::NEW_INPUT)->
            $engineEditList = Motorbike::where('is_out', EMotorbike::NOT_OUT)
            ->whereNull('customer_id')
            ->where('status', '!=', 1)
            ->pluck('engine_no');
            $isContainEngine = $engineEditList->contains(function ($value, $key) {
                return $value == $this->engineEdit;
            });
            if (!$isContainEngine) {
                $engineEditList->push($this->engineEdit);
            }
            //TUDN remove where('status', EMotorbike::NEW_INPUT)->
            $chassicEditList = Motorbike::where('is_out', EMotorbike::NOT_OUT)
            ->whereNull('customer_id')
            ->where('status', '!=', 1)
            ->pluck('chassic_no');
            $isContainChassic = $chassicEditList->contains(function ($value, $key) {
                return $value == $this->chassicEdit;
            });
            if (!$isContainChassic) {
                $chassicEditList->push($this->chassicEdit);
            }

            // Nếu bán ra sẽ lấy giá đề xuất
            $mtocMotorbike = HMSReceivePlan::where('chassic_no', $this->chassic)->where('engine_no', $this->engine)->first();
            if ($mtocMotorbike) {
                $priceSuggest = Mtoc::where('model_code', $mtocMotorbike->model_code)
                    ->where('type_code', $mtocMotorbike->type_code)
                    ->where(function ($q) use ($mtocMotorbike) {
                        if (empty($mtocMotorbike->option_code)) {
                            $q->whereNull('option_code');
                        } else {
                            $q->where('option_code', $mtocMotorbike->option_code);
                        }
                    })
                    ->where('color_code', $mtocMotorbike->color_code)
                    ->select('suggest_price')->first();
                if ($priceSuggest) {
                    $this->price =  $priceSuggest->suggest_price;
                    $this->vat_price =$this->price;
                    $this->actual_price = $this->price;
                    $this->listed_price = $this->price;
                }else{
                    $this->price = 0;
                    // $this->vat_price =$this->price;
                    // $this->actual_price = $this->price;
                    $this->listed_price = $this->price;
                }
                $this->model = $mtocMotorbike->model_name;
                $this->modelList = $mtocMotorbike->model_category;
                $this->modelType = $mtocMotorbike->model_type;
                $this->color = $mtocMotorbike->color;
            }
            $mtocMotorbikeEdit = HMSReceivePlan::where('chassic_no', $this->chassicEdit)->where('engine_no', $this->engineEdit)->first();
            if ($mtocMotorbikeEdit) {
                $priceSuggestEdit = Mtoc::where('model_code', $mtocMotorbikeEdit->model_code)
                    ->where('type_code', $mtocMotorbikeEdit->type_code)
                    ->where('option_code', $mtocMotorbikeEdit->option_code)
                    ->where('color_code', $mtocMotorbikeEdit->color_code)
                    ->select('suggest_price')->first();
                if ($priceSuggestEdit != null) {
                    $this->priceEdit =  $priceSuggestEdit->suggest_price;
                    $this->actual_priceEdit = $this->priceEdit;
                    $this->listed_priceEdit = $this->priceEdit;
                }
            }
        }

        $this->dispatchBrowserEvent('setSelect2');
        $this->dispatchBrowserEvent('setDatePickerNow');
        $this->dispatchBrowserEvent('setDatePickerUpdate');
        if ($this->itemEditID && $this->buyDateEdit) {
            $this->dispatchBrowserEvent('setDatePickerEdit', ['date' => $this->buyDateEdit]);
            $this->dispatchBrowserEvent('setDatePickerEdit2', ['date' => $this->buyDateEdit]);
        }
        return view('livewire.component.list-input-motorbike', compact('data', 'chassicList', 'engineList', 'chassicEditList', 'engineEditList', 'totalMotorbike'));
    }

    public function setbuyDate($time)
    {
        $this->buyDate = date('Y-m-d', strtotime($time['buyDate']));
    }

    public function setbuyDateEdit($time)
    {
        $this->buyDateEdit = date('Y-m-d', strtotime($time['buyDateEdit']));
    }

    public function updatedChassic()
    {
        if ($this->type != EOrder::TYPE_NHAP) {
            $motorbike = Motorbike::where('chassic_no', $this->chassic)->where('is_out', EMotorbike::NOT_OUT)->first();
            if ($motorbike) {
                $this->autoFill = true;
                $this->engine = $motorbike->engine_no;
                $this->model = $motorbike->model_code;
                $this->modelType = $motorbike->model_type;
                $this->modelList = $motorbike->model_list;
                $this->color = $motorbike->color;
                $this->price = $motorbike->price;
                $this->buyDate = $motorbike->buy_date;
                $this->vat_price = $this->price;
                $this->actual_price = $this->price;
                $this->listed_price = $this->price;
            } else {
                $this->resetData();
                $this->autoFill = false;
            }
        } else {
            $hmsReceivePlan = HMSReceivePlan::where('chassic_no', $this->chassic)->first();
            if ($hmsReceivePlan) {
                $this->autoFill = true;
                $this->engine =  $hmsReceivePlan->engine_no;
                $this->model = $hmsReceivePlan->model_name;
                $this->modelType = $hmsReceivePlan->model_type;
                $this->modelList = $hmsReceivePlan->model_category;
                $this->color = $hmsReceivePlan->color;
                $this->price = Community::getAmount($hmsReceivePlan->payment_amount_by_dealer);
            } else {
                $this->resetData();
                $this->autoFill = false;
            }
        }
    }

    //TUDN thu commit lai check auto update cicd
    public function updatedEngine()
    {
        if ($this->type != EOrder::TYPE_NHAP) {
            $motorbike = Motorbike::where('engine_no', $this->engine)->where('is_out', EMotorbike::NOT_OUT)->first();
            if ($motorbike) {
                $this->autoFill = true;
                $this->chassic = $motorbike->chassic_no;
                $this->model = $motorbike->model_code;
                $this->modelType = $motorbike->model_type;
                $this->modelList = $motorbike->model_list;
                $this->color = $motorbike->color;
                $this->price = $motorbike->price;
                $this->buyDate = $motorbike->buy_date;
                if ($this->updateVat_price == null) {
                    $this->vat_price = $this->price;
                } else {
                    $this->vat_price = $this->updateVat_price;
                }
                if ($this->updateActual_price != null) {
                    $this->actual_price = $this->updateActual_price;
                } else {
                    $this->actual_price = $this->price;
                }
                $this->listed_price = $this->price;
            } else {
                $this->resetData();
                $this->autoFill = false;
            }
        } else {
            $hmsReceivePlan = HMSReceivePlan::where('engine_no', $this->engine)->first();
            if ($hmsReceivePlan) {
                $this->autoFill = true;
                $this->chassic = $hmsReceivePlan->chassic_no;
                $this->model = $hmsReceivePlan->model_name;
                $this->modelType = $hmsReceivePlan->model_type;
                $this->modelList = $hmsReceivePlan->model_category;
                $this->color = $hmsReceivePlan->color;
                $this->price = Community::getAmount($hmsReceivePlan->payment_amount_by_dealer);
            } else {
                $this->resetData();
                $this->autoFill = false;
            }
        }
    }

    public function updatedChassicEdit()
    {
        if ($this->type != EOrder::TYPE_NHAP) {
            $motorbike = Motorbike::where('chassic_no', $this->chassicEdit)->where('is_out', EMotorbike::NOT_OUT)->first();
            if ($motorbike) {
                $this->autoFill = true;
                $this->engineEdit = $motorbike->engine_no;
                $this->modelEdit = $motorbike->model_code;
                $this->modelTypeEdit = $motorbike->model_type;
                $this->modelListEdit = $motorbike->model_list;
                $this->colorEdit = $motorbike->color;
                $this->priceEdit = $motorbike->price;
                $this->buyDateEdit = $motorbike->buy_date;
                $this->vat_priceEdit = $this->priceEdit;
                $this->actual_priceEdit = $this->priceEdit;
                $this->listed_priceEdit = $this->priceEdit;
            } else {
                $this->resetDataEdit();
                $this->autoFill = false;
            }
        } else {
            $hmsReceivePlan = HMSReceivePlan::where('chassic_no', $this->chassicEdit)->first();
            if ($hmsReceivePlan) {
                $this->autoFill = true;
                $this->engineEdit = $hmsReceivePlan->engine_no;
                $this->modelEdit = $hmsReceivePlan->model_name;
                $this->modelTypeEdit = $hmsReceivePlan->model_type;
                $this->modelListEdit = $hmsReceivePlan->model_category;
                $this->colorEdit = $hmsReceivePlan->color;
                $this->priceEdit = Community::getAmount($hmsReceivePlan->payment_amount_by_dealer);
            } else {
                $this->resetDataEdit();
                $this->autoFill = false;
            }
        }
    }

    public function updatedEngineEdit()
    {

        if ($this->type != EOrder::TYPE_NHAP) {
            $motorbike = Motorbike::where('engine_no', $this->engineEdit)->where('is_out', EMotorbike::NOT_OUT)->first();
            if ($motorbike) {
                $this->autoFill = true;
                $this->chassicEdit = $motorbike->chassic_no;
                $this->modelEdit = $motorbike->model_code;
                $this->modelTypeEdit = $motorbike->model_type;
                $this->modelListEdit = $motorbike->model_list;
                $this->colorEdit = $motorbike->color;
                $this->priceEdit = $motorbike->price;
                $this->buyDateEdit = $motorbike->buy_date;
                $this->vat_priceEdit = $this->priceEdit;
                $this->actual_priceEdit = $this->priceEdit;
                $this->listed_priceEdit = $this->priceEdit;
            } else {
                $this->resetDataEdit();
                $this->autoFill = false;
            }
        } else {
            $hmsReceivePlan = HMSReceivePlan::where('engine_no', $this->engineEdit)->first();
            if ($hmsReceivePlan) {
                $this->autoFill = true;
                $this->chassicEdit = $hmsReceivePlan->chassic_no;
                $this->modelEdit = $hmsReceivePlan->model_name;
                $this->modelTypeEdit = $hmsReceivePlan->model_type;
                $this->modelListEdit = $hmsReceivePlan->model_category;
                $this->colorEdit = $hmsReceivePlan->color;
                $this->priceEdit = Community::getAmount($hmsReceivePlan->payment_amount_by_dealer);
            } else {
                $this->resetDataEdit();
                $this->autoFill = false;
            }
        }
    }

    public function addNew()
    {
        $this->chassic = null;
        $this->engine = null;
        $this->resetData();
        $this->resetValidation();
        $this->addStatus = true;
    }

    public function addItem()
    {
        $this->validate([
            'chassic' => $this->type != EOrder::TYPE_NHAP ? 'required|sale_motor:' . $this->chassic . ',' . $this->engine . '|sold:' . $this->chassic . ',' . $this->engine : 'required|bought:' . $this->chassic . ',' . $this->engine,
            'engine' => 'required',
            'model' => 'required',
            'modelType' => 'required',
            'modelList' => 'required',
            'price' => 'required|integer',
            'vat_price' => $this->type != EOrder::TYPE_NHAP ? 'required' : '',
            'actual_price' => $this->type != EOrder::TYPE_NHAP ? 'required' : '',
            'listed_price' => $this->type == EOrder::TYPE_BANLE ? 'required' : '',
            'buyDate' => $this->type == EOrder::TYPE_NHAP ? 'required|date|before_or_equal:currentDay' : '',
        ], [], [
            'chassic' => 'Số khung',
            'engine' => 'Số máy',
            'model' => 'Tên đời xe',
            'modelType' => 'Danh mục đời xe',
            'modelList' => 'Phân loại đời xe',
            'price' => 'Đơn giá',
            'vat_price' => 'Giá in hóa đơn',
            'actual_price' => 'Giá thực tế',
            'listed_price' => 'Giá niêm yết',
            'buyDate' => 'Ngày nhập',
            'currentDay' => 'ngày hiện tại',
        ]);
        $order_detail = new OrderDetail();
        $order_detail->chassic_no = $this->chassic;
        $order_detail->engine_no = $this->engine;
        $order_detail->model_code = $this->model;
        $order_detail->model_type = $this->modelType;
        $order_detail->model_list = $this->modelList;
        $order_detail->color = $this->color;
        $order_detail->price = Community::getAmount($this->price);
        $order_detail->admin_id = auth()->id();
        $order_detail->status = EOrderDetail::STATUS_SAVE_DRAFT;
        $order_detail->category = EOrderDetail::CATE_MOTORBIKE;
        $order_detail->type = $this->type;
        if (in_array($this->type, [EOrder::TYPE_BANBUON, EOrder::TYPE_BANLE])) {
            $order_detail->vat_price = Community::getAmount($this->vat_price);
            $order_detail->actual_price = Community::getAmount($this->actual_price);
        }
        if ($this->type == EOrder::TYPE_BANLE) {
            $order_detail->listed_price = Community::getAmount($this->listed_price);
        }
        if ($this->type == EOrder::TYPE_NHAP) {
            $order_detail->buy_date = $this->buyDate;
        }
        $order_detail->product_id = Motorbike::where('chassic_no', $this->chassic)
            ->where('engine_no', $this->engine)
            ->where('is_out', EMotorbike::NOT_OUT)->first()->id ?? null;
        $order_detail->save();
        Motorbike::where('chassic_no', $this->chassic)
            ->where('engine_no', $this->engine)
            ->where('is_out', EMotorbike::NOT_OUT)
            ->update(['status' => EMotorbike::PROCESS]);
        $this->autoFill = false;
        $this->addStatus = false;
        $this->resetData();
        $this->emit('setBtnAddStatus');
    }

    public function editItem($id)
    {
        $this->resetValidation();
        $this->itemEditID = $id;
        $order_detail = OrderDetail::findOrFail($id);
        $this->chassicEdit = $order_detail->chassic_no;
        $this->engineEdit = $order_detail->engine_no;
        $this->modelEdit = $order_detail->model_code;
        $this->modelTypeEdit = $order_detail->model_type;
        $this->modelListEdit = $order_detail->model_list;
        $this->colorEdit = $order_detail->color;
        $this->priceEdit = $order_detail->price;
        $this->buyDateEdit = $order_detail->buy_date;
        $this->vat_priceEdit = number_format($order_detail->vat_price);
        $this->actual_priceEdit = number_format($order_detail->actual_price);
        $this->listed_priceEdit = number_format($order_detail->listed_price);
    }

    public function updateItem($id)
    {
        $this->validate([
            'chassicEdit' => $this->type != EOrder::TYPE_NHAP ? 'required|sale_motor:' . $this->chassicEdit . ',' . $this->engineEdit . '|sold:' . $this->chassicEdit . ',' . $this->engineEdit . ',' . $id : 'required|bought:' . $this->chassicEdit . ',' . $this->engineEdit . ',' . $id,
            'engineEdit' => 'required',
            'modelEdit' => 'required',
            'modelTypeEdit' => 'required',
            'modelListEdit' => 'required',
            'priceEdit' => 'required|integer',
            'vat_priceEdit' => $this->type != EOrder::TYPE_NHAP ? 'required' : '',
            'actual_priceEdit' => $this->type != EOrder::TYPE_NHAP ? 'required' : '',
            'listed_priceEdit' => $this->type == EOrder::TYPE_BANLE ? 'required' : '',
            'buyDateEdit' => $this->type == EOrder::TYPE_NHAP ? 'required|date|before_or_equal:currentDay' : '',
        ], [], [
            'chassicEdit' => 'Số khung',
            'engineEdit' => 'Số máy',
            'modelEdit' => 'Tên đời xe',
            'modelTypeEdit' => 'Danh mục đời xe',
            'modelListEdit' => 'Phân loại đời xe',
            'priceEdit' => 'Đơn giá',
            'vat_priceEdit' => 'Giá in hóa đơn',
            'actual_priceEdit' => 'Giá thực tế',
            'listed_priceEdit' => 'Giá niêm yết',
            'buyDateEdit' => 'Ngày nhập',
            'currentDay' => 'ngày hiện tại',
        ]);

        $order_detail = OrderDetail::findOrFail($id);
        $order_detail->chassic_no = $this->chassicEdit;
        $order_detail->engine_no = $this->engineEdit;
        $order_detail->model_code = $this->modelEdit;
        $order_detail->model_list = $this->modelListEdit;
        $order_detail->model_type = $this->modelTypeEdit;
        $order_detail->color = $this->colorEdit;
        $order_detail->price = Community::getAmount($this->priceEdit);
        $order_detail->admin_id = auth()->id();
        if (in_array($this->type, [EOrder::TYPE_BANBUON, EOrder::TYPE_BANLE])) {
            $order_detail->vat_price = Community::getAmount($this->vat_priceEdit);
            $order_detail->actual_price = Community::getAmount($this->actual_priceEdit);
        }
        if ($this->type == EOrder::TYPE_BANLE) {
            $order_detail->listed_price = Community::getAmount($this->listed_priceEdit);
        }
        if ($this->type == EOrder::TYPE_NHAP) {
            $order_detail->buy_date = $this->buyDateEdit;
            Motorbike::where('chassic_no', $this->chassicEdit)->where('engine_no', $this->engineEdit)->update(['buy_date' => $this->buyDateEdit]);
        }
        $order_detail->save();
        $this->itemEditID = '';
    }

    public function cancel()
    {
        $this->itemEditID = '';
    }

    public function cancelAdd()
    {
        $this->autoFill = false;
        $this->addStatus = false;
        $this->resetData();
        $this->emit('setBtnAddStatus');
    }

    public function delete($id)
    {
        $this->itemEditID = '';
        $order_detail = OrderDetail::findOrFail($id);
        if ($order_detail->status == EOrderDetail::STATUS_SAVED) {
            if ($this->type == EOrder::TYPE_NHAP) {
                $order_detail->motorbike()->delete();
            } else {
                $order_detail->motorbike()->update([
                    'customer_id' => null,
                    'status' => EMotorbike::NEW_INPUT,
                ]);
            }
        } elseif ($order_detail->status == EOrderDetail::STATUS_SAVE_DRAFT && $this->type != EOrder::TYPE_NHAP) {
            $order_detail->motorbike()->update([
                'status' => EMotorbike::NEW_INPUT,
            ]);
        }
        $order_detail->delete();
        $countOrderDetails = OrderDetail::where('order_id', $this->order_id)->count();
        if (!$countOrderDetails && $this->order_id) {
            Order::findOrFail($this->order_id)->delete();
        }
        return redirect()->route('motorbikes.orders.index');
    }

    public function deleteAll($detalId, $orderId)
    {
        $this->itemEditID = '';
        $order_detail = OrderDetail::findOrFail($detalId);
        $order = Order::findOrFail($orderId);
        if ($order_detail->status == EOrderDetail::STATUS_SAVED) {
            if ($this->type == EOrder::TYPE_NHAP) {
                $order_detail->motorbike()->delete();
            } else {
                $order_detail->motorbike()->update([
                    'customer_id' => null,
                    'status' => EMotorbike::NEW_INPUT,
                ]);
            }
        } elseif ($order_detail->status == EOrderDetail::STATUS_SAVE_DRAFT && $this->type != EOrder::TYPE_NHAP) {
            $order_detail->motorbike()->update([
                'status' => EMotorbike::NEW_INPUT,
            ]);
        }
        $order_detail->delete();
        $order->delete();

        return redirect()->route('motorbikes.orders.index');
    }

    public function resetDataEdit()
    {
        $this->modelEdit = null;
        $this->modelTypeEdit = null;
        $this->modelListEdit = null;
        $this->colorEdit = null;
        $this->priceEdit = null;
        $this->vat_priceEdit = null;
        $this->listed_priceEdit = null;
        $this->actual_priceEdit = null;
        $this->updateVat_price = null;
        $this->updateActual_price = null;
    }

    public function resetData()
    {
        $this->model = null;
        $this->modelType = null;
        $this->modelList = null;
        $this->color = null;
        $this->price = null;
        $this->vat_price = null;
        $this->listed_price = null;
        $this->actual_price = null;
    }

    public function checkSupplier($supplierCode)
    {
        $this->isHVN = trim($supplierCode) == 'HVN' || $this->type != EOrder::TYPE_NHAP;
    }

    public function updateCode($code)
    {
        $this->supplierCode = $code;
    }

    public function addInputRow($code)
    {
        $this->buyDate = Carbon::now()->format('Y-m-d');
        $motorbike = Motorbike::where('chassic_no', $code)->where('is_out', EMotorbike::NOT_OUT)->first();

        if ($this->type == EOrder::TYPE_NHAP) {
            if (trim($this->supplierCode) == 'HVN') {
                $this->isHVN = true;
                if (count(explode("&", $code)) != 2) {
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => "Barcode không đúng"]);
                    return;
                }
                $lotNumber = trim(explode("&", $code)[0]);
                $headCode = trim(explode("&", $code)[1]);
                OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)
                    ->where('category', EOrderDetail::CATE_MOTORBIKE)
                    ->where('type', $this->type)->where('admin_id', auth()->id())->delete();

                $orderDetailPlan = DB::table('view_hms_receive_plan_detail')
                    ->where('head_code', $headCode)
                    ->where('hvn_lot_number', $lotNumber)
                    ->select('*')
                    ->get();
                if ($orderDetailPlan->count() == 0) {
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => "Không tìm thấy thông tin mã barcode"]);
                    return;
                }
                foreach ($orderDetailPlan as $key => $item) {
                    $isExisted = OrderDetail::where('chassic_no', $item->chassic_no)
                        ->where('engine_no', $item->engine_no)->first();
                    if (!$isExisted) {
                        $order_detail = new OrderDetail();
                        $order_detail->chassic_no = $item->chassic_no;
                        $order_detail->engine_no = $item->engine_no;
                        $order_detail->model_code = $item->model_name;
                        $order_detail->model_type = $item->model_type;
                        $order_detail->model_list = $item->model_category;
                        $order_detail->color = $item->color;
                        $order_detail->price =  $item->DEALER_PRICE; // Community::getAmount($item->payment_amount_by_dealer);
                        $order_detail->admin_id = auth()->id();
                        $order_detail->status = EOrderDetail::STATUS_SAVE_DRAFT;
                        $order_detail->category = EOrderDetail::CATE_MOTORBIKE;
                        $order_detail->type = EOrder::TYPE_NHAP;
                        $order_detail->product_id = null;
                        $order_detail->buy_date = Carbon::now()->format('Y-m-d');
                        $order_detail->save();
                    }
                }
                // $order_detail_plan = DB::table('hms_receive_plan')->where('chassic_no', $code)->first();
                // if ($order_detail_plan) {
                //     $this->addStatus = true;
                //     $this->emit('resetBtnAddStatus');
                //     $this->chassic = $order_detail_plan->chassic_no;
                //     $this->engine = $order_detail_plan->engine_no;
                //     $this->model = $order_detail_plan->model_name;
                //     $this->modelList = $order_detail_plan->model_category;
                //     $this->modelType = $order_detail_plan->model_type;
                //     $this->color = $order_detail_plan->color;
                //     $this->price = $order_detail_plan->payment_amount_by_dealer;
                // }
            }
        } elseif (in_array($this->type, [EOrder::TYPE_BANBUON, EOrder::TYPE_BANLE])) {
            if ($motorbike) {
                $this->isHVN = false;
                $this->addStatus = true;
                $this->emit('resetBtnAddStatus');
                $this->chassic = $motorbike->chassic_no;
                $this->engine = $motorbike->engine_no;
                $this->model = $motorbike->model_code;
                $this->modelList = $motorbike->model_list;
                $this->modelType = $motorbike->model_type;
                $this->color = $motorbike->color;
                $this->price = $motorbike->price;
                $this->vat_price = $motorbike->price;
                $this->listed_price = $motorbike->price;
                $this->actual_price = $motorbike->price;
            }
        }
    }
}
