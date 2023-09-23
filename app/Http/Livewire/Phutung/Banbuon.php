<?php

namespace App\Http\Livewire\Phutung;

use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Http\Livewire\Base\BaseLive;
use App\Http\Livewire\Component\ListInputPartWholesale;

use App\Imports\BanBuonImport;
use App\Models\Accessory;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Warehouse; //tudn add
use App\Models\PositionInWarehouse; //tudn add

use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Banbuon extends BaseLive
{
    public $totalstock = 0;
    public $totalsell = 0;

    public $ward_id, $district_id, $province_id;
    public $type;
    public $code, $name, $address, $phone, $email, $barcode;
    public $addBtn = true;
    public $statusInputCode = false;
    public $file;
    public $identity_code;
    public $status = false, $order_id;

    public $avaibleaccessories =[];
    public $warehouses ; //ds các warehouse
    public $selectwarehouse;
    public $updatestatus =false;
    public $positionWarehouseId;
    public $positionWarehouseList=[];
    public $itempositionid ;

    public $chkIsVirtual =false;
    public $isVirtual = 0;
    public $transactionDate;

    protected $listeners = ['setBtnAddStatus', 'setAddress', 'addBarCode',
                            'checkinputheader','resetInput', 'settransactionDate'];

                            

    public function settransactionDate($time)
    {
        $this->transactionDate = date('Y-m-d', strtotime($time['transactionDate']));
        $this->emit('getTransactionDate', $this->transactionDate);
    }
    public function mount()
    {
        $warehouses=Warehouse::all(); // it will get the entire table
        $this->selectwarehouse =0;
        if($warehouses){
            $this->warehouses =$warehouses;
            foreach($warehouses as $item){
                $this->selectwarehouse =$item->id;
                break;
            }
        }
        $m_positionWarehouseList = PositionInWarehouse::where('warehouse_id',$this->selectwarehouse)
        ->get();

        /*
        if($m_positionWarehouseList){
            $this->positionWarehouseList =$m_positionWarehouseList;
            foreach($m_positionWarehouseList as $item){
                //1. get selected
                $this->positionWarehouseId =$item->id;
                break;
            }
        }else $this->positionWarehouseId = 0;
        */

        if (isset($_GET['show'])) {
            $this->status = true;
            $this->updatestatus =false;
        }
        if (isset($_GET['id'])) {
            $this->order_id = $_GET['id'];
            $customer_id = Order::find($this->order_id)->customer_id;
            $this->updatestatus =true;

            if ($this->order_id) {
                $existedOrder = Order::find($this->order_id);
                $order = Order::leftJoin('suppliers', 'suppliers.id', '=', 'orders.supplier_id')
                ->where('orders.id', $this->order_id)->first();
                if ($order) {
                    $customer = Customer::find($order->customer_id);
                    $this->identity_code=$customer->identity_code;
                    $this->code = $customer->code;
                    $this->name = $customer->name;
                    $this->address = $customer->address;
                    $this->phone = $customer->phone;
                    $this->email = $customer->email;
                    $this->ward_id = $customer->ward;
                    $this->district_id = $customer->district;
                    $this->province_id = $customer->city;
                    $this->isVirtual = isset($order->isvirtual) ? $order->isvirtual : 0;
                    $this->chkIsVirtual =$this->isVirtual==1 ? true :false;
                }

                //get warehouse of this order
                $orderdetail = Orderdetail::where('order_id',$this->order_id)->first();
                if($orderdetail){
                    $this->selectwarehouse = $orderdetail->warehouse_id;
                    $this->positionWarehouseId = $orderdetail->position_in_warehouse_id;
                    $this->transactionDate = date('Y-m-d', strtotime($existedOrder->created_at));
                }

            }

        }
    }

    /**
     * handle check or uncheck isVirtual check box
     */
    public function updatedchkIsVirtual(){
        $this->isVirtual = ($this->chkIsVirtual) ? 1 : 0;
        $this->emit('setIsVirtualOrderToDetail',$this->isVirtual);
    }

    /**
     * raise event selected ware house complete
     * set value to componnent
     */
    //
    public function updatedbarcode(){
        //re render warehouse, get selected werehouse
        if (!empty($this->barcode)) {
            # code...
            $partInfo = Accessory::whereNull('deleted_at')
            ->select(DB::raw('max(quantity) as quantity'),
            'code','warehouse_id','position_in_warehouse_id')
            ->groupBy('code','warehouse_id','position_in_warehouse_id')
            ->having('code','=',$this->barcode)
            ->first();
            if(isset($partInfo)){
                $this->selectwarehouse = $partInfo->warehouse_id;
                $this->positionWarehouseId = $partInfo->position_in_warehouse_id;
            }
        }
    }

    /**
     * event change wrehouse
     * updated position follow warehouse
     */
    public function updatedselectwarehouse(){
        $warehouse_id = $this->selectwarehouse;
        $m_positionWarehouseList = PositionInWarehouse::where('warehouse_id',$warehouse_id)->get();
        if($m_positionWarehouseList){
            $this->positionWarehouseList = $m_positionWarehouseList;
            foreach($m_positionWarehouseList as $item){
                if(!isset($this->positionWarehouseId)){
                    $this->positionWarehouseId = $item->id;
                    break;
                }else break;
            }
        }

        /* TUDN removed uu tien chon part truoc
       //Get Avaible accessories
       $this->avaibleaccessories = Accessory::where('warehouse_id',$this->selectwarehouse)
       ->where('position_in_warehouse_id',$this->positionWarehouseId)
       ->get();

       */
      $this->emit('setwarehouse',$this->selectwarehouse);
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

    public function resetBtnAddStatus()
    {
        $this->addBtn = false;
    }
    public function render()
    {
        $this->avaibleaccessories = Accessory::whereNull('deleted_at')
        ->Select('code','name')->distinct()->get();

        $m_positionWarehouseList = PositionInWarehouse::where('warehouse_id',$this->selectwarehouse)
        ->get();
        if (isset($m_positionWarehouseList)) {
            # code...
            $this->positionWarehouseList =$m_positionWarehouseList;
        }
        //set isVirtual to detail
        $this->emit('setIsVirtualOrderToDetail',$this->isVirtual);
        $this->dispatchBrowserEvent('setSelect2');
        return view('livewire.phutung.banbuon');
    }

    public function add()
    {
        //$this->addBtn = false;
        $this->emit('addNew');
    }
    public function setBtnAddStatus()
    {
        $this->addBtn = true;
    }

    /**
     * create order
     */
    public function checkinputheader(){
        $this->validate([
            'code' => 'required',
            'name' => 'required',
            'phone' => 'required',], [], [
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
            'phone' => 'required',], [], [
            'code' => 'Mã khách hàng',
            'name' => 'Tên khách hàng',
            'phone' => 'Số điện thoại'
        ]);
        if ($this->order_id) {
            $detail = Order::find($this->order_id)->details->toArray();
        } else {
            $detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('type', EOrderDetail::TYPE_BANBUON)->where('admin_id', auth()->id())->get()->toArray();
        }
        if (count($detail) == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Chưa có phụ tùng nào được nhập']);
        } elseif (!$this->addBtn) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Có bản nháp chưa hoàn thành']);
        } else {
            $customer = Customer::where('code', $this->code)->first();

            if (!$customer) {
                $this->validate([
                    // 'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:customers,email',
                    'phone' => 'required|max:11|regex:/^[0-9]+$/i|unique:customers,phone',], [], [

                    'phone' => 'Số điện thoại'
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
                $order->status = EOrder::STATUS_UNPAID;
                $order->order_type = EOrder::ORDER_TYPE_SELL;
                $order->type = EOrder::TYPE_BANBUON;
                $order->customer_id = $newCustomer->id;
                $order->save();
                //list phu tung
                OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('type', EOrderDetail::TYPE_BANBUON)->where('admin_id', auth()->id())->update([
                    'status' => EOrderDetail::STATUS_SAVED,
                    'order_id' => $order->id,
                ]);
                $order_detail = OrderDetail::where('order_id', $order->id)->get();
                if ($order_detail) {
                    $accesory_code = Accessory::all()->pluck('code')->toArray();
                    foreach ($order_detail as $item) {
                        if (in_array($item->code, $accesory_code)) {
                            if(!$this->order_id) {
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
            } else {
                $this->validate([
                    // 'email'=>'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:customers,email,'.$customer->id,
                    'phone' => 'required|max:11|regex:/^[0-9]+$/i|unique:customers,phone,' . $customer->id,], [], [

                    'phone' => 'Số điện thoại'
                ]);
                Customer::where('code', $this->code)->update([
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'name' => $this->name,
                    'address' => $this->address,
                    'city' => $this->province_id,
                    'district' => $this->district_id,
                    'identity_code' => $this->identity_code,
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
                $order->type = EOrder::TYPE_BANBUON;
                $order->status = EOrder::STATUS_UNPAID;
                $order->customer_id = $customer->id;
                $order->save();
                //list phu tung
                $order_detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('type', EOrderDetail::TYPE_BANBUON)->where('admin_id', auth()->id())->update([
                    'status' => EOrderDetail::STATUS_SAVED,
                    'order_id' => $order->id,
                ]);
                $accesory_code = Accessory::all()->pluck('code')->toArray();
                $order_detail = OrderDetail::where('order_id', $order->id)->get();
                foreach ($order_detail as $item) {
                    if (in_array($item->code, $accesory_code)) {
                        if(!$this->order_id) {
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
            $this->province_id = $customer->city;
            $this->district_id = $customer->district;
            $this->ward_id = $customer->ward;
            if ($this->province_id || $this->district_id || $this->ward_id || $this->address) {
                $this->emit('fillAddress', $this->province_id, $this->district_id, $this->ward_id, $this->address);

            }
        }
        //emit change value for detail
        $this->emit('setHeaderInfo',$this->code,$this->phone);
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
        $this->code = '';
        $this->name = '';
        $this->phone = '';
        $this->address = '';
        $this->email = '';
        $this->province_id = '';
        $this->district_id = '';
        $this->ward_id = '';
        $this->emit('resetAddress');

        //clear list input
        //$this->emit('resetlistaccessories');
    }

    public function import()
    {
        $this->validate([
            'file' => 'required'
        ]);
        try {
            Excel::import(new BanBuonImport, $this->file);
            $this->emit('close-modal-import');
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Import thành công']);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $ar = [];
            foreach ($failures as $failure) {
                $ar[] = $failure->errors()[0];
            }
            $ar = array_unique($ar);
            $message = '';
            foreach ($ar as $item) {
                $message .=$item . '<br>';
            }
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
        }
    }

    public function downloadExample()
    {
        return Storage::disk('public')->download('mau_file_ban_buon_phu_tung.xlsx');
    }


    /**
     * Do scan part number
     * check scan part exist or not
     * if exist add row
     */
    public function addBarCode($code)
    {
        
        if (!$code) {
            $message = 'Bạn chưa chọn mã phụ tùng !vui lòng chọn mã trước';
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
        }

        //$this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => $code]);
        $this->validate([
            'selectwarehouse'=> 'required',
            'barcode' => 'required'
        ], [
            'selectwarehouse.required' => 'Bạn chưa chọn kho',
            'barcode.required' => 'Bạn chưa chọn mã phụ tùng'
        ], []);

        //1. check code existing
        $scanAccessoryNumber = Accessory::where('code',$code)
                            ->where('quantity','<>',0)
                            ->where('warehouse_id',$this->selectwarehouse)
                            ->first();
        if($scanAccessoryNumber){
            $this->emit('addInputRow', $code,$this->selectwarehouse,$this->positionWarehouseId);
        }else{
            $message = "Mã phụ tùng :" . $code . " không đủ tồn kho hoặc không còn tồn tại!";
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
        }
        $this->barcode = "";
    }
}
