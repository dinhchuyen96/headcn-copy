<?php

namespace App\Http\Livewire\Component;

use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Models\Accessories;
use App\Models\Accessory;
use App\Models\CategoryAccessory;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Http\Livewire\Base\BaseLive;
use App\Service\Community;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\DB;



class ListInputPartWholesale extends BaseLive
{

    public $totalstock = 0;
    public $totalsell = 0;

    public $addStatus = false;
    public $accessoryNumber = [];
    public $accessoryName = [];
    public $warehouse = [];
    public $whLocation = [];
    public $stockQty = [];
    public $qty = [];
    public $orgqty = [];
    public $netPrice = [];
    public $orderPrice = [];
    public $vatPrice = [];
    public $actPrice = [];
    public $product_id = []; // chua cac accessory id
    public $position_in_warehouse_id = [];


    public $avaliableAccessory = [];
    public $i = 0;
    public $inputs = [];


    public $warehouse_id;
    public $showstatus;
    public $updatestatus = false;
    public $order_id;

    public $businesstype; // Dinh nghia loai business la ban buon 1 - ban le 2

    public $customercode;
    public $customerphone;

    public $removeitems = [];
    public $isVirtual = 0;
    public $type;
    public $transactionDate;

    protected $listeners = [
        'setwarehouse' => 'setwarehouse',
        'getTransactionDate' => 'getTransactionDate',
        'onSelectAccessory' => 'onSelectAccessory',
        'addInputRow' => 'addInputRow',
        'createsaleorderdetail' => 'createsaleorderdetail',
        'checkhasorderdetail' => 'checkhasorderdetail',
        'resetlistaccessories' => 'resetlistaccessories',
        'setHeaderInfo' => 'setHeaderInfo',
        'setIsVirtualOrderToDetail' => 'setIsVirtualOrderToDetail'
    ];


    public function mount($type)
    {
        $this->type = $type;
        //$this->resetaccessaryNumber();
        //2. get selected warehouse from parent form
        if ($type == 1) {
            $this->businesstype = EOrderDetail::TYPE_BANBUON;
        } elseif ($type == 2) {
            $this->businesstype = EOrderDetail::TYPE_BANLE;
        } else { //defaul is ban buon
            $this->businesstype = EOrderDetail::TYPE_BANBUON;
        }

        if (isset($_GET['id'])) {
            $this->order_id = $_GET['id'];
            $this->updatestatus = true;
            $this->setComponentData($this->order_id);
        }

        if (isset($_GET['show'])) {
            $this->showstatus  = true;
            $this->updatestatus = false;
        }
    }

    /**
     * Response for emit set is vertual status of order
     */
    public function setIsVirtualOrderToDetail($isVirtual)
    {
        $this->isVirtual = $isVirtual;
    }

    /**
     * get all part info
     * which has stock > 0
     * in selected warehouse
     */
    public function getAvaibleAccessories($id)
    {
        $this->avaliableAccessory  = Accessory::where('quantity', '<>', 0)
            ->where('warehouse_id', $id)
            ->get();
    }

    /**
     * check user input detail ornot
     */
    public function checkhasorderdetail()
    {
    }
    public function setwarehouse($value)
    {
        $this->warehouse_id = $value;
        //reset avaible accessories
        //  $this->getAvaibleAccessories($value);
    }

    public function getTransactionDate($transactionDate)
    {
       $this->transactionDate = $transactionDate;
    }

    /**
     * set params from parent component
     *
     */
    public function setComponentData($order_id)
    {
        if ($order_id) {
            $this->order_id = $order_id;
            $order = Order::where('id', $order_id)->first();
            $this->customercode = isset($order->customer->code) ? $order->customer->code : '';
            $this->customerphone = isset($order->customer->phone) ? $order->customer->phone : '';

            $order_details = OrderDetail::where('order_id', $order_id)->get();
            if ($order_details) {
                foreach ($order_details as $item) {
                    array_push($this->accessoryNumber, $item->code);
                    array_push($this->accessoryName, $item->accessorie != null ? $item->accessorie->name : '');
                    array_push($this->warehouse, $item->warehouse_id);
                    array_push($this->whLocation, $item->positioninwarehouse != null ? $item->positioninwarehouse->name : '');
                    array_push($this->stockQty, $item->accessorie != null ? $item->accessorie->quantity : '');
                    array_push($this->qty, $item->quantity);
                    array_push($this->orgqty, $item->quantity); //set original qty

                    array_push($this->orderPrice, isset($item->accessorie->price) ? $item->accessorie->price : 0); //gia nhap theo gia cua accessory

                    array_push($this->netPrice, $item->listed_price);
                    array_push($this->vatPrice, $item->vat_price);
                    array_push($this->actPrice, $item->actual_price);
                    array_push($this->product_id, $item->product_id);
                    array_push($this->position_in_warehouse_id, $item->position_in_warehouse_id);
                }
            }
        }
    }

    /**
     * Create new order detail
     * loop via all child item
     */
    public function createsaleorderdetail($orderid)
    {



        $order_details = [];
        $order_details = Orderdetail::where('order_details.order_id', $orderid)->get();
        if ($order_details->isNotEmpty()) {
            foreach ($order_details as $item) {
                //1.restore accessory by update quantity
                $accessoryitem = Accessory::where('id', $item->product_id)->get()->first();
                if ($accessoryitem) {
                    $updateqty = (int)$accessoryitem->quantity + (int)$item->quantity;
                    //neu don hang pt la ban le
                    //va la don  hang ao thi ko tru ton kho
                    //else cho phep tru ton kho
                    if (!($this->isVirtual)) {
                        $accessoryitem->update(['quantity' => $updateqty]);
                    }

                    //2. xoa ban ghi order_details
                    $item->delete();
                }
            }
        }


        //tao thong tin order detail moi
        $total_items = 0;
        $total_order_amount = 0;
        foreach ($this->accessoryNumber as $key => $value) {
            //1. tao ban ghi detail order
            $order_detail = new OrderDetail();
            $order_detail->order_id = $orderid;
            $order_detail->price = (int)$this->actPrice[$key];
            $order_detail->code = $this->accessoryNumber[$key];
            $order_detail->quantity = (int)$this->qty[$key];
            $order_detail->supplier_type = 0;
            $order_detail->status = EOrderDetail::STATUS_SAVE_DRAFT;
            $order_detail->name = $this->accessoryName[$key];
            $order_detail->admin_id = auth()->id();
            $order_detail->category = EOrderDetail::CATE_ACCESSORY;
            $order_detail->type = $this->businesstype;
            $order_detail->listed_price = $this->netPrice[$key];
            $order_detail->vat_price = (int)$this->vatPrice[$key];
            $order_detail->actual_price = (int)$this->actPrice[$key];
            $order_detail->product_id = $this->product_id[$key];
            $order_detail->warehouse_id = $this->warehouse[$key];
            $order_detail->position_in_warehouse_id = $this->position_in_warehouse_id[$key];
            $order_detail->save();

            $total_items += 1;
            $total_order_amount += (int)$this->qty[$key] * (int)$this->actPrice[$key];

            //2.giam so luong ton kho tren accessory
            $accessoryitem = Accessory::where('id', $order_detail->product_id)->first();
            if ($accessoryitem) {
                $updateqty = (int)$accessoryitem->quantity - (int)$order_detail->quantity;

                //neu don hang pt la ban le
                //va la don  hang ao thi ko tru ton kho
                //else cho phep tru ton kho
                if (!($this->isVirtual)) {
                    $accessoryitem->update(['quantity' => $updateqty]);
                }
            }
        }

        $order = Order::where('id', $orderid)->first();
        // dd($order, $this->transactionDate, Carbon::createFromFormat('Y-m-d', $this->transactionDate));
        if ($order) {
            $order->update([
                'total_items' => $total_items,
                'total_money' => $total_order_amount,
                'created_at' => Carbon::createFromFormat('Y-m-d', $this->transactionDate)
            ]);
        }
    }


    //////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * add new item
     */
    public function addNew()
    {
        $lastindex = 1;
        addItem($lastindex);
    }

    /**
     * add new row to html table
     */
    public function addItem($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs, $i);
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
        unset($this->whLocation[$i]);
        unset($this->stockQty[$i]);
        unset($this->qty[$i]);
        unset($this->orgqty[$i]);


        unset($this->orderPrice[$i]);

        unset($this->netPrice[$i]);
        unset($this->vatPrice[$i]);
        unset($this->actPrice[$i]);

        unset($this->product_id[$i]);
        unset($this->position_in_warehouse_id[$i]);
    }


    /***
     * Them moi 1 row
     * tao doi tuong accessory can input
     * add data to re render
     */
    public function addInputRow($code, $selectwarehouse, $position_in_warehouse_id)
    {
        //1. check array list is set or not if not set then re-set

        $this->recreatearraylist();
        //check code ton tai hay chua
        $key = array_search($code, $this->accessoryNumber, true);
        $existing = false;
        if (json_encode($key) != 'false') {
            $existing = true;
            $message = $code . ' - đã có trong danh sách!';
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
        }

        if (!$existing) {
            $this->warehouse_id = $selectwarehouse;
            $lastIdx = $this->i;

            //lay thong tin gia ban de xuat neu co
            $partinfo = CategoryAccessory::where('code', $code)->first();
            $saleprice = 0;
            if ($partinfo) {
                $saleprice = $partinfo->netprice;
            }

            $item =  Accessory::leftJoin(
                "position_in_warehouse",
                'position_in_warehouse.id',
                '=',
                'accessories.position_in_warehouse_id',
                'position_in_warehouse.warehouse_id',
                '=',
                'accessories.warehouse_id'
            )
                ->where('accessories.code', $code)->where('accessories.warehouse_id', $selectwarehouse)
                ->where('accessories.position_in_warehouse_id', $position_in_warehouse_id)
                ->select('accessories.*', 'position_in_warehouse.name as position_name')
                ->first();

            if ($item) {
                array_push($this->accessoryNumber, isset($item->code) ? $item->code : 0);
                array_push($this->accessoryName, isset($item->name) ? $item->name : '');
                array_push($this->warehouse, isset($item->warehouse_id) ? $item->warehouse_id : 0);
                array_push($this->whLocation, isset($item->position_name) ? $item->position_name : '');
                array_push($this->stockQty, isset($item->quantity) ? $item->quantity : 0);
                array_push($this->qty, 1);
                array_push($this->orderPrice, isset($item->price) ? $item->price : 0);

                array_push($this->netPrice, $saleprice);
                array_push($this->vatPrice, $saleprice);
                array_push($this->actPrice, $saleprice);
                array_push($this->product_id, isset($item->id) ? $item->id : 0);
                array_push($this->position_in_warehouse_id, isset($item->position_in_warehouse_id) ? $item->position_in_warehouse_id : 0);
            }
            $this->addItem($lastIdx);
        }
    }


    /**
     * event raise when header change info
     */
    public function setHeaderInfo($customercode, $customerphone)
    {
        $this->customercode = $customercode;
        $this->customerphone = $customerphone;
    }

    /**
     * Check nhap Qty co dung
     */
    public function validateQty()
    {
        if (!empty($this->accessoryNumber)) {
            $partNos = array_unique($this->accessoryNumber);
            $saleQtys = [];
            foreach ($partNos as $partNumber) {
                $intPartSaleQty = 0;
                $partStockQty = 0;
                $currentStock = 0;
                $intPartSaleQtyOrg = 0;
                foreach ($this->accessoryNumber as $key => $value) {
                    if ($this->accessoryNumber[$key] == $partNumber) {
                        $partStockQty = isset($this->stockQty[$key]) ? $this->stockQty[$key] : 0;
                        $intPartSaleQty += isset($this->qty[$key]) ? $this->qty[$key] : 0;
                        $intPartSaleQtyOrg += isset($this->orgqty[$key]) ? $this->orgqty[$key] : 0;

                        $currentStockObj = Accessory::where('code', $partNumber)
                            ->where('warehouse_id', $this->warehouse[$key])
                            ->where('position_in_warehouse_id', $this->position_in_warehouse_id[$key])
                            ->select('quantity')
                            ->get()->first();
                        if ($currentStockObj) {
                            $currentStock
                                = (int)$currentStockObj->quantity;
                        }
                    }
                }

                if ($partStockQty + $intPartSaleQtyOrg - $intPartSaleQty < 0  || $currentStock + $intPartSaleQtyOrg - $intPartSaleQty < 0) {
                    $message = $partNumber . ' - nhập bán vượt quá tồn kho hiện có!';
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
                    return false;
                }
            }
        }
        return true;
    }

    /**
     *
     * validate input detail
     */
    public function validateinputdetail()
    {
        Validator::validate($this->all(), [
            'accessoryNumber.*'=>'required',
            'accessoryName.*'=>'required',
            'whLocation.*'=>'required',
            'qty.*'=>'required|integer|gt:0|digits_between:1,9',
//            'vatPrice.*'=>'required|integer|gt:0|digits_between:1,9',
            'actPrice.*'=>'required|integer|gt:0'
        ],
            ['accessoryNumber.*.required'=>'Mã phụ tùng phải nhập',
                'accessoryName.*.required'=>'Tên phụ tùng phải nhập',
                'qty.*.required'=>'phải nhập cột số lượng',
                'qty.*.integer'=>'Số lượng phải kiểu số',
                'qty.*.gt'=>'Số lượng phải lớn hơn 0',
                'qty.*.digits_between'=>'Số lượng chỉ được nhập từ 1-999999999',
//                'vatPrice.*.required'=>'Phải nhập cột đơn giá',
//                'vatPrice.*.integer'=>'Đơn giá phải kiểu số',
//                'vatPrice.*.gt'=>'Đơn giá phải lớn hơn 0',
//                'vatPrice.*.digits_between'=>'Đơn giá chỉ được nhập từ 1-999999999',
                'actPrice.*.required'=>'Phải nhập cột đơn giá',
                'actPrice.*.integer'=>'Đơn giá phải kiểu số',
                'actPrice.*.gt'=>'Đơn giá phải lớn hơn 0',
                'actPrice.*.digits_between'=>'Đơn giá chỉ được nhập từ 1-999999999'
            ]);

    }
    /**
     * handle user click tạo don hoac cap nhat
     */
    public function createorder()
    {
        //validate input header
//        $this->emit('checkinputheader');
    
        if(!isset($this->customercode) && !isset($this->customerphone)){
            $message = 'Bạn chưa tạo thông tin khách hàng!vui lòng tạo khách hàng trước';
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
        } else {

            //check if customer exist
            $customer  = Customer::where('code', $this->customercode)->orwhere('phone', $this->customerphone)->get()->first();
            if ($customer) {
                //1. check detail exist ?
                //if not exist then warning
                //check da tao chi tiet hay chua
                if (empty($this->accessoryNumber)) {
                    $message = 'Bạn chưa tạo chi tiết đơn hàng!';
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
                }else{
                    $this->validateinputdetail();
                    if (!$this->validateQty()) {
                        return;
                    }

                    //create or update order header
                    //if is not editing then create new order
                    //else update order
                    if (!$this->updatestatus) {
                        $order = new Order();
                        $order->admin_id = auth()->id();
                        $order->category = EOrder::CATE_ACCESSORY;
                        $order->status = EOrder::STATUS_UNPAID;
                        $order->order_type = EOrder::ORDER_TYPE_SELL;
                        $order->type = $this->businesstype;
                        $order->customer_id = $customer->id;
                        $order->isvirtual = $this->isVirtual;
                        $order->save();
                    } else {
                        //update existing order
                        $order = Order::where('id', $this->order_id)->first();
                        if ($order) {
                            $order->admin_id = auth()->id();
                            $order->category = EOrder::CATE_ACCESSORY;
                            $order->status = EOrder::STATUS_UNPAID;
                            $order->order_type = EOrder::ORDER_TYPE_SELL;
                            $order->type = $this->businesstype;
                            $order->customer_id = $customer->id;
                            $order->isvirtual = $this->isVirtual;
                            $order->update();
                        }
                    }

                    //emit order detail
                    if ($order->id) {
                        $id = $order->id;
                        $this->createsaleorderdetail($id);
                        //show message finish
                        OrderDetail::where('order_id', $id)->where('admin_id', auth()->id())->update([
                            'status' => EOrderDetail::STATUS_SAVED,
                            'created_at' => $this->transactionDate
                        ]);
                        $existingorderdetails = OrderDetail::where('order_id', $id)->get();
                        // dd($existingorderdetails);
                        if ($existingorderdetails->isNotEmpty()) {
                            if (!$this->updatestatus) {

                                $message = "Đơn hàng :" . $id . " được tạo thành công";
                            } else {
                                $message = "Đơn hàng :" . $id . " được cập nhật thành công";
                            }
                            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => $message]);
                            $this->dispatchBrowserEvent('hello', ['url' => route('phutung.inhoadonbanle.index', ['id' => $id])]);
                        } else {
                            $message = "Tạo  chi tiết đơn hàng lỗi, vui lòng thử lại!";
                            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
                            //xoa order tao rac di
                            $order->delete();
                        }
                    } else {
                        $message = "Tạo đơn hàng lỗi, vui lòng thử lại!";
                        $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
                    }

                    //reset input
                    $this->emit('resetInput');
                    $this->releaseheaderinfo();
                    $this->resetlistaccessories();
                }
            } else {
                //show message waring customer not exist
                $message = "Mã khách hàng chưa tồn tại, vui lòng tạo khách hàng trước!";
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
                //return;
            }
        }
    }

    // handle user click in phiếu
    public function printorder()
    {

        $this->emit('checkinputheader');
        if (!$this->customercode && !$this->customerphone) {
            $message = 'Bạn chưa tạo thông tin khách hàng!vui lòng tạo khách hàng trước';
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
        } else {
            // logic xử lý cách in phiếu TODO
            $message = 'In hóa đơn thành công';
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => $message]);
        }
    }

    public function releaseheaderinfo()
    {
        $this->customercode = null;
        $this->customerphone = null;
        $this->customer = null;
    }

    /**
     * reset all list input
     */
    public function resetlistaccessories()
    {
        unset($this->inputs);
        unset($this->accessoryNumber);
        unset($this->accessoryName);
        unset($this->warehouse);
        unset($this->whLocation);
        unset($this->stockQty);
        unset($this->qty);

        unset($this->orderPrice);
        unset($this->netPrice);
        unset($this->vatPrice);
        unset($this->actPrice);

        unset($this->product_id);
        unset($this->position_in_warehouse_id);
    }
    /**
     * recreate all array list using for list input
     */
    public function recreatearraylist()
    {
        if (!isset($this->inputs)) $this->inputs = [];
        if (!isset($this->accessoryNumber)) $this->accessoryNumber = [];
        if (!isset($this->accessoryName)) $this->accessoryName = [];
        if (!isset($this->warehouse)) $this->warehouse = [];
        if (!isset($this->whLocation)) $this->whLocation = [];
        if (!isset($this->stockQty)) $this->stockQty = [];
        if (!isset($this->qty)) $this->qty = [];

        if (!isset($this->orderPrice)) $this->orderPrice = [];
        if (!isset($this->netPrice)) $this->netPrice = [];
        if (!isset($this->vatPrice)) $this->vatPrice = [];
        if (!isset($this->actPrice)) $this->actPrice = [];

        if (!isset($this->product_id)) $this->product_id = [];
        if (!isset($this->position_in_warehouse_id)) $this->position_in_warehouse_id = [];
    }

    /**
     * render form after action
     *
     */
    public function render()
    {
        // $this->contacts = Contact::all();
        //$this->accessoryName =$this->accessories;
        if (isset($this->accessoryNumber) && !empty($this->accessoryNumber)) {
            $totalstock  = 0;
            $totalsell  = 0;
            # code...
            foreach ($this->accessoryNumber as $key => $value) {
                $totalstock += $this->stockQty[$key];
                $totalsell += $this->qty[$key];
            }
            $this->totalstock = $totalstock;
            $this->totalsell = $totalsell;
        }
        $type = $this->type;
        return view('livewire.component.list-input-part-wholesale', compact('type'));
    }
}
