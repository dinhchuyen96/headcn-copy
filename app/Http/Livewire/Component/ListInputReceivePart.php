<?php
namespace App\Http\Livewire\Component;

use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Models\Accessories;
use App\Models\Accessory;
use App\Models\Warehouse;
use App\Models\PositionInWarehouse;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\HMSPartOrderPlanDetail;
use App\Models\Hmspartreceiveplan;
use App\Models\Supplier;
use App\Models\CategoryAccessory;
use App\Http\Livewire\Base\BaseLive;
use App\Service\Community;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\DB;



class ListInputReceivePart extends BaseLive
{

    public $totalOrderQty = 0;
    public $totalReceiptQty = 0;
    public $totalInputQty =0;

    public $addStatus = false;
    public $accessoryNumber= [];
    public $accessoryName = [];
    public $warehouse=[];

    public $whLocation=[];
    public $stockQty=[];
    public $qty= [];
    public $stkqty= [];

    public $netPrice=[];
    public $vatPrice=[];
    public $actPrice=[];
    public $product_id =[] ;// chua cac accessory id
    public $position_in_warehouse_id=[];

    public $poQty = [];
    public $receiptQty = [];
    public $whName = [];
    public $remainqty=[]; // so luong con sau khi nhap


    public $avaliableAccessory = [];
    public $i=0;
    public $inputs =[];


    public $warehouse_id;
    public $showstatus;
    public $updatestatus =false;
    public $order_id;
    public $ponumber;


    public $businesstype ; // Dinh nghia loai business la ban buon 1 - ban le 2  - nhap pt 3

    public $receivedate;

    public $supplyCode; //select supplier code
    public $o_number ; //

    public $autoconvert =false;
    public $autoconvertconfirm =false;
    public $bill_number = '';
    public $order_number = [] ;//chua ds cac order number khi input rows


    protected $listeners = ['setwarehouse'=>'setwarehouse',
                            'onSelectAccessory'=>'onSelectAccessory',
                            'addInputRow'=>'addInputRow',
                            'addInputRowByPO'=>'addInputRowByPO',
                            'craeteorderdetail'=>'craeteorderdetail',
                            'checkhasorderdetail'=>'checkhasorderdetail',
                            'resetlistaccessories'=>'resetlistaccessories',
                            'setreceivedate'=>'setreceivedate',
                            'setsuppliercode' => 'setsuppliercode',
                            'addInputRowByBillAndShipNumber' => 'addInputRowByBillAndShipNumber'
                            ];




    public function mount($type)
    {


        $this->supplyCode =env('APP_HVNCODE');//set default
        //$this->resetaccessaryNumber();
        //2. get selected warehouse from parent form
        if($type==1){
            $this->businesstype = EOrderDetail::TYPE_BANBUON;
        }elseif($type==2){
            $this->businesstype = EOrderDetail::TYPE_BANLE;
        }else{ //defaul is ban buon
            $this->businesstype = EOrderDetail::TYPE_BANBUON;
        }

        if (isset($_GET['id'])) {
            $this->order_id = $_GET['id'];
            $this->updatestatus =true;
            $order = Order::where('id',$this->order_id);
            if($order){
                 // $order->details()->select('buy_date')
                $receivedate = OrderDetail::where('order_id',$this->order_id)
                ->select('buy_date')
                ->get()->first();
                $this->receivedate =isset($receivedate->buy_date) ? $receivedate->buy_date :  reFormatDate(now(), 'Y-m-d');
            }
            $this->setComponentData($this->order_id);
        }

        if (isset($_GET['show'])) {
            $this->showstatus  = true;
            $this->updatestatus =false;

        }

    }

    /**
     * Set supplier code from parent
     */
    public function setsuppliercode($suppliercode){
        $this->supplyCode = $suppliercode;
    }
    /**
     *set receive date from parent
     */
    public function setreceivedate($receivedate){

        $this->receivedate = $receivedate;
    }

    /**
     *
     * Validate input detail
     */
    public function validateinputdetail(){

        $this->validate([
            'accessoryNumber.*'=>'required',
            'accessoryName.*'=>'required',
            'whName.*'=>'required',
            'whLocation.*'=>'required',
            'qty.*'=>'required|integer|gt:0|digits_between:1,9',
            'netPrice.*'=>'required|integer|gt:0|digits_between:1,9',
            'stkqty.*' =>'gte:0'
           ],
           ['accessoryNumber.*.required'=>'Mã phụ tùng phải nhập',
           'accessoryName.*.required'=>'Tên phụ tùng phải nhập',
           'qty.*.required'=>'phải nhập cột số lượng',
           'qty.*.integer'=>'Số lượng phải kiểu số',
           'qty.*.gt'=>'Số lượng phải lớn hơn 0',
           'qty.*.digits_between'=>'Số lượng chỉ được nhập từ 1-999999999',
           'netPrice.*.required'=>'Phải nhập cột đơn giá',
           'netPrice.*.integer'=>'Đơn giá phải kiểu số',
           'netPrice.*.gt'=>'Đơn giá phải lớn hơn 0',
           'netPrice.*.digits_between'=>'Đơn giá chỉ được nhập từ 1-999999999',
           'stkqty.*.gte' => 'số lượng nhập phải nhỏ hơn hoặc bằng tồn kho'
           ],
           []);


    }

    /**
     * create order
     * validate input
     */
    public function createorder(){
        try {
            //code...
            $this->emit('checkinputheader');
            //check supplier code da ton tai
            $supplier_id = 0; //default for HVN if not existing in DB
            $supply = Supplier::where('code', $this->supplyCode)->first();
            if (!$supply) {
                if($this->supplyCode!=env('APP_HVNCODE')){
                    $message = 'Nhà cung cấp chưa tồn tại!';
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
                }else{
                    $supplier = new Supplier();
                    $supplier->code = $this->supplyCode;
                    $supplier->name = $this->supplyCode;
                    $supplier->save();
                }
            }else{
                $supplier_id = $supply->id;

                //1. check detail exist ?
                //if not exist then warning
                if(!$this->checkhasorderdetail()) {
                    $message = 'Bạn chưa tạo chi tiết đơn hàng!';
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error',
                    'message' => $message]);
                    return;
                };
                // $this->validateinputdetail();
                //if pass validate then create order
                //if status la create new thi tao order moi
                //else update order
                if(!$this->updatestatus){ //tao moi order
                        $order = new Order();
                }else{ //update order
                    $order = Order::findOrFail($this->order_id);
                }
                $order->admin_id = auth()->id();
                $order->category = 1;
                $order->order_type = 2;
                $order->type = 3;
                $order->order_no = $this->ponumber;
                $order->supplier_id = $supplier_id;
                $order->warehouse_id = $this->warehouse_id;
                $order->bill_number =$this->bill_number;
                $order->save();
                $returnOrderId = $order->id;
                //cap nhat order detail
                if($returnOrderId){
                    $this->craeteorderdetail($returnOrderId,$this->receivedate);
                }else{
                    $message = "Tạo đơn hàng lỗi, vui lòng thử lại!";
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
                }

                //check sucess or not
                //show message finish
                $existingorderdetails = OrderDetail::where('order_id',$order->id)->get();
                if($existingorderdetails){
                    if(!$this->updatestatus){
                        $message = "Đơn hàng :". $order->id." được tạo thành công";
                    }else{
                        $message = "Đơn hàng :". $order->id." được cập nhật thành công";
                    }
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => $message]);
                }else{
                    $message = "Tạo  chi tiết đơn hàng lỗi, vui lòng thử lại!";
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
                    $order->delete();
                }
            }
        } catch (Exception $e) {
            //throw $th;
            Log::error($e->getMessage());

        }finally{
            //reset input
            $this->resetlistaccessories();
            $this->emit('resetInput');
        }

    }
    /**
     * get all part info
     * which has stock > 0
     * in selected warehouse
     */
    public function getAvaibleAccessories($id){
        $this->avaliableAccessory  = Accessory::where('quantity','<>',0)
                                            ->where('warehouse_id',$id)->pluck('code');

    }

    /**
     * check user input detail ornot
     */
    public function checkhasorderdetail()
    {
        if(count($this->accessoryNumber)==0) {
            return false;
        }
        return true;
    }
    public function setwarehouse($value){
            $this->warehouse_id =$value;
            //reset avaible accessories
            $this->getAvaibleAccessories($value);
    }

    /**
     * set params from parent component
     *
     */
    public function setComponentData($order_id){
        if($order_id){
            $this->order_id = $order_id;
            $order_details = OrderDetail::where('order_id',$order_id)->get();
            $order = Order::where('id',$order_id)->first();
            if($order->supplier){
                $supplierCode = $order->supplier->code;
            }else $supplierCode = '';
            $this->supplyCode = $supplierCode;
            $this->warehouse_id =$order ->warehouse_id;
            $this->bill_number =isset($order->bill_number) ? $order->bill_number : '';


            $selectponumber = $order->order_no;
            if($order_details)
            {
                foreach($order_details as $item)
                {
                    $poQty = 0;  //gia tri theo PO cua HVN
                    $receiptQty = 0; // gia tri con lai theo PO cua HVN
                    $code = $item->code;
                    if($supplierCode==env('APP_HVNCODE')){
                        //check ma phu tung co ton tai trong PO ko
                        //neu ko thi alert message va focus lai o ma pt
                        //get receipt qty so luong da nhan
                         $selectponumber = isset($item->order_number) ? $item->order_number : '';

                        $partInfo = Hmspartreceiveplan::where('order_number',$selectponumber)
                        ->where('part_no',$code)->first();
                        if($partInfo){
                            $poQty =$partInfo->allocated_qty ? (int) $partInfo->allocated_qty : 0;
                        }

                        if (!empty($selectponumber)) {
                            # code...
                            $receiptQty = OrderDetail::where('order_number',$selectponumber)
                            ->where('code',$code)
                            ->sum('quantity');
                        }

                    }


                    array_push($this->accessoryNumber ,$item->code);
                    array_push($this->accessoryName ,$item->accessorie!=null? $item->accessorie->name:'');
                    array_push($this->warehouse ,$item->warehouse_id);
                    array_push($this->whName ,$item->warehouse!=null?$item->warehouse->name:'');
                    array_push($this->whLocation ,$item->positioninwarehouse!=null ? $item->positioninwarehouse->name:'');
                    array_push($this->poQty ,$poQty);
                    array_push($this->receiptQty ,$receiptQty);
                    array_push($this->qty ,$item->quantity);

                    array_push($this->stkqty ,$this->poQty -$this->receiptQty - $item->quantity);


                    array_push($this->netPrice ,$item->listed_price);
                    array_push($this->vatPrice ,$item->vat_price);
                    array_push($this->actPrice ,$item->actual_price);
                    array_push($this->product_id ,$item->product_id);
                    array_push($this->position_in_warehouse_id ,$item->position_in_warehouse_id);

                }
            }
        }
    }

    /**
     * Create new order detail
     * loop via all child item
     */
    public function craeteorderdetail($orderid,$receivedate){

        //check lai order da ton tai hay chua
        $order = Order::where('id',$orderid)->first();
        if(!$order){
            $message = 'Order không còn tồn tại hoặc đã bị xóa!';
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
            exit;
        }
        $ponumber =$order->order_no;
        $order_details = [];
        $order_details = Orderdetail::where('order_details.order_id',$orderid)->get();

        if (count($order_details)) {
            foreach ($order_details as $item){
                //1.restore accessory by update quantity
                $accessoryitem = Accessory::where('id',$item->product_id)->first();
                if($accessoryitem){
                    $updateqty = (int)Community::getAmount($accessoryitem->quantity) - (int)Community::getAmount($item->quantity);
                    $accessoryitem->update(['quantity' => $updateqty]);
                }
                //2. xoa ban ghi order_details
                $item->delete();
            }
        }

        //tao thong tin order detail moi
        $total_items =0;
        $total_order_amount = 0;

        //bat dau thuc hien voi moi partno duoc chon
        foreach ($this->accessoryNumber as $key => $value) {
            //check accessory voi warehouse id, position id, and code da ton tai hay chua
            //neu chua thi tao ra
            $m_accessoryNumber = $this->accessoryNumber[$key];
            $m_accessoryName=    $this->accessoryName[$key];
            $m_order_numberr = isset($this->order_number[$key]) ? $this->order_number[$key] : $ponumber ;

            $autoconvert = isset($this->autoconvert) ? $this->autoconvert : false;
            $changerate =1;
            //neu ko autoconvert thi change rate =1 va giu nguyen code va name
            //else get change rate va code name cua ma con
            if($autoconvert){
                $partinfo =CategoryAccessory::where('parentcode', $m_accessoryNumber)
                ->where('deleted_at',null)
                ->get()->first();
                if($partinfo){
                    $m_accessoryNumber = $partinfo ->code;
                    $m_accessoryName=$partinfo ->name;
                    $changerate = $partinfo ->changerate;
                }
            }

            $m_warehouse_id = $this->warehouse[$key];
            $m_position_in_warehouse_id = $this->position_in_warehouse_id[$key];
            $m_inputQty = $this->qty[$key] * $changerate;
            $m_netPrice =(int) $this->netPrice[$key] / $changerate;


            $m_accessory =Accessory::where('code',$m_accessoryNumber)
            ->where('warehouse_id',$m_warehouse_id)
            ->where('position_in_warehouse_id',$m_position_in_warehouse_id)
            ->get()->first();

            //1neu chua ton tai thi tao moi
            if(!$m_accessory){
                $m_accessory = new Accessory();
                $m_accessory->order_id = $orderid;
                $m_accessory->code = $m_accessoryNumber;
                $m_accessory->name = $m_accessoryName;
                $m_accessory->quantity = 0;
                $m_accessory->price = $m_netPrice;
                $m_accessory->warehouse_id = $m_warehouse_id;
                $m_accessory->position_in_warehouse_id = $m_position_in_warehouse_id;
                $m_accessory->buy_date = $receivedate;
                $m_accessory->save();
            }
            $product_id = $m_accessory->id;


             //2. tao ban ghi detail order
             $order_detail = new OrderDetail();
             $order_detail->order_id = $orderid;
             $order_detail->price =  $m_netPrice;
             $order_detail->code =  $m_accessoryNumber;
             $order_detail->quantity = $m_inputQty;
             $order_detail->supplier_type = 0;
             $order_detail->status = EOrderDetail::STATUS_SAVED;
             $order_detail->name =  $m_accessoryName;
             $order_detail->admin_id = auth()->id();
             $order_detail->category = EOrderDetail::CATE_ACCESSORY;
             $order_detail->type = 3;
             $order_detail->listed_price = $m_netPrice;
             $order_detail->actual_price =  $m_netPrice;

             $order_detail->order_number = $m_order_numberr ;
             $order_detail->buy_date = $receivedate ;

             $order_detail->product_id = $product_id;
             $order_detail->warehouse_id =  $m_warehouse_id ;
             $order_detail->position_in_warehouse_id =$m_position_in_warehouse_id ;
             $order_detail->save();

             $total_items +=1;
             $total_order_amount +=(int)$this->qty[$key] * (int)$this->netPrice[$key];

             //3.tang so luong ton kho tren accessory
             $accessoryitem = Accessory::where('id',$order_detail->product_id)->first();
             if($accessoryitem){
                 $updateqty = (int)$accessoryitem->quantity + (int)$order_detail->quantity;
                 $accessoryitem->update(['quantity'=>$updateqty]);
             }
        }

        $order->update(['total_items'=>$total_items,
                        'total_money'=>$total_order_amount]);

    }


//////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * add new item
     */
    public function addNew(){
        $lastindex =1;
        addItem($lastindex);
    }

    /**
     * add new row to html table
     */
    public function addItem($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs ,$i);

    }
    /**
     * remove item from table html
     */
    public function removeItem($i)
    {
        unset($this->inputs[$i]);
        unset($this->accessoryNumber[$i]);
        unset($this->accessoryName[$i]);
        unset($this->warehouse[$i]);
        unset($this->whName[$i]);
        unset($this->whLocation[$i]);
        unset($this->poQty[$i]);
        unset($this->receiptQty[$i]);
        unset($this->qty[$i]);

        unset($this->stkqty[$i]);

        unset($this->netPrice[$i]);
        unset($this->product_id[$i]);
        unset($this->position_in_warehouse_id[$i]);
        unset($this->order_number[$i]);
    }

    /**
     * hadnle when user scan billandshipnumber barcode
     * @param//barcode = shipnumber & billnumber &dealercode
     * if type 26 0000000000&invoicenum&head
     * if type 22 shipnum&0000000000&head
     */
    public function addInputRowByBillAndShipNumber($shipnumber,$billnumber,$dealercode,
    $receivedate,$autoconvert,$warehouse_id,$position_in_warehouse_id){
        $this->resetlistaccessories(); //reset all row first
        $this->receivedate =$receivedate;
        $this->autoconvert =$autoconvert;
        $this->warehouse_id =$warehouse_id;
        $this->bill_number =$shipnumber.'&'. $billnumber.'&'.$dealercode ;

        $select_warehousename = Warehouse::where('id',$warehouse_id)->pluck('name')->first();
        $positionobj = PositionInWarehouse::where('id',$position_in_warehouse_id)
        ->where('warehouse_id',$warehouse_id)->get()->first();
        $positioninwarehouse_name ='';
        if($positionobj){$positioninwarehouse_name = $positionobj->name;}

        $lastIdx = $this->i;
        if($shipnumber!='0000000000'){
            $poItems = Hmspartreceiveplan::where('shipment_number',$shipnumber)
            ->where('head_code',$dealercode)
            //->where('allocated_qty','<>',0)
            ->get();
        }else{
            $poItems = Hmspartreceiveplan::where('part_type','26-Drop Shipment Order')
            ->where('invoice_number',$billnumber)
            ->where('head_code',$dealercode)
            //->where('allocated_qty','<>',0)
            ->get();
        }

            if($poItems->IsNotEmpty()){
                 //voi moi item trong PO
                 foreach($poItems as $item){
                    $poQty =isset($item->allocated_qty) ? (int) $item->allocated_qty : 0;
                    $code =$item->part_no;
                    $name =$item->part_name;
                    $order_number=isset($item->order_number) ? $item->order_number : '';

                    //get receipt qty so luong da nhan
                    $receiptQty = 0 ;
                    if (!empty($order_number)) {
                        # code...
                        $receipt = OrderDetail::where('order_number',$order_number)
                            ->where('type',EOrderDetail::TYPE_NHAP)
                            ->where('code',$code)->sum('quantity');
                        $receiptQty = isset($receipt) ? $receipt : 0;
                    }
                    $qty = $poQty -$receiptQty > 0 ? $poQty -$receiptQty :0 ;
                    $netprice =isset($item->receive_price) ? (int) $item->receive_price : 0;

                    array_push($this->accessoryNumber ,$code);
                    array_push($this->accessoryName ,$name);
                    array_push($this->warehouse ,$warehouse_id);
                    array_push($this->whName ,$select_warehousename);  //name of warehouse
                    array_push($this->whLocation ,$positioninwarehouse_name); //name of position
                    array_push($this->poQty ,$poQty);
                    array_push($this->receiptQty ,$receiptQty);
                    array_push($this->qty ,$qty);

                    array_push($this->stkqty ,$poQty -$receiptQty -$qty);

                    array_push($this->netPrice ,$netprice);
                    array_push($this->position_in_warehouse_id ,$position_in_warehouse_id);

                    array_push($this->order_number,$order_number);

                    $this->addItem($lastIdx);
                    $lastIdx +=1;
                }
            }

    }

    /***
     * add to row by select PO from parent
     * Get all avaible PO item which remain
     */
    public function addInputRowByPO($selectwarehouse,$positioninwarehouse,
    $supplierCode,$selectponumber,$receivedate,$autoconvert){
            $this->warehouse_id =$selectwarehouse;
            $this->ponumber =$selectponumber;
            $this->supplyCode = $supplierCode;
            $this->autoconvert =$autoconvert;

            if(isset($receivedate)){$this->receivedate =$receivedate;}

            $select_warehousename = Warehouse::where('id',$selectwarehouse)->pluck('name')->first();

            $positionobj = PositionInWarehouse::where('id',$positioninwarehouse)
            ->where('warehouse_id',$selectwarehouse)->get()->first();
            $positioninwarehouse_name ='';
            if($positionobj){
                $positioninwarehouse_name = $positionobj->name;
            }
            $lastIdx = $this->i;

            $poItems = HMSPartOrderPlanDetail::where('order_number',$selectponumber)
            ->where('dnp','<>',null)
            ->get();
            if($poItems->IsNotEmpty()){
                //voi moi item trong PO
                foreach($poItems as $item){
                    $poQty =isset($item->quantity_requested) ? (int) $item->quantity_requested : 0;
                    $code =$item->part_no;
                    $name =$item->part_description;
                    //get receipt qty so luong da nhan
                    $receiptQty = OrderDetail::where('order_number',$selectponumber)
                    ->where('type',3)
                    ->where('code',$code)->sum('quantity');
                    $receiptQty = (!empty($receiptQty)) ? $receiptQty : 0;
                    $qty = $poQty -$receiptQty > 0 ? $poQty -$receiptQty :0 ;
                    $netprice =isset($item->dnp) ? (int) $item->dnp : 0;

                    array_push($this->accessoryNumber ,$code);
                    array_push($this->accessoryName ,$name);
                    array_push($this->warehouse ,$selectwarehouse);
                    array_push($this->whName ,$select_warehousename);  //name of warehouse
                    array_push($this->whLocation ,$positioninwarehouse_name); //name of position
                    array_push($this->poQty ,$poQty);
                    array_push($this->receiptQty ,$receiptQty);
                    array_push($this->qty ,$qty);

                    array_push($this->stkqty , $poQty -$receiptQty - $qty);

                    array_push($this->netPrice ,$netprice);
                    array_push($this->position_in_warehouse_id ,$positioninwarehouse);

                    $this->addItem($lastIdx);
                    $lastIdx +=1;
                }
            }
    }



    /***
     * Them moi 1 row
     * tao doi tuong accessory can input
     * add data to re render
     */
    public function addInputRow($code,$name,$selectwarehouse,$positioninwarehouse,$qty,$netprice,
                                $supplierCode,$selectponumber,$receivedate,$autoconvert){
        if($code){
            $this->warehouse_id =$selectwarehouse;
            $this->ponumber =$selectponumber;
            $this->supplyCode = $supplierCode;
            $this->autoconvert =$autoconvert;

            if(isset($receivedate)){
                $this->receivedate =$receivedate;
            }

            $lastIdx = $this->i;

            $select_warehousename = Warehouse::where('id',$selectwarehouse)->pluck('name')->first();

            $positionobj = PositionInWarehouse::where('id',$positioninwarehouse)
            ->where('warehouse_id',$selectwarehouse)->get()->first();
            $positioninwarehouse_name ='';
            if($positionobj){
                $positioninwarehouse_name = $positionobj->name;
            }

            //$message = $positioninwarehouse;
            //$this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
            $poQty = 0;  //gia tri theo PO cua HVN
            $receiptQty = 0; // gia tri con lai theo PO cua HVN
            if($supplierCode==env('APP_HVNCODE')){
                 //check ma phu tung co ton tai trong PO ko
                //neu ko thi alert message va focus lai o ma pt
                $partInfo = HMSPartOrderPlanDetail::where('order_number',$selectponumber)
                ->where('part_no',$code)->get()->first();
                if($partInfo){
                    $poQty =(int) $partInfo->quantity_requested;
                }
                //get receipt qty so luong da nhan
                $receiptQty = OrderDetail::where('order_number',$selectponumber)
                ->where('type',3)
                ->where('code',$code)->sum('quantity');
            }
            $receiptQty = (isset($receiptQty) && $receiptQty !='') ? $receiptQty : 0;

            array_push($this->accessoryNumber ,$code);
            array_push($this->accessoryName ,$name);
            array_push($this->warehouse ,$selectwarehouse);
            array_push($this->whName ,$select_warehousename);  //name of warehouse
            array_push($this->whLocation ,$positioninwarehouse_name); //name of position
            array_push($this->poQty ,$poQty);
            array_push($this->receiptQty ,$receiptQty);
            array_push($this->qty ,$qty);
            array_push($this->stkqty ,$poQty -$receiptQty -$qty);

            array_push($this->netPrice ,$netprice);
            array_push($this->position_in_warehouse_id ,$positioninwarehouse);

            $this->addItem($lastIdx);
        }
    }

    /**
     * reset all list input
     */
    public function resetlistaccessories(){
        unset($this->inputs);
        $this->inputs =[];
        unset($this->accessoryNumber);
        $this->accessoryNumber=[];
        unset($this->accessoryName);
        $this->accessoryName =[];
        unset($this->warehouse);
        $this->warehouse=[];
        unset($this->whName);
        $this->whName=[];
        unset($this->whLocation);
        $this->whLocation=[];
        unset($this->poQty);
        $this->poQty=[];
        unset($this->receiptQty);
        $this->receiptQty=[];
        unset($this->qty);
        $this->qty=[];

        unset($this->stkqty);
        $this->stkqty=[];

        unset($this->netPrice);
        $this->netPrice=[];
        unset($this->position_in_warehouse_id);
        $this->position_in_warehouse_id=[];
        unset($this->order_number);
        $this->order_number=[];
    }

    /**
     * render form after action
     *
     */
    public function render()
    {
       // $this->contacts = Contact::all();
        //$this->accessoryName =$this->accessories;
        return view('livewire.component.list-input-receive-part');
    }
}
