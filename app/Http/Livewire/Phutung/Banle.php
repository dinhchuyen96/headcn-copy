<?php

namespace App\Http\Livewire\Phutung;

use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Http\Livewire\Base\BaseLive;
use App\Models\Accessory;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PositionInWarehouse; //tudn add

use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Banle extends BaseLive
{
    public $ward_id, $district_id, $province_id;
    public $type;
    public $code, $name, $address, $phone, $email, $barcode, $identity_code, $transactionDate;
    public $addBtn = true;
    public $statusInputCode = false;
    public $status = false, $order_id;
    protected $listeners = [
        'setBtnAddStatus', 'setAddress', 'addBarCode', 'resetBtnAddStatus',
        'checkinputheader', 'resetInput', 'settransactionDate'
    ];

    public $warehouses = [];
    public $selectwarehouse;
    public $updatestatus = false;
    public $avaibleaccessories = [];

    public $positionWarehouseId;
    public $positionWarehouseList = [];
    public $itempositionid;

    public $chkIsVirtual =false; 
    public $isVirtual = 0;


    public function settransactionDate($time)
    {
        $this->transactionDate = date('Y-m-d', strtotime($time['transactionDate']));
        $this->emit('getTransactionDate', $this->transactionDate);
    }

    public function mount()
    {
        $this->warehouses = Warehouse::all(); // it will get the entire table
        $warehouses = $this->warehouses;
        if (!empty($warehouses)) {
            $this->selectwarehouse = $warehouses[0]->id;
        } else $this->selectwarehouse = 1;


        $m_positionWarehouseList = PositionInWarehouse::where('warehouse_id', $this->selectwarehouse)
            ->get();
        if ($m_positionWarehouseList) {
            $this->positionWarehouseList = $m_positionWarehouseList;
            foreach ($m_positionWarehouseList as $item) {
                //1. get selected
                $this->positionWarehouseId = $item->id;
                break;
            }
        } else $this->positionWarehouseId = 0;

        //Get Avaible accessories
        $this->avaibleaccessories = Accessory::where('warehouse_id', $this->selectwarehouse)
            ->where('position_in_warehouse_id', $this->positionWarehouseId)
            ->get();
        /*
        if($this->avaibleaccessories){
            $this->barcode = $this->avaibleaccessories[0]->code;
        }else{ $this->barcode = "";}
        */
        if (isset($_GET['show'])) {
            $this->status = true;
        }
        if (isset($_GET['id'])) {
            $this->order_id = $_GET['id'];
            $customer_id = Order::find($this->order_id)->customer_id;
            if ($this->order_id) {
                $existedOrder = Order::find($this->order_id);
                $order = Order::leftJoin('suppliers', 'suppliers.id', '=', 'orders.supplier_id')
                    ->where('orders.id', $this->order_id)->first();
             
                if ($order) {
                    $customer = Customer::find($customer_id);
                    $this->code = $customer->code;
                    $this->identity_code = $customer->identity_code;
                    $this->name = $customer->name;
                    $this->address = $customer->address;
                    $this->phone = $customer->phone;
                    $this->email = $customer->email;
                    $this->ward_id = $customer->ward;
                    $this->district_id = $customer->district;
                    $this->province_id = $customer->city;
          
                }
                //get warehouse of this order
                $orderdetail = Orderdetail::where('order_id', $this->order_id)->first();
                if ($orderdetail) {

                    $this->selectwarehouse = $orderdetail->warehouse_id;
                    $this->transactionDate = date('Y-m-d', strtotime($existedOrder->created_at));
                }
            }
        }
    }

    /**
     * handle check or uncheck isVirtual check box
     */
    public function updatedchkIsVirtual()
    {
        $this->isVirtual = ($this->chkIsVirtual) ? 1 : 0;
        $this->emit('setIsVirtualOrderToDetail', $this->isVirtual);
    }


    public function render()
    {

        //set isVirtual to detail
        $this->emit('setIsVirtualOrderToDetail', $this->isVirtual);
        $this->dispatchBrowserEvent('setSelect2');

        $this->avaibleaccessories = Accessory::whereNull('deleted_at')
            ->Select('code', 'name')->distinct()->get();

        $m_positionWarehouseList = PositionInWarehouse::where('warehouse_id', $this->selectwarehouse)
            ->get();
        if (isset($m_positionWarehouseList)) {
            # code...
            $this->positionWarehouseList = $m_positionWarehouseList;
        }

        return view('livewire.phutung.banle');
    }
    public function resetBtnAddStatus()
    {
        $this->addBtn = false;
    }
    public function add()
    {
        $this->addBtn = false;
        $this->emit('addNew');
    }

    public function setBtnAddStatus()
    {
        $this->addBtn = true;
    }




    /**
     * raise event selected ware house complete
     * set value to componnent
     */
    //
    public function updatedbarcode()
    {
        //re render warehouse, get selected werehouse
        if (!empty($this->barcode)) {
            # code...
            $partInfo = Accessory::whereNull('deleted_at')
                ->select(
                    DB::raw('max(quantity) as quantity'),
                    'code',
                    'warehouse_id',
                    'position_in_warehouse_id'
                )
                ->groupBy('code', 'warehouse_id', 'position_in_warehouse_id')
                ->having('code', '=', $this->barcode)
                ->first();
            if (isset($partInfo)) {
                $this->selectwarehouse = $partInfo->warehouse_id;
                $this->positionWarehouseId = $partInfo->position_in_warehouse_id;
            }
        }
    }


    public function updatedselectwarehouse()
    {
        $warehouse_id = $this->selectwarehouse;
        $m_positionWarehouseList = PositionInWarehouse::where('warehouse_id', $warehouse_id)->get();
        if ($m_positionWarehouseList) {
            $this->positionWarehouseList = $m_positionWarehouseList;
            foreach ($m_positionWarehouseList as $item) {
                $this->positionWarehouseId = $item->id;
                    break;
            }
        }
        //Get Avaible accessories
        /*
        $this->avaibleaccessories = Accessory::where('warehouse_id', $this->selectwarehouse)
            ->where('position_in_warehouse_id', $this->positionWarehouseId)
            ->get();
            */
        $this->emit('setwarehouse', $this->selectwarehouse);
    }


    /**
     * Handle change position
     */
    /*
    public function updatedpositionWarehouseId(){
        $this->avaibleaccessories = Accessory::where('warehouse_id', $this->selectwarehouse)
        ->where('position_in_warehouse_id', $this->positionWarehouseId)
        ->get();
    }
    */


    /**
     * create order when user click button tao don
     */
    public function checkinputheader()
    {
        $this->validate([
            'code' => 'required',
            'name' => 'required',
            'phone' => 'required',
        ], [], [
            'code' => 'Mã khách hàng',
            'name' => 'Tên khách hàng',
            'phone' => 'Số điện thoại'
        ]);
    }



    public function save()
    {

        $this->validate([
            'code' => 'required',
            'name' => 'required',
            'phone' => 'required|max:11'
        ], [], [
            'code' => 'Mã khách hàng',
            'name' => 'Tên khách hàng',
            'phone' => 'Số điện thoại',
        ]);
        if ($this->order_id) {
            $detail = Order::find($this->order_id)->details->toArray();
        } else {
            $detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('type', EOrderDetail::TYPE_BANLE)->where('admin_id', auth()->id())->get()->toArray();
        }
        if (count($detail) == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Chưa có phụ tùng nào được nhập']);
        } elseif (!$this->addBtn) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Có bản nháp chưa hoàn thành']);
        } else {

            $customer = Customer::where('code', $this->code)->first();
            if (!$customer) {
                $this->validate([
                    'phone' => 'regex:/^[0-9]+$/i|unique:customers,phone',
                ], [], [
                    'phone' => 'Số điện thoại',
                ]);
                $newCustomer = new Customer();
                $newCustomer->code = $this->code;
                $newCustomer->phone = $this->phone;
                $newCustomer->email = $this->email;
                $newCustomer->name = $this->name;
                $newCustomer->identity_code = $this->identity_code;
                $newCustomer->address = $this->address;
                $newCustomer->city = $this->province_id;
                $newCustomer->district = $this->district_id;
                $newCustomer->ward = $this->ward_id;
                $newCustomer->save();
                if ($this->order_id) {
                    $order = Order::findOrFail($this->order_id);
                } else {
                    $order = new Order();
                }
                $order->admin_id = auth()->id();
                $order->category = EOrder::CATE_ACCESSORY;
                $order->order_type = EOrder::ORDER_TYPE_SELL;
                $order->type = EOrder::TYPE_BANLE;
                $order->status = EOrder::STATUS_UNPAID;
                $order->customer_id = $newCustomer->id;
                $order->save();
                //list phu tung
                $order_detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('type', EOrderDetail::TYPE_BANLE)->where('admin_id', auth()->id())->update([
                    'status' => EOrderDetail::STATUS_SAVED,
                    'order_id' => $order->id,
                ]);
                $accesory_code = Accessory::all()->pluck('code')->toArray();
                $order_detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVED)->where('type', EOrderDetail::TYPE_BANLE)
                    ->where('admin_id', auth()->id())->whereNotNull('order_id')->get();
                foreach ($order_detail as $item) {
                    if (in_array($item->code, $accesory_code)) {
                        if (!$this->order_id) {
                            $accessory = Accessory::where('code', $item->code)->get()->first();
                            $accessory->quantity = $accessory->quantity - $item->quantity;
                            $accessory->save();
                        }
                    } else {
                        $accessoryNew = new Accessory();
                        $accessoryNew->quantity = $item->quantity;
                        $accessoryNew->price = $item->listed_price;
                        $accessoryNew->code = $item->code;
                        $accessoryNew->save();
                    }
                    $accessory = Accessory::where('code', $item->code)->get()->first();
                    OrderDetail::where('code', $item->code)->update(['product_id' => $accessory->id]);
                }
                $order->update([
                    'total_items' => $order_detail->count(),
                    'total_money' => $order->totalPriceByType(),
                    'order_no' => 'ORDER_' . $order->id,
                ]);
            } else {
                $this->validate([
                    'phone' => 'required|max:11|regex:/^[0-9]+$/i|unique:customers,phone,' . $customer->id,
                ]);
                Customer::where('code', $this->code)->update([
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'name' => $this->name,
                    'identity_code' => $this->identity_code,
                    'address' => $this->address,
                    'city' => $this->province_id,
                    'district' => $this->district_id,
                    'ward' => $this->ward_id,
                ]);
                if ($this->order_id) {
                    $order = Order::findOrFail($this->order_id);
                } else {
                    $order = new Order();
                }
                $order->admin_id = auth()->id();
                $order->category = EOrder::CATE_ACCESSORY;
                $order->order_type = EOrder::ORDER_TYPE_SELL;
                $order->type = EOrder::TYPE_BANLE;
                $order->status = EOrder::STATUS_UNPAID;
                $order->customer_id = $customer->id;
                $order->save();
                //list phu tung
                $order_detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('type', EOrderDetail::TYPE_BANLE)->where('admin_id', auth()->id())->update([
                    'status' => EOrderDetail::STATUS_SAVED,
                    'order_id' => $order->id,
                ]);
                $accesory_code = Accessory::all()->pluck('code')->toArray();
                $order_detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVED)->where('type', EOrderDetail::TYPE_BANLE)->where('admin_id', auth()->id())->whereNotNull('order_id')->get();
                foreach ($order_detail as $item) {
                    if (in_array($item->code, $accesory_code)) {
                        if (!$this->order_id) {
                            $accessory = Accessory::where('code', $item->code)->get()->first();
                            $accessory->quantity = $accessory->quantity - $item->quantity;
                            $accessory->save();
                        }
                    } else {
                        $accessoryNew = new Accessory();
                        $accessoryNew->quantity = $item->quantity;
                        $accessoryNew->price = $item->listed_price;
                        $accessoryNew->code = $item->code;
                        $accessoryNew->save();
                    }
                    $accessory = Accessory::where('code', $item->code)->get()->first();
                    OrderDetail::where('code', $item->code)->update(['product_id' => $accessory->id]);
                }
                $order->update([
                    'total_items' => $order_detail->count(),
                    'total_money' => $order->totalPriceByType(),
                    'order_no' => 'ORDER_' . $order->id,
                ]);
            }
            if ($this->order_id) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhật thành công']);
            } else {
                $this->emit('loadListInput');
                $this->code = '';
                $this->phone = '';
                $this->resetInput();

                $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Tạo mới thành công']);
            }
        }
    }

    public function updatedCode()
    {
        if ($this->code) {
            $this->inputChangeValue('code');
        } else if ($this->phone) {
            $this->inputChangeValue('phone');
        }
    }

    public function inputChangeValue($key)
    {
        $value = '';
        if ($key == 'code') {
            $value = $this->code;
        } else if ($key == 'phone') {
            $value = $this->phone;
        }
        $customer = Customer::where($key, $value)->first();
        if ($customer) {
            $this->name = $customer->name;
            $this->code = $customer->code;
            $this->address = $customer->address;
            $this->phone = $customer->phone;
            $this->email = $customer->email;
            $this->identity_code = $customer->identity_code;
            $this->province_id = $customer->city;
            $this->district_id = $customer->district;
            $this->ward_id = $customer->ward;
            if ($this->province_id || $this->district_id || $this->ward_id || $this->address) {
                $this->emit('fillAddress', $this->province_id, $this->district_id, $this->ward_id, $this->address);
            }
        }
        //emit change value for detail
        $this->emit('setHeaderInfo', $this->code, $this->phone);
    }

    public function updatedPhone()
    {
        if ($this->phone) {
            $this->inputChangeValue('phone');
        } else if ($this->code) {
            $this->inputChangeValue('code');
        }
    }

    public function resetInput()
    {
        $this->name = '';
        $this->identity_code = '';
        $this->address = '';
        $this->phone = '';
        $this->email = '';
        $this->province_id = '';
        $this->district_id = '';
        $this->ward_id = '';
        $this->emit('resetAddress');

        //clear list input
        //$this->emit('resetlistaccessories');
    }

    /**
     * Do add barcode
     */
    public function addBarCode($code)
    {
        if (!$code) {
            $message = 'Bạn chưa chọn mã phụ tùng !vui lòng chọn mã trước';
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
        }

        if ($code) {
            //1. check code existing
            $scanAccessoryNumber = Accessory::where('code', $code)
                ->where('quantity', '<>', 0)
                ->where('warehouse_id', $this->selectwarehouse)
                ->first();
            if ($scanAccessoryNumber) {
                //else
                $this->emit('addInputRow', $code, $this->selectwarehouse, $this->positionWarehouseId);
            } else {
                $message = "Mã phụ tùng :" . $code . " không đủ tồn kho hoặc không còn tồn tại!";
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
            }
            $this->barcode = "";
        }
        //$this->emit('resetInputBarCode');
    }
}
