<?php

namespace App\Http\Livewire\Phutung;

use App\Enum\EOrderDetail;
use App\Exports\DsDonHangExport;
use App\Http\Livewire\Base\BaseLive;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderDetail;
use App\Models\Accessory;
use App\Models\Warehouse;
use App\Models\PositionInWarehouse;
use App\Models\ReturnItem;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dsdonhang extends BaseLive
{
    public $customerSelectedId;
    public $orderSelectedId;
    public $keyword;
    public $showAdvancesearch = 0;
    public $searchPartNo;

    public $searchName;
    public $searchType;
    public $searchAddr;
    public $searchStatus;
    public $searchSupplier;
    public $searchFromDate;
    public $searchToDate;
    public $perPage = 10;

    public $chkIsVirtual = true;
    public $chkIsReal = true;

    public $listSelected = [];

    protected $listeners = ['setfromDate', 'settoDate','getreturnparts','getPositionInWarehouses', 'validateQuantity'];

    public $returncustomerid ;
    public $listreturnpart = [];
    public $returnpartid;
    public $returnpartcode;
    public $returnpartname;
    public $returnqty = 0;
    public $buyQty = 0;
    public $returnprice = 0;
    public $returndescription ;
    public $listreturnwarehouse=[];
    public $returnwarehouseid;
    public $listreturnposition=[];
    public $returnpositionid;
    public $returnsupplierid;
    public $returnorderid;
    public $PONumber = 0;

    public $validCheckReturn;
    public function mount()
    {
        ///get list cusstomer
        $this->listreturnwarehouse =$this->getWarehouses();

        $start = Carbon::now();
        $end = Carbon::now();
        $this->searchFromDate = $start;
        $this->searchToDate = $end;
    }

    public function render()
    {

        $this->getreturnparts();
        $this->getselectedreturnpart();
        $this->getPositionInWarehouses();


        $query = Order::with('customer');
        $query = $this->searchQuery($query);
        if ($this->key_name2) {
            $query->orderBy($this->key_name2, $this->sortingName2);
        }

        $totalQty = $query->sum('order_details.quantity');
        $totalAmount = $query->sum(DB::raw('order_details.quantity * order_details.actual_price'));

        $this->dispatchBrowserEvent('select2Customer');
        $data = $query->paginate($this->perPage);
        return view('livewire.phutung.dsdonhang', [
            'data' => $data,
            'totalQty' => $totalQty,
            'totalAmount' => $totalAmount,
        ]);
    }

    /**
     *
     */
    public function getWarehouses(){
        try {
            //code...
            $lst = [];
            $lst = Warehouse::whereNull('deleted_at')
            ->select('id','name')->get(); // it will get the entire table
            return $lst;
        } catch (Exception $ex) {
            //throw $th;
            Log::info($ex);
            return null;
        }
    }

    public function getPositionInWarehouses(){
        try {
            if (isset($this->returnwarehouseid) &&!empty($this->returnwarehouseid)) {
                # code...
                //code...
                $lst = PositionInWarehouse::where('warehouse_id', $this->returnwarehouseid)
                ->select('id','name')->get();
                $this->listreturnposition = $lst;
            }
        } catch (Exception $ex) {
            //throw $th;
            Log::info($ex);
        }
    }

    //getCustomers
    public function getCustomers(){
        try {
            //code...
            $lst = [];
            $lst = Customer::orderBy('name','asc')
            ->select('id','name','phone')
            ->get();
            return $lst;
        } catch (Exception $ex) {
            //throw $th;
            Log::info($ex);
            return null;
        }
    }

    /**
     * handle when user slected part
     * return price
     * warehouse position
     */
    public function getselectedreturnpart(){
      
        if (isset($this->returnpartid) && !empty($this->returnpartid)) {

            # code...
            $returnpart = Accessory::find($this->returnpartid);
            
            if (isset($returnpart)) {
                # code...
                $this->returnwarehouseid =$returnpart->warehouse_id;
                $this->returnpositionid=$returnpart->position_in_warehouse_id;
                $this->returnprice =$returnpart->price;
                $this->returnsupplierid = $returnpart->supplier_id;
                $this->returnpartcode =   $returnpart->code;
                $this->returnpartname =   $returnpart->name;
            }
            $returnorder = DB::table('order_details')
            ->join('orders',function($join){
                $join->on('orders.id','=','order_details.order_id');
                $join->where('orders.customer_id','=',$this->returncustomerid);
                $join->where('order_details.product_id','=',$this->returnpartid);
                $join->where('order_details.category', '=', 1);
            })
            ->select('orders.*', 'orders.id','order_details.actual_price', 'orders.total_items', 'order_details.order_number')->first();
            
            if (isset($returnorder)) {
                # code...
                $this->returnorderid = $returnorder->id;
                $this->returnprice =$returnorder->actual_price;
                $this->buyQty = $returnorder->total_items;
                $this->PONumber = $returnorder->order_number;
            }
        }
    }

    /**
     *
     */
    public function getreturnparts(){
        try {
            //code...
            if(isset($this->returncustomerid) && !empty($this->returncustomerid)){
                $lst = DB::table('accessories')
                ->join('order_details',function($join){
                    $join->on('order_details.code','=','accessories.code');
                    $join->on('order_details.product_id','=','accessories.id');
                })
                ->join('orders', function($join){
                    $join->on('orders.id','=','order_details.order_id');
                    $join->where('orders.category', '=', '1');
                    $join->whereIn('orders.type', [1, 2]);
                    $join->where('orders.customer_id','=',$this->returncustomerid);
                })
                ->select('accessories.id as id','accessories.code as code','accessories.name as name')
                ->get();
                
                $this->listreturnpart = $lst;
            }

        } catch (Exception $e) {
            //throw $th;
            Log::info($e);
            return null;
        }

    }

    /**
     * validate in put and comment
     */
    public function doreturnpart(){


           //1. validate input
           $this->validateinput();

           DB::beginTransaction();

           $message = '';
           try {
                //import to Order table , order detail table
               //code...
                $returnitem = new ReturnItem();
                $returnitem->item_id = $this->returnpartid;
                $returnitem->customer_id = $this->returncustomerid;
                $returnitem->item_type = 1 ; //phu tung
                $returnitem->ref_order_id =isset($this->returnorderid) ? $this->returnorderid : 0;
                $returnitem->item_qty = $this->returnqty;
                $returnitem->item_price = $this->returnprice;
                $returnitem->warehouse_id = $this->returnwarehouseid;
                $returnitem->paid_status = 0; // default chua thanh toan;
                $returnitem->position_in_warehouse_id = $this->returnpositionid;
                $returnitem->save();

                // Tạo hóa đơn nhập lại đon hảng ở bảng orders
                $order = new Order();
                $order->admin_id = auth()->id();
                $order->category = 1;
                $order->order_type = 2;
                $order->type = 3;
                $order->order_no = 'RETURN_' . $this->returnorderid;
                $order->supplier_id = 0; // Default HVN
                $order->warehouse_id = $this->returnwarehouseid;
                $order->bill_number = null;
                $order->save();

                $returnOrderId = $order->id;

                //cap nhat order detail
                $orderDetail = null;
                if($returnOrderId){
                   $orderDetail = $this->createOrderDetail($returnOrderId,$this->receivedate);
                }else{
                    $message = "Tạo đơn hàng lỗi, vui lòng thử lại!";
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $message]);
                }

                //cap nhat acccessory tang len
                $accessoryitem =  Accessory::find($this->returnpartid);
                $accessoryitem->quantity +=$this->returnqty;
                $accessoryitem->quantity +=$this->returnqty;
                $accessoryitem->updated_at = carbon::now();
                $accessoryitem->save();

                if($orderDetail){
                    $message = 'Trả lại phụ tùng thành công!';
                    $this->dispatchBrowserEvent('show-toast', ["type" => "success", "message" => $message]);
                }else{
                    $message = 'Trả lại phụ tùng không thành công!';
                    $this->dispatchBrowserEvent('show-toast', ["type" => "error", "message" => $message]);
                    $order->delete();
                }
                DB::commit();
             
           } catch (Exception $e) {
               //throw $th;
               Log::info($e);
               DB::rollback();
               $message = 'Trả lại phụ tùng không thành công!';
               $this->dispatchBrowserEvent('show-toast', ["type" => "error", "message" => $message]);
           }finally{
               $this->returncustomerid = '';
               $this->returnpartid = 0;
               $this->returndescription = '';
               $this->returnwarehouseid = '';
               $this->returnpositionid = '';
               $this->returnqty = 0;
               $this->listreturnpart = null;
               $this->listreturnposition = null;
           }
    }

    /**
     * Create order detail
     * @param int $orderid
     * @return object
     */
    public function createOrderDetail($orderid)
    {
             $order_detail = new OrderDetail();
             $order_detail->order_id = $orderid;
             $order_detail->price =  $this->returnprice;
             $order_detail->code =  $this->returnpartcode;
             $order_detail->quantity = $this->returnqty;
             $order_detail->supplier_type = 0;
             $order_detail->status = EOrderDetail::STATUS_SAVED;
             $order_detail->name =  $this->returnpartname;
             $order_detail->admin_id = auth()->id();
             $order_detail->category = EOrderDetail::CATE_ACCESSORY;
             $order_detail->type = 3;
             $order_detail->listed_price = $this->returnprice;
             $order_detail->actual_price =  $this->returnprice;

             $order_detail->order_number = $this->PONumber ;
             $order_detail->buy_date = Carbon::now()->toDateString() ;
             $order_detail->product_id = $this->returnpartid;
             $order_detail->warehouse_id =  $this->returnwarehouseid ;
             $order_detail->position_in_warehouse_id =$this->returnpositionid ;
             $order_detail->save();

             return $order_detail;
    }

    public function validateQuantity()
    {
        //1 require, 2 tk nhan va gui khac nhau , 3 note require , 4 user chuyen require
        if($this->returnqty > $this->buyQty) {
            $message = 'Số lượng trả lại không được lớn hơn số lượng đã mua';
            $this->dispatchBrowserEvent('show-toast', ["type" => "error", "message" => $message]);
            $this->validCheckReturn = false;

        } else {
            $this->validCheckReturn = true;
        }
    }

    public function validateinput(){
     

        $this->validate([
            'returncustomerid' => 'required',
            'returnpartid' => 'required',
            'returnqty' => 'required|integer|gt:0|digits_between:1,11',
            'returndescription' => 'required|max:255',
            'returnprice' => 'required|integer|gt:0|digits_between:1,11',
        ], [
            'returncustomerid.required' => 'Bạn chưa chọn khách hàng',
            'returnpartid.required' => 'Bạn chưa chọn phụ tùng',
            'returnqty.required' => 'Số lượng là bắt buộc',
            'returnqty.integer' => 'Số lượng là kiểu số',
            'returnqty.gt' => 'Số lượng lớn hơn 0',
            'returnqty.digits_between' => 'Số lượng nhỏ hơn 999999999',
            'returndescription.required' => 'Bạn chưa nhập ghi chú',
            'returndescription.max' => 'Ghi chú không quá 255 ký tự',
            'returnprice.integer' => 'Giá nhập là kiểu số',
            'returnprice.gt' => 'Giá nhập lớn hơn 0',
            'returnprice.digits_between' => 'Giá nhập nhỏ hơn 999999999',
        ], []);
    }

    public function searchQuery($query)
    {
        $query->where('orders.category', '=', '1')->whereIn('orders.type', [1, 2]);
        $query->join('order_details', 'orders.id', '=', 'order_details.order_id');
        $query->leftJoin('customers', 'customers.id', '=', 'orders.customer_id');

        if (isset($this->keyword) && !empty(($this->keyword))) {
            # code...
            $query->where('order_details.code', 'like', trim($this->keyword) . '%');
            $query->orWhere('order_details.name', 'like', trim($this->keyword) . '%');
            $query->orWhere('customers.name', 'like', trim($this->keyword) . '%');
            $query->orWhere('customers.address', 'like', trim($this->keyword) . '%');
        }

        if (isset($this->searchName))
            $query->where('customers.name', 'like', trim($this->searchName) . '%');
        if ($this->searchType)
            $query->where('orders.type', '=', $this->searchType);
        if ($this->searchStatus)
            $query->where('orders.status', '=', $this->searchStatus);
        if (isset($this->searchPartNo))
            $query->where('order_details.code', 'like', trim($this->searchPartNo) . '%');

        if (!empty($this->searchFromDate)) {
            $query->whereDate('orders.created_at', '>=', $this->searchFromDate);
        }
        if (!empty($this->searchToDate)) {
            $query->whereDate('orders.created_at', '<=', $this->searchToDate);
        }

        if ($this->chkIsVirtual && !$this->chkIsReal) {
            $query->where('orders.isvirtual', '=', 1);
        } elseif (!$this->chkIsVirtual && $this->chkIsReal) {
            $query->where('orders.isvirtual', '=', 0);
        }
        $query->orderBy('orders.id', 'ASC');

        $query->select(
            'orders.*',
            DB::raw('order_details.code as part_no'),
            DB::raw('order_details.name as part_name'),
            DB::raw('order_details.quantity as qty'),
            DB::raw('order_details.actual_price'),
            DB::raw('order_details.quantity*order_details.actual_price as amount')
        );
        return $query;
    }
    public function setfromDate($time)
    {
        $this->searchFromDate = date('Y-m-d', strtotime($time['fromDate']));
    }
    public function settoDate($time)
    {
        $this->searchToDate = date('Y-m-d', strtotime($time['toDate']));
    }
    public function resetSearch()
    {
        $this->searchName = "";
        $this->searchType = "";
        $this->searchAddr = "";
        $this->searchStatus = "";
        $this->searchSupplier = "";

        $start = Carbon::now();
        $end = Carbon::now();
        $this->searchFromDate = $start;
        $this->searchToDate = $end;

        //$this->emit('resetDateKendo');
    }
    public function showMessage()
    {
        $this->dispatchBrowserEvent('show-toast', ["type" => "error", "message" => "Đơn hàng đã thanh toán không được xóa"]);
    }
    /**
     * handle delete action
     */
    public function delete()
    {
        DB::beginTransaction();
        $message = '';
        try {
            $orderdetails =  OrderDetail::where('order_id', $this->deleteId)
                ->where('deleted_at', null)
                ->get();
            foreach ($orderdetails as $item) {
                $productId = $item->product_id;
                //update lai stock
                $accessory = Accessory::findOrFail($productId);
                if ($accessory) {
                    $accessory->quantity += $item->quantity;
                    $accessory->save();
                }
                //delete item detail
                $item->delete();
            }
            Order::findOrFail($this->deleteId)->delete();
            DB::commit();
            $message = 'Xóa thành công';
            $this->dispatchBrowserEvent('show-toast', ["type" => "success", "message" => $message]);
        } catch (\Exception $ex) {
            DB::rollback();
            $message = 'Xóa dữ liệu không thành công !';
            $this->dispatchBrowserEvent('show-toast', ["type" => "error", "message" => $message]);
        } finally {
        }
    }
    public function export()
    {
        $query = Order::query();
        $query = $this->searchQuery($query);
        $data = $query->get();
        if ($data->count() == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'warning', 'message' => 'Ko có bản ghi nào!']);
            $this->emit('close-modal-delete');
        } else {
            $this->emit('close-modal-delete');
            return Excel::download(new DsDonHangExport($data), 'dsdonhang_' . date('Y-m-d-His') . '.xlsx');
        }
    }

    public function simpleSearch()
    {
    }

    public function updatedlistSelected()
    {
        if (count($this->listSelected) > 0) {
            $firstSelected = $this->listSelected[count($this->listSelected) - 1];
            $this->listSelected = [];
            $this->listSelected[] = $firstSelected;
            $this->customerSelectedId = explode('_', $firstSelected)[0];
            $this->orderSelectedId = explode('_', $firstSelected)[1];
        }
    }
}
