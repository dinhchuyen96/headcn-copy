<?php

namespace App\Http\Livewire\Phutung;

use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Imports\PhuTungNhapImport;
use App\Imports\ValidatePhuTungNhapImport;
use App\Models\Accessory;
use App\Models\MasterData;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\HMSPartOrderPlanDetail;

use App\Models\Supplier;
use App\Models\Warehouse; //TUDN
use App\Models\PositionInWarehouse;
use App\Models\CategoryAccessory; //TUDN

use App\Http\Livewire\Base\BaseLive;
use App\Service\Community;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

class OrderBuyList extends BaseLive
{
    public $ward_id, $district_id, $province_id;
    public $supplyCode, $name, $address, $phone, $email, $barCode;
    public $addBtn = true;
    public $o_number, $file;
    public $order_id, $isViewMode = false;
    public $hvnHiddenDiv = false;
    public $isViewModeInputCode = false;
    public $positionWarehouseId;
    public $positionWarehouseList = [];
    public $isEditMode = false;


    //TUDN
    public $warehouses = [];
    public $selectwarehouse;
    public $selectdate;
    public $receivedate;

    public $inputQty;
    public $inputPrice; //price of barcode input
    public $barCodeName;

    public $itempositionid;
    public $itemwarehouseid;
    public $receivebyPO;

    public $autoconvert = true;

    public $isDisable =false;

    //Scan bill code number
    public $receivebyBillAndShip_number =true;
    public $billandship_number ='';
    public $bill_number = '';
    //end scan bill code number

    protected $listeners = [
        'setBtnAddStatus', 'setAddress', 'changeOrderNumber', 'resetBtnAddStatus', 'addBarCode',
        'checkinputheader', 'receiveBillAndShipNumber'
    ];

    public function mount()
    {
        if (isset($_GET['show'])) {
            $this->isViewMode = true;
        }

        //neu o che do view or edit
        if (isset($_GET['id'])) {
            $this->order_id = $_GET['id'];
            $this->isEditMode = true;
            $order = Order::leftJoin('suppliers', 'suppliers.id', '=', 'orders.supplier_id')
                ->where('orders.id', $this->order_id)->first();
            if ($order) {
                $this->o_number = $order->order_no;
                $receivedate = OrderDetail::where('order_id', $this->order_id)
                    ->select('buy_date')
                    ->get()->first();
                $this->receivedate =isset($receivedate->buy_date) ? $receivedate->buy_date :  reFormatDate(now(), 'Y-m-d');

                $supply = Supplier::find($order->supplier_id);
                if ($supply) {
                    $this->supplyCode = $supply->code;
                    $this->name = $supply->name;
                    $this->address = $supply->address;
                    $this->phone = $supply->phone;
                    $this->email = $supply->email;
                    $this->ward_id = $supply->ward_id;
                    $this->district_id = $supply->district_id;
                    $this->province_id = $supply->province_id;
                } else {
                    $this->supplyCode = env('APP_HVNCODE');
                    $this->hvnHiddenDiv = true;
                    $this->name = env('APP_HVNNAME');
                }
                if ($this->supplyCode == env('APP_HVNCODE')) {
                    $this->receivebyBillAndShip_number = true;
                    $this->receivebyPO = false;
                } else {
                    $this->receivebyBillAndShip_number = false;
                    $this->receivebyPO = false;
                }

                //set lai position warehouse
               // $whid =  Order::find($_GET['id'])->warehouse_id;
               // $this->selectwarehouse =$whid;
                $this->warehouses = Warehouse::all(); // it will get the entire table
                $this->bill_number =isset($order->bill_number) ? $order->bill_number : '';
                $this->billandship_number =isset($order->bill_number) ? $order->bill_number : '';

                //get warehouse of this order
                $orderdetail = Orderdetail::where('order_id',$this->order_id)->first();
                if($orderdetail){
                    $this->selectwarehouse = $orderdetail->warehouse_id;
                    $this->positionWarehouseList = PositionInWarehouse::where('warehouse_id', $this->selectwarehouse) ->get();
                    $this->positionWarehouseId = $orderdetail->position_in_warehouse_id;
                }else{
                    $this->positionWarehouseList = PositionInWarehouse::all();
                }

            }
        } else {
            //o che do create new
            $this->supplyCode = env('APP_HVNCODE');
            $this->hvnHiddenDiv = true;
            $this->name = env('APP_HVNNAME');
             //TUDN
            $this->receivedate =  reFormatDate(now(), 'Y-m-d');
            $this->receivebyBillAndShip_number = true;
            $this->receivebyPO = false;
            $warehouses = Warehouse::all(); // it will get the entire table
            if (!empty($warehouses)) {
                $this->warehouses = $warehouses;
                foreach ($warehouses as $item) {
                    $this->selectwarehouse = $item->id;
                    //get position warehouse
                    break;
                }
            } else $this->selectwarehouse = '';
            $m_positionWarehouseList = PositionInWarehouse::where('warehouse_id', $this->selectwarehouse)
                ->get();
            if ($m_positionWarehouseList) {
                $this->positionWarehouseList = $m_positionWarehouseList;
                foreach ($m_positionWarehouseList as $item) {
                    //1. get selected
                    $this->positionWarehouseId = $item->id;
                    break;
                }
            } else $this->positionWarehouseId = '';
            //END TUDN

        }

    }




    /**handle if user check autoconvert */
    public function updatedautoconvert()
    {
    }

    /**
     * handle action after finish enter supply code
     * check hvn thi load name of hvn and pOs
     * else check existing supplier name
     */
    public function updatedsupplyCode()
    {
        if ($this->supplyCode == env('APP_HVNCODE')) {
            $this->receivebyBillAndShip_number =true;
            $this->name = env('APP_HVNNAME');
            $this->po_number = DB::table('hms_part_order_plan')->where('order_number', '!=', '')
                ->get()->pluck('order_number');
            //set default input by PO
            $this->receivebyBillAndShip_number =true;
            $this->receivebyPO = false;

        } else {
            $this->receivebyBillAndShip_number =false;
            //set default input by PO
            $this->receivebyPO = false;
            //load existing
            $supply = Supplier::where('code', $this->supplyCode)->get()->first();
            if ($supply) {
                $this->name = $supply->name;
                $this->address = $supply->address;
                $this->phone = $supply->phone;
                $this->email = $supply->email;
                $this->province_id = $supply->province_id;
                $this->district_id = $supply->district_id;
                $this->ward_id = $supply->ward_id;
                if ($this->province_id || $this->district_id || $this->ward_id || $this->address) {
                    $this->emit('fillAddress', $this->province_id, $this->district_id, $this->ward_id, $this->address);
                    $this->statusInputCode = false;
                }
            }
        }
        $this->emit('setsuppliercode',$this->supplyCode);
    }

    //TUDN
    /**
     * Do event change warehouse
     */
    public function updatedselectwarehouse(){
        //render lai vi tri kho
        $warehouse_id = $this->selectwarehouse;
        $m_positionWarehouseList = PositionInWarehouse::where('warehouse_id', $warehouse_id)->get();
        if ($m_positionWarehouseList) {
            $this->positionWarehouseList = $m_positionWarehouseList;
            foreach ($m_positionWarehouseList as $item) {
                $this->positionWarehouseId = $item->id;
                break;
            }

        }
    }



    public function onChangePositionInWarehouse()
    {
        /*
        $mpositionwarehouseid = $this->positionWarehouseId;
        $this->dispatchBrowserEvent('show-toast',
        ['type' => 'error', 'message' => $mpositionwarehouseid]);
        $this->positionWarehouseId =$mpositionwarehouseid; */
    }

    //END TUDN





    public function render()
    {
        $po_number = [];
        if ($this->supplyCode == env('APP_HVNCODE')) {
            $po_number = DB::table('hms_part_order_plan')->where('order_number', '!=', '')->get()->pluck('order_number');
        }

        $this->dispatchBrowserEvent('setSelect2');
        return view('livewire.phutung.order-buy-list', compact('po_number'));
    }

    /**
     * Handle event
     */
    public function changeOrderNumber()
    {
        if($this->receivebyPO){
            $this->emit('resetlistaccessories'); // reset all input  before
            $this->addBarCode(null);
            $this->isDisable =true;
        }
    }

    /**
     * Handle when user scan barcode bill and ship number
     * @param billandshipnumber = 'shipno&billno&dealercode
     */
    public function receiveBillAndShipNumber($billandshipnumber){
        if(!empty($billandshipnumber)){
            $arr = explode('&',$billandshipnumber);
            $shipnumber = isset($arr[0]) ? $arr[0] : '';
            $billnumber = isset($arr[1]) ? $arr[1] : '';
            $dealercode = isset($arr[2]) ? $arr[2] : '';
            $receivedate = $this->receivedate;
            $warehouse_id =$this->selectwarehouse;
            $position_in_warehouse_id =$this->positionWarehouseId;
            $autoconvert =$this->autoconvert;

            //validate barcode co hop le
            if($shipnumber!=''&&$billnumber!='' &&$dealercode!=''){
                //1. get all content by bill
                //barcode = shipnumber & billnumber &dealercode
                $this->emit('addInputRowByBillAndShipNumber',
                            $shipnumber,$billnumber,$dealercode,
                            $receivedate,$autoconvert,
                            $warehouse_id,$position_in_warehouse_id
                );
                $this->resetInputPartData();
            }else{
                $message = 'Barcode nhập PT không hợp lệ!';
                $this->dispatchBrowserEvent(
                    'show-toast',
                    ['type' => 'error', 'message' => $message]
                );
            }
        }
    }

    /**
     * handle after user click check box receive by bill number
     */
    public function updatedreceivebyBillAndShip_number(){
        if($this->receivebyBillAndShip_number==false){
            $this->receivebyPO =true;
        }else $this->receivebyPO =false;
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
    public function resetBtnAddStatus()
    {
        $this->addBtn = false;
    }

    public function ValidateBarcode()
    {
        if ($this->code == env('APP_HVNCODE')) {
            $codeInVali = DB::table('hms_part_order_plan_detail')
                ->where('part_no', '!=', '')
                ->pluck('part_no')->toArray();
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
            'quantity.integer' => 'số lượng phải nhập kiểu số',
            'listed_price.required' => 'Giá niêm yết bắt buộc',
            'listed_price.min' => 'Giá niêm yết phải lớn hơn 0',
            'listed_price.max' => 'Giá niêm yết không quá 10000000000',
            'actual_price.required' => 'Giá in hóa đơn bắt buộc ',
            'actual_price.min' => 'Giá in hóa đơn phải lớn hơn 0',
            'vat_price.required' => 'Giá thực tế bắt buộc',
            'vat_price.min' => 'Giá thực tế phải lớn hơn 0',
        ], []);
    }

    /**
     * Do event Barcode scanner or Barcode Enter
     * Get Part info from HMS partOrder Plan if existing
     * focus in name text box
     */
    public function getBarCodeInfo()
    {
        //if is HVN then  get partname via hms part order plan
        //else raise forcus in barcodeName item
        $isExistingPartNo = true;
        if ($this->supplyCode == env('APP_HVNCODE')) {
            $partNumber = $this->barCode;
            $orderNumber = $this->o_number;

            //check ma phu tung co ton tai trong PO ko
            //neu ko thi alert message va focus lai o ma pt
            $partInfo = HMSPartOrderPlanDetail::where('order_number', $orderNumber)
                ->where('part_no', $partNumber)->get()->first();
            if ($partInfo) {
                $this->barCodeName = $partInfo->part_description;
                $this->inputPrice = (int)Community::getAmount($partInfo->dnp);
                $isExistingPartNo = true;
            } else {
                $this->dispatchBrowserEvent(
                    'show-toast',
                    ['type' => 'error', 'message' => 'Mã phụ tùng không có trong PO!']
                );
                $isExistingPartNo = false;
            }
        }else{
            //get part info from category part
            $partNumber = trim($this->barCode);
            $partInfo = CategoryAccessory::whereNull('deleted_at')
            ->where('code','=', $partNumber)->get()->first();
            if ($partInfo) {
                $this->barCodeName = $partInfo->name;
                //$this->inputPrice = (int)Community::getAmount($partInfo->dnp);
                $isExistingPartNo = true;
            }
        }
        //neu ma pT ton tai hoac ko fai la NCC HVN
        if ($isExistingPartNo) {
            //focus in ten phu tung
            $this->dispatchBrowserEvent('setFocusItem', ['name' => 'inputQty']);
        } else {
            $this->dispatchBrowserEvent('setFocusItem', ['name' => 'barCode']);
        }
    }

    /**
     * validate when user input by PO
     */
    public function ValidateInputByPO()
    {
        //validate input
        if ($this->supplyCode == env('APP_HVNCODE')) {
            $this->validate([
                'positionWarehouseId' => 'required',
                'o_number' => 'required'
            ], [
                'positionWarehouseId.required' => 'Bạn chưa chọn vị trí kho',
                'o_number.required' => 'Số PO bắt buộc phải nhập',
            ], []);
        } else {
            $this->validate([
                'positionWarehouseId' => 'required',
            ], [
                'positionWarehouseId.required' => 'Bạn chưa chọn vị trí kho',
            ], []);
        }
    }
    /**
     *
     * validate if input by each part number in detail
     */
    public function ValidateInputByPartNumber()
    {
        //validate input
        if ($this->supplyCode == env('APP_HVNCODE')) {
            $this->validate([
                'positionWarehouseId' => 'required',
                'o_number' => 'required',
                'barCode' => 'required',
                'barCodeName' => 'required',
                'inputQty' => 'required|integer|gt:0|digits_between:1,9',
                'inputPrice' => 'required|integer|gt:0|digits_between:1,9',
            ], [
                'positionWarehouseId.required' => 'Bạn chưa chọn vị trí kho',
                'o_number.required' => 'Số PO bắt buộc phải nhập',
                'barCode.required' => 'Barcode bắt buộc phải nhập',
                'barCodeName.required' => 'Tên phụ tùng bắt buộc phải nhập',
                'inputQty.required' => 'Số lượng bắt buộc phải nhập ',
                'inputQty.integer' => 'Số lượng phải là kiểu số',
                'inputQty.gt' => 'Số lượng phải lớn hơn 0',
                'inputQty.digits_between' => 'Số lượng phải từ 1 - 999999999',
                'inputPrice.required' => 'Đơn giá bắt buộc phải nhập ',
                'inputPrice.integer' => 'Số lượng phải là kiểu số',
                'inputPrice.gt' => 'Số lượng phải lớn hơn 0',
                'inputPrice.digits_between' => 'Số lượng phải từ 1 - 999999999'
            ], []);
        } else {
            $this->validate([
                'positionWarehouseId' => 'required',
                'barCode' => 'required',
                'barCodeName' => 'required',
                'inputQty' => 'required|integer|gt:0|digits_between:1,9',
                'inputPrice' => 'required|integer|gt:0|digits_between:1,9'
            ], [
                'positionWarehouseId.required' => 'Bạn chưa chọn vị trí kho',
                'barCode.required' => 'Barcode bắt buộc phải nhập',
                'barCodeName.required' => 'Tên phụ tùng bắt buộc phải nhập',
                'inputQty.required' => 'Số lượng bắt buộc phải nhập ',
                'inputQty.integer' => 'Số lượng phải là kiểu số',
                'inputQty.gt' => 'Số lượng phải lớn hơn 0',
                'inputQty.digits_between' => 'Số lượng phải từ 1 - 999999999',
                'inputPrice.required' => 'Đơn giá bắt buộc phải nhập ',
                'inputPrice.integer' => 'Số lượng phải là kiểu số',
                'inputPrice.gt' => 'Số lượng phải lớn hơn 0',
                'inputPrice.digits_between' => 'Số lượng phải từ 1 - 999999999'
            ], []);
        }
    }

    /**
     * add info to list
     */
    public function addBarCode($code=null)
    {

        $warehouse_id = $this->selectwarehouse;
        $position_in_warehouse_id = $this->positionWarehouseId;

        $name = $this->barCodeName;
        $qty = $this->inputQty;
        $inputPrice = $this->inputPrice;
        $supplierCode = $this->supplyCode;
        $poNumber = $this->o_number;

        /**check nhap theo PO hay chi tiet
         * neu nhap chi tiet
         * else nhap PO
         */
        $autoconvert = $this->autoconvert;
        if (!$this->receivebyPO) {
            $this->ValidateInputByPartNumber();

            //add row to list in
            $this->emit(
                'addInputRow',
                $code,
                $name,
                $warehouse_id,
                $position_in_warehouse_id,
                $qty,
                $inputPrice,
                $supplierCode,
                $poNumber,
                $this->receivedate,
                $autoconvert
            );
            //reset input data
            $this->resetInputPartData();
        } else {
            $this->ValidateInputByPO();
            //add row to list in
            $this->emit(
                'addInputRowByPO',
                $warehouse_id,
                $position_in_warehouse_id,
                $supplierCode,
                $poNumber,
                $this->receivedate,
                $autoconvert
            );
        }
    }

    /**raise event when user change receive date */
    public function updatedreceivedate()
    {
        $this->emit('setreceivedate', $this->receivedate);
    }
    /**
     * reset input data
     */
    public function resetInputPartData()
    {
        $this->barCode = '';
        $this->barCodeName = '';
        $this->inputQty = '';
        $this->inputPrice = '';
        $this->dispatchBrowserEvent('setFocusItem', ['name' => 'barCode']);
    }




    /**
     * create order
     * validate input
     */
    public function checkinputheader()
    {
        $this->validate([
            'supplyCode' => 'required',
            'name' => 'required',
            'selectwarehouse' => 'required',
            'positionWarehouseId' => 'required'
        ], [
            'supplyCode.required' => 'Mã nhà cung cấp bắt buộc',
            'name.required' => 'Tên nhà cung cấp bắt buộc',
            'selectwarehouse.required' => 'Bạn phải chọn kho nhập',
            'positionWarehouseId.required' => 'Vị trí kho bắt buộc',
        ]);
    }

    public function save()
    {
        if ($this->order_id) {
            $detail = Order::find($this->order_id)->details->toArray();
        } else {
            if ($this->supplyCode == env('APP_HVNCODE')) {
                $detail = DB::table('hms_part_order_plan_detail')->where('order_number', $this->o_number)->get();
            } else {
                $detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('type', EOrderDetail::TYPE_NHAP)->where('supplier_type', '!=', EOrderDetail::SUPPLIER_TYPE)
                    ->where('admin_id', auth()->id())->get()->toArray();
            }
        }
        if (count($detail) == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Chưa có phụ tùng nào được nhập']);
        } elseif (!$this->addBtn) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Có bản nháp chưa hoàn thành']);
        } else {
            $this->validate([
                'supplyCode' => 'required',
                'name' => 'required',
                'positionWarehouseId' => 'required'
            ], [
                'supplyCode.required' => 'Mã nhà cung cấp bắt buộc',
                'name.required' => 'Tên nhà cung cấp bắt buộc',
                'positionWarehouseId.required' => 'Vị trí kho bắt buộc',
            ]);

            $supply = Supplier::where('code', $this->supplyCode)->first();

            if (!$supply) {
                if ($this->supplyCode != env('APP_HVNCODE')) {
                    //                    $this->validate([
                    //                        'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:suppliers,email,code' . $this->supplyCode,
                    //                        'phone' => 'required|max:11|regex:/^[0-9]+$/i|unique:suppliers,phone'
                    //                    ]);
                }
                $newSupply = new Supplier();
                $newSupply->code = $this->supplyCode;
                $newSupply->phone = $this->phone;
                $newSupply->email = $this->email;
                $newSupply->name = $this->name;
                $newSupply->address = $this->address;
                $newSupply->province_id = $this->province_id;
                $newSupply->district_id = $this->district_id;
                $newSupply->ward_id = $this->ward_id;
                $newSupply->save();
                if ($this->order_id) {
                    $order = Order::findOrFail($this->order_id);
                } else {
                    $order = new Order();
                }
                $order->admin_id = auth()->id();
                $order->category = EOrder::CATE_ACCESSORY;
                $order->order_type = EOrder::ORDER_TYPE_BUY;
                $order->type = EOrder::TYPE_NHAP;
                $order->status = EOrder::STATUS_UNPAID;
                $order->order_no = $this->o_number;
                $order->supplier_id = $newSupply->id;
                $order->warehouse_id = $this->positionWarehouseId;
                $order->save();
                //list phu tung
                $order_detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('supplier_type', '!=', EOrderDetail::SUPPLIER_TYPE)
                    ->where('type', EOrderDetail::TYPE_NHAP)->where('admin_id', auth()->id())->get();
                if ($order_detail) {
                    OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('supplier_type', '!=', EOrderDetail::SUPPLIER_TYPE)->where('type', EOrderDetail::TYPE_NHAP)->where('admin_id', auth()->id())->update([
                        'status' => EOrderDetail::STATUS_SAVED,
                        'order_id' => $order->id,
                    ]);
                    $accesory_code = Accessory::all()->pluck('code')->toArray();
                    $order_detail = OrderDetail::where('order_id', $order->id)->get();
                    foreach ($order_detail as $item) {
                        if (in_array($item->code, $accesory_code)) {
                            $accessory = Accessory::where('code', $item->code)->where('position_in_warehouse_id', $this->positionWarehouseId)->get()->first();
                            $accessory->quantity += $item->quantity;
                            $accessory->save();
                        } else {
                            $warehouseId = PositionInWarehouse::find($this->positionWarehouseId)->warehouse_id;
                            $accessoryNew = new Accessory();
                            $accessoryNew->quantity = $item->quantity;
                            $accessoryNew->price = $item->listed_price;
                            $accessoryNew->code = $item->code;
                            $accessoryNew->name = $item->name;
                            $accessoryNew->position_in_warehouse_id = $this->positionWarehouseId;
                            $accessoryNew->warehouse_id = $warehouseId;
                            $accessoryNew->save();
                        }
                        $accessory = Accessory::where('code', $item->code)->get()->first();
                        OrderDetail::where('code', $item->code)->update(['product_id' => $accessory->id]);
                    }
                }
                $order->update([
                    'total_items' => $order_detail->sum('quantity'),
                    'total_money' => $order->totalPrice(),
                    'order_no' => $order->order_no ?? 'ORDER_' . $order->id,
                ]);
                if ($this->order_id) {
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhật thành công']);
                } else {
                    $this->supplyCode = env('APP_HVNCODE');
                    $this->emit('loadListInput');
                    $this->resetInput();
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Tạo mới thành công']);
                }
            } else {
                if ($this->supplyCode != env('APP_HVNCODE')) {
                    //                    $this->validate([
                    //                        'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:suppliers,email,' . $supply->id,
                    //                        'phone' => 'required|max:11|regex:/^[0-9]+$/i|unique:suppliers,phone,' . $supply->id,
                    //                    ]);
                    Supplier::where('code', $this->supplyCode)->update([
                        'phone' => $this->phone,
                        'email' => $this->email,
                        'name' => $this->name,
                        'address' => $this->address,
                        'province_id' => $this->province_id,
                        'district_id' => $this->district_id,
                        'ward_id' => $this->ward_id,
                    ]);
                }

                if ($this->order_id) {
                    $order = Order::findOrFail($this->order_id);
                } else {
                    $order = new Order();
                }
                $order->admin_id = auth()->id();
                $order->created_by = auth()->id();
                $order->category = EOrder::CATE_ACCESSORY;
                $order->order_type = EOrder::ORDER_TYPE_BUY;
                if ($this->supplyCode == env('APP_HVNCODE')) {
                    $order->order_no = $this->o_number;
                }
                $order->type = EOrder::TYPE_NHAP;
                $order->status = EOrder::STATUS_UNPAID;
                $order->supplier_id = $supply->id;
                $order->warehouse_id = $this->positionWarehouseId;
                $order->save();
                //list phu tung
                if ($this->supplyCode != env('APP_HVNCODE')) {
                    $order_detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('type', EOrderDetail::TYPE_NHAP)
                        ->where('supplier_type', '!=', EOrderDetail::SUPPLIER_TYPE)->where('admin_id', auth()->id())->get();
                    if ($order_detail) {
                        $order_detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('type', EOrderDetail::TYPE_NHAP)->where('supplier_type', '!=', EOrderDetail::SUPPLIER_TYPE)
                            ->where('admin_id', auth()->id())->update([
                                'status' => 1,
                                'order_id' => $order->id,
                            ]);
                        $accesory_code = Accessory::all()->pluck('code')->toArray();
                        $order_detail = OrderDetail::where('order_id', $order->id)->get();
                        foreach ($order_detail as $item) {
                            if (in_array($item->code, $accesory_code)) {
                                $accessory = Accessory::where('code', $item->code)->where('position_in_warehouse_id', $this->positionWarehouseId)->get()->first();
                                $accessory->quantity += $item->quantity;
                                $accessory->price =  $item->listed_price;
                                $accessory->save();
                            } else {
                                $warehouseId = PositionInWarehouse::find($this->positionWarehouseId)->warehouse_id;
                                $accessoryNew = new Accessory();
                                $accessoryNew->quantity = $item->quantity;
                                $accessoryNew->price = $item->listed_price;
                                $accessoryNew->supplier_id = $supply->id;
                                $accessoryNew->code = $item->code;
                                $accessoryNew->name = $item->name;
                                $accessoryNew->position_in_warehouse_id = $this->positionWarehouseId;
                                $accessoryNew->warehouse_id = $warehouseId;
                                $accessoryNew->buy_date = Carbon::now();
                                $accessoryNew->save();
                            }
                            $accessory = Accessory::where('code', $item->code)->get()->first();
                            OrderDetail::where('code', $item->code)->update(['product_id' => $accessory->id]);
                        }
                        $order->update([
                            'total_items' => $order_detail->sum('quantity'),
                            'total_money' => $order->totalPrice(),
                            'order_no' => 'ORDER_' . $order->id,
                        ]);
                    }
                } else {
                    $order_detail_draft = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('category', EOrderDetail::CATE_ACCESSORY)->where('supplier_type', EOrderDetail::SUPPLIER_TYPE)
                        ->where('order_number', $this->o_number)->where('type', 3)->where('admin_id', auth()->id())->pluck('code')->toArray();
                    $order_detail = DB::table('hms_part_order_plan_detail')->where('order_number', $this->o_number)->whereIn('part_no', $order_detail_draft)->get();
                    if ($order_detail) {
                        foreach ($order_detail as $item) {
                            $accessory = Accessory::where('code', $item->part_no)->where('position_in_warehouse_id', $this->positionWarehouseId)->first();
                            if (!$accessory) {
                                $warehouseId = PositionInWarehouse::find($this->positionWarehouseId)->warehouse_id;
                                $accessory = new Accessory();
                                $accessory->quantity = $item->quantity_requested;
                                $accessory->price = $item->dnp;
                                $accessory->name = $item->part_description;
                                $accessory->supplier_id = $supply->id;
                                $accessory->code = $item->part_no;
                                $accessory->position_in_warehouse_id = $this->positionWarehouseId;
                                $accessory->warehouse_id = $warehouseId;
                                $accessory->save();
                            }
                            OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('category', EOrderDetail::CATE_ACCESSORY)->where('supplier_type', EOrderDetail::SUPPLIER_TYPE)
                                ->where('order_number', $this->o_number)->where('type', EOrderDetail::TYPE_NHAP)->where('admin_id', auth()->id())->update([
                                    'product_id' => $accessory->id,
                                    'status' => EOrderDetail::STATUS_SAVED,
                                    'order_id' => $order->id
                                ]);
                            $order->update([
                                'total_items' => $order_detail->sum('quantity'),
                                'total_money' => $order->totalPrice(),
                                'order_no' =>  $this->o_number,
                            ]);
                        }
                    }
                }
                if ($this->order_id) {
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhật thành công']);
                } else {
                    $this->supplyCode = env('APP_HVNCODE');
                    $this->emit('loadListInput');
                    $this->resetInput();
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Tạo mới thành công']);
                }
            }
        }
    }
    public function changeStatusCode()
    {
        // $this->emit('updateCode', $this->supplyCode);
        $this->statusInputCode = true;
        if ($this->supplyCode) {
            if ($this->supplyCode == env('APP_HVNCODE')) {
                $this->hvnHiddenDiv = true;
                $this->name = env('APP_HVNNAME');
                $checkSupplierHD = Supplier::where('code', env('APP_HVNCODE'))->first();
                if (!$checkSupplierHD) {
                    $checkSupplierHD = new Supplier();
                    $checkSupplierHD->code = env('APP_HVNCODE');
                    $checkSupplierHD->name = env('APP_HVNNAME');
                    $checkSupplierHD->save();
                }
            } else {
                $this->hvnHiddenDiv = false;
            }
            $supply = Supplier::where('code', $this->supplyCode)->first();
            if ($supply) {
                $this->name = $supply->name;
                $this->address = $supply->address;
                $this->phone = $supply->phone;
                $this->email = $supply->email;
                $this->province_id = $supply->province_id;
                $this->district_id = $supply->district_id;
                $this->ward_id = $supply->ward_id;
                if ($this->province_id || $this->district_id || $this->ward_id || $this->address) {
                    $this->emit('fillAddress', $this->province_id, $this->district_id, $this->ward_id, $this->address);
                    $this->statusInputCode = false;
                }
            } else {
                $this->resetInput();
            }
        }
    }
    public function updatedPhone()
    {
        $this->emit('updateSupPhone', $this->phone);
    }

    /**
     * TUDN
     * raise event when finish barcode input
     */
    public function updatedbarCode()
    {
        $this->getBarCodeInfo();
    }

    /**
     * reset input data
     */
    public function resetInput()
    {
        $this->name = '';
        $this->address = '';
        $this->phone = '';
        $this->email = '';
        $this->province_id = '';
        $this->district_id = '';
        $this->ward_id = '';
        $this->o_number = '';
        $this->barCode = '';
        $this->barCodeName = '';
        $this->inputQty = '';
        $this->inputPrice = '';

        //$this->emit('resetAddress');
        //$this->emit('resetListInput');
        //$this->emit('resetlistaccessories');
    }
    public function import()
    {
        $this->validate([
            'file' => 'required'
        ], [
            'file.required' => 'Hãy chọn file để import',
        ]);
        try {
            DB::beginTransaction();
            Excel::import(new PhuTungNhapImport, $this->file);
            $this->emit('close-modal-import');
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Import thành công']);
            DB::commit();
            return true;
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $ar = [];
            foreach ($failures as $failure) {
                $ar[] = 'Dòng ' . $failure->row() . ': ' . array_values($failure->errors())[0];
            }
            $ar = array_unique($ar);
            foreach ($ar as $item) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $item]);
            }
            // dd($ar);
            DB::rollBack();
            return;
        }
    }
    public function downloadExample()
    {
        return Storage::disk('public')->download('mau_phu_tung_nhap.xlsx');
    }

    public function validateImport()
    {
        $this->validate([
            'file' => 'required'
        ], [
            'file.required' => 'Hãy chọn file để import',
        ]);
        try {
            Excel::import(new ValidatePhuTungNhapImport, $this->file);
            $this->emit('show-btn-access');
            return true;
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $ar = [];
            foreach ($failures as $failure) {
                $ar[] = 'Dòng ' . $failure->row() . ': ' . array_values($failure->errors())[0];
            }
            $ar = array_unique($ar);
            foreach ($ar as $item) {
                $this->emit('hide-btn-access');
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $item]);
            }
            return;
        }
    }
}
