<?php

namespace App\Http\Livewire\Gift;

use App\Http\Livewire\Base\BaseLive;
use Illuminate\Support\Facades\Auth;
use App\Models\GiftMaster;
use App\Models\GiftBalance;
use App\Models\GiftTransaction;
use App\Models\GiftTransactionOrder;
use App\Models\GiftWarehouse;
use App\Models\GiftPositionInWarehouse;
use App\Exports\GiftExport;
use App\Models\Warehouse;
use App\Models\PositionInWarehouse;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\Customer;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Carbon\Carbon;

class GiftList extends BaseLive
{
    public $keyword;

    public $giftcode;
    public $giftname;
    public $rate;

    public $selectgift;
    public $selectgiftout ;
    public $selectorder;
    public $selectorderlist= [];
    public $selectgiftlist=[];
    public $warehouselist  = [];
    public $positionlist  = [];
    public $selectwarehousein;
    public $selectpositionin;
    public $stockindate;
    public $stockoutdate;
    public $selectwarehouseout;
    public $selectpositionout;
    public $stockinqty =0;
    public $stockoutqty = 0;
    public $stockoutnote;
    public $customerPhone;
    public $customerorders= [];
    public $selectedOrders=[];
    public $selectcustomerinfo = "";

    public $selectquatangtab = "true" ;
    public $selectordertab ="false";
    public $quatangnavlink = true ;
    public $ordernavlink =false;
    public $stockoutpoint = 0;


    protected $listeners = ['setstockindate','setstockoutdate',
     'getCustomerOrders', 'setSelectedOrder','unsetSelectedOrder'];



     public function setSelectedOrder($chkorderid){
        if (isset($this->selectedOrders)) {
            # code...
            array_push($this->selectedOrders,$chkorderid);
        }
     }
     public function unsetSelectedOrder($chkorderid){
        if (isset($this->selectedOrders)) {
            # code...
            array_push($this->selectedOrders,$chkorderid);
        }
     }

    public function mount()
    {
        $this->stockoutdate = reFormatDate(now(),'Y-m-d');
        $this->key_name = 'id';
        $this->sortingName = 'asc';
        $this->stockindate=reFormatDate(now(),'Y-m-d');
    }
    public function render()
    {
        $this->dispatchBrowserEvent('setSelect2');

        //1. get gift list
        $this->getGiftList();
        $this->getWarehouseList();
        $this->getPositionList();

        $this->getCustomerOrders();

        $query = GiftMaster::query();
        $query->whereNull('gift_master.deleted_at');
        $query ->leftJoin('gift_balance',function($join){
            $join->on('gift_balance.gift_id','gift_master.id');
        });
        $query ->join('gift_warehouse',function($join){
            $join->on('gift_warehouse.id','gift_balance.warehouse_id');
        });
        $query ->leftJoin('gift_position_in_warehouse',function($join){
            $join->on('gift_position_in_warehouse.id','gift_balance.position_in_warehouse_id');
        });
        if ($this->keyword) {
            $query->where('gift_master.name', 'like', $this->keyword.'%');
            $query->orWhere('gift_master.code', 'like', $this->keyword.'%');
        }
        $query->select('gift_master.id',
        'gift_master.code','gift_master.name','gift_master.rate',
        DB::raw('gift_warehouse.id as warehouse_id'),
        DB::raw('gift_warehouse.name as warehouse_name'),
        DB::raw('gift_position_in_warehouse.id as position_id'),
        DB::raw('gift_position_in_warehouse.name as position_name'),
        DB::raw('COALESCE(gift_balance.qty,0) as qty')
        );

        $totalqty = $query->sum('qty');

        $data = $query->orderBy($this->key_name, $this->sortingName)
        ->paginate($this->perPage);


        $this->updateUI();

        // dd($data);
        return view('livewire.gift.gift-list',
                ['data' => $data,'totalqty'=>$totalqty
                ]);
    }

    public function updateUI()
    {
        $this->dispatchBrowserEvent('select2Customer');
        $this->dispatchBrowserEvent('setUpdateDatepicker');
    }

    public function delete()
    {
        $gift = Gift::findOrFail($this->deleteId);
        $gift->delete();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa thành công']);
    }
    public function export()
    {
        $gift = Gift::all();
        if ($gift->count() == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'warning', 'message' => 'Ko có bản ghi nào!']);
            $this->emit('close-modal-export');
        } else {
            return Excel::download(new GiftExport, 'quatang_' . date('Y-m-d-His') . '.xlsx');
        }
    }


    public function setstockindate($time)
    {
         $this->stockindate = date('Y-m-d', strtotime($time['stockindate']));
    }
    public function setstockoutdate($time)
    {
         $this->stockoutdate = date('Y-m-d', strtotime($time['stockoutdate']));
    }

    /**
     * handle create new gift
     * add to gift master
     */
    public function createNewGift(){
        //1 validate
        $this->validate([
            'giftcode' => 'required|max:50',
            'giftname' => 'required|max:500',
            'rate' => 'required|numeric|min:1'

        ], [
            'giftcode.required' => 'mã quà bắt buộc',
            'giftname.required' => 'tên quà bắt buộc',
            'rate.required' => 'số điểm đổi bắt buộc',
            'rate.numeric' => 'số điểm đổi phải là số',
            'rate.min' => 'số điểm đổi phải lớn hơn 0',
            'giftcode.max' => 'mã quà không quá 50 kí tự',
            'giftname.max' => 'tên quà không quá 50 kí tự'
        ], []);
        //create new
        try {
            //code...
            $gift = new GiftMaster();
            $gift->code = $this->giftcode ;
            $gift->name = $this->giftname ;
            $gift->rate = $this->rate ;
            $gift->save();


            $this->dispatchBrowserEvent('closeModalNewGift');
            $this->dispatchBrowserEvent('show-toast',
            ['type' => 'success', 'message' => 'Thêm quà tặng thành công']);
        } catch (Exception $e) {
            //throw $th;
            Log::info($e);
            $this->dispatchBrowserEvent('show-toast',
            ['type' => 'error', 'message' => 'Thêm quà tặng thất bại']);
        }finally{
            $this->giftcode = '';
            $this->giftname = '';
            $this->rate  = '';
        }
    }


    /**
     * handle when user select gif
     * automatic set warehouse and position which has much stock
     */
    public function updatedselectgiftout(){
        $selectgiftout =$this->selectgiftout;
        if (isset($selectgiftout)) {
            # code...
            $warehouse = DB::table('gift_balance')
            ->whereNull('deleted_at')
            ->select(DB::raw('max(qty) as quantity'),
            'gift_id','warehouse_id','position_in_warehouse_id')
            ->groupBy('gift_id','warehouse_id','position_in_warehouse_id')
            ->having('gift_id','=',$selectgiftout)
            ->first();
            if (isset($warehouse)) {
                # code...
                $this->selectwarehouseout = $warehouse->warehouse_id;
                $this->selectpositionout = $warehouse->position_in_warehouse_id;
            }
        }
    }

    /**handle simple search */
    public function simpleSearch(){
        if (!empty($this->keyword)) {
            # code...
        }
    }


    /**
     * validate stockout info
     */
    public function validateStockout(){
        $availablegift = [];
        $availablegift = GiftBalance::whereNull('deleted_at')
        ->where('gift_id','=',$this->selectgiftout)
        ->where('warehouse_id',$this->selectwarehouseout)
        ->where('qty', '>', 0)
        ->pluck('gift_id') ->toArray();


        $selectgiftValidator = ['required', Rule::in($availablegift)];
        //1. validate
        $this->validate([
            'selectgiftout' => $selectgiftValidator,
            'selectwarehouseout' => 'required',
            'stockoutqty' => 'required|numeric|min:1',
            'stockoutnote'=> 'required|max:255'
        ], [
            'selectgiftout.required' => 'vui lòng chọn quà tặng',
            'selectgiftout.in' => 'Quà tặng không còn đủ tồn kho',
            'selectwarehouseout.required' => 'vui lòng chọn kho',
            'stockoutqty.required' => 'vui lòng nhập số lượng',
            'stockoutqty.numeric' => 'số lượng phải là số',
            'stockoutqty.min' => 'số lượng nhập phải lớn hơn 0',
            'stockoutnote.required' => 'Thông tin lí do bắt buộc',
            'stockoutnote.max' => 'lí do không quá 255 kí tự',
        ], []);


         //check khach hang ko du diem
         $selectcustomervalidator = [];
         $stockoutpoint = 0 ;
         if (isset($customerPhone)) {
             # code...
             $giftmaster = GiftMaster::whereNull('deleted_at')
             -> where('id',$this->selectgiftout) ->first();
             if (isset($giftmaster)) {
                 # code...
                 $stockoutpoint = $giftmaster->rate  * $stockoutqty ;
                 $this->stockoutpoint = $stockoutpoint;
             }

             $validcustomer = Customer::where('phone' , '=' , $customerPhone)
             ->where('point' , '>', 0)
             ->where('point', '>=',$stockoutpoint)
             ->pluck('phone')->toArray();

            $selectcustomervalidator = [Rule::in($validcustomer)];
            $this->validate([
                'customerPhone' => $selectcustomervalidator
            ],
            [
                'customerPhone.in' => 'Điểm của khách hàng không đủ',
            ]);
         }
         //end check

    }

    /**
     * Handle stockout
     * record transaction history
     * deduct stock gift
     */
    public function doStockOut(){

        $this->validateStockout();
        //2. do stock out
        DB::beginTransaction();
        $message = '';
        try {
            $customer_id =null;
            if (isset($this->customerPhone)) {
                # code...
                $customer = Customer::where('phone' , '=' , $this->customerPhone)->first() ;
                $customer_id =isset($customer) ? $customer->id :null;

                //update stockout point reduce
                $customer->point += -1* $this->stockoutpoint;
                $customer->save();
            }

            //2.1 ghi nhan transaction
            $gifttransaction = new GiftTransaction();
            $gifttransaction->trans_date = $this->stockindate;
            $gifttransaction->trans_type = 2;
            $gifttransaction->note = $this->stockoutnote;
            $gifttransaction->from_warehouse_id =$this->selectwarehouseout;
            $gifttransaction->from_position_in_warehouse_id =$this->selectpositionout;
            $gifttransaction->gift_id = $this->selectgiftout;
            $gifttransaction->qty = $this->stockoutqty;
            $gifttransaction->created_by = Auth::user()->id;
            $gifttransaction->updated_by = Auth::user()->id;
            $gifttransaction->created_at = Carbon::now();
            $gifttransaction->updated_at = Carbon::now();

            //set customer id neu user select customer
            if (isset($customer_id)) {
                # code...
                $gifttransaction->customer_id = $customer_id;
            }

            $gifttransaction->save();

            //1.2 do gift transaction order
            $trans_id = $gifttransaction->id;
            if (isset($this->selectedOrders) && count($this->selectedOrders) > 0) {
                # code...
                $selectedOrders = array_unique($this->selectedOrders);
                foreach ($selectedOrders as $key => $value) {
                    # code...
                    $gifttransorder  = new GiftTransactionOrder();
                    $gifttransorder->trans_id = $trans_id;
                    $gifttransorder->order_id = $value;
                    $gifttransorder->created_by = Auth::user()->id;
                    $gifttransorder->updated_by = Auth::user()->id;
                    $gifttransorder->created_at = Carbon::now();
                    $gifttransorder->updated_at = Carbon::now();
                    $gifttransorder->save();
                }
            }

            //2.2 ghi nhan gift balance - stockoutqty
            $giftbalance = GiftBalance::whereNull('deleted_at')
            ->where('gift_id','=',$this->selectgiftout)
            ->where('warehouse_id',$this->selectwarehouseout)
            ->where('position_in_warehouse_id',$this->selectpositionout)
            ->first();
            if (!isset($giftbalance)) {
                # code...
                $giftbalance = new GiftBalance();
            }
            $giftbalance->warehouse_id =$this->selectwarehouseout;
            $giftbalance->position_in_warehouse_id =$this->selectpositionout;
            $giftbalance->gift_id = $this->selectgiftout;
            $giftbalance->qty += -1 * $this->stockoutqty;
            $giftbalance->created_by = Auth::user()->id;
            $giftbalance->updated_by = Auth::user()->id;
            $giftbalance->created_at = Carbon::now();
            $giftbalance->updated_at = Carbon::now();
            $giftbalance->save();

            //update point of customer


            DB::commit();
            $this->dispatchBrowserEvent('closeModalStockout');
                $this->dispatchBrowserEvent('show-toast',
                ['type' => 'success', 'message' => 'Xuất kho quà tặng thành công']);
        }catch (Exception $e) {
            DB::rollback();
            //throw $th;
            Log::info($e);
            $this->dispatchBrowserEvent('show-toast',
            ['type' => 'error', 'message' => 'Xuất kho quà tặng thất bại']);
        }
        finally{
            $this->stockoutdate = Carbon::now();
            $this->selectgiftout = '';
            $this->selectwarehouseout = '';
            $this->selectpositionout = null;
            $this->stockoutqty = 0;
            $this->stockoutnote = '';
            unset($this->selectedOrders); // $foo is gone
            $this->selectedOrders = []; //
            unset($this->customerorders); // $foo is gone
            $this->customerorders = []; //
            $this->customerPhone = null;
        }

    }

    /**
     *
     */
    public function selectOrder($id){
        array_push($this->selectedOrders,$id);
    }

    /**
     * Handle do stock in modal
     */
    public function doStockIn(){
        //1. validate
              //1 validate
              $this->validate([
                'selectgift' => 'required',
                'selectwarehousein' => 'required',
               // 'selectposition' => 'required',
                'stockinqty' => 'required|numeric|min:1'

            ], [
                'selectgift.required' => 'vui lòng chọn quà tặng',
                'selectwarehousein.required' => 'vui lòng chọn kho',
                //'selectposition.required' => 'vui lòng chọn vị trí',
                'stockinqty.required' => 'vui lòng nhập số lượng',
                'rate.numeric' => 'số lượng phải là số',
                'rate.min' => 'số lượng nhập phải lớn hơn 0',
                'giftcode.max' => 'mã quà không quá 50 kí tự'
            ], []);
        //2 do stock in
        DB::beginTransaction();
        $message = '';
        try {
            //code...
            //2.1 ghi nhan transaction
            $gifttransaction = new GiftTransaction();
            $gifttransaction->trans_date = $this->stockindate;
            $gifttransaction->trans_type = 1;
            $gifttransaction->to_warehouse_id =$this->selectwarehousein;
            $gifttransaction->to_position_in_warehouse_id =$this->selectpositionin;
            $gifttransaction->gift_id = $this->selectgift;
            $gifttransaction->qty = $this->stockinqty;
            $gifttransaction->created_by = Auth::user()->id;
            $gifttransaction->updated_by = Auth::user()->id;
            $gifttransaction->created_at = Carbon::now();
            $gifttransaction->updated_at = Carbon::now();
            $gifttransaction->save();

            //2.2 ghi nhan gift balance
            $giftbalance = GiftBalance::whereNull('deleted_at')
            ->where('gift_id','=',$this->selectgift)
            ->where('warehouse_id',$this->selectwarehousein)
            ->where('position_in_warehouse_id',$this->selectpositionin)
            ->first();
            if (!isset($giftbalance)) {
                # code...
                $giftbalance = new GiftBalance();
            }
            $giftbalance->warehouse_id =$this->selectwarehousein;
            $giftbalance->position_in_warehouse_id =$this->selectpositionin;
            $giftbalance->gift_id = $this->selectgift;
            $giftbalance->qty += $this->stockinqty;
            $giftbalance->created_by = Auth::user()->id;
            $giftbalance->updated_by = Auth::user()->id;
            $giftbalance->created_at = Carbon::now();
            $giftbalance->updated_at = Carbon::now();
            $giftbalance->save();
            DB::commit();
        //try do
                $this->dispatchBrowserEvent('closeModalStockin');
                $this->dispatchBrowserEvent('show-toast',
                ['type' => 'success', 'message' => 'Nhập kho quà tặng thành công']);
            } catch (Exception $e) {
                DB::rollback();
                //throw $th;
                Log::info($e);
                $this->dispatchBrowserEvent('show-toast',
                ['type' => 'error', 'message' => 'Nhập kho quà tặng thất bại']);
        }
        finally{
            $this->stockindate = Carbon::now();
            $this->selectgift = '';
            $this->selectwarehousein = '';
            $this->selectpositionin = null;
            $this->stockinqty = 0;


        }
    }

    /**
     * return selectgiftlist
     */
    public function getGiftList(){
        $selectgiftlist = GiftMaster::whereNull('deleted_at')
        ->select('id','code','name')
        ->get();
        $this->selectgiftlist = $selectgiftlist;
    }

    /**
     * return warehouse list
     */
    public function getWarehouseList(){
        $warehouselist = GiftWarehouse::whereNull('deleted_at')
        ->select('id','name')
        ->get();
        $this->warehouselist = $warehouselist ;
    }
    /**
     * return warehouse list
     */
    public function getPositionList(){
        $positionlist = GiftPositionInWarehouse::whereNull('deleted_at')
        ->select('id','name')
        ->get();
        $this->positionlist = $positionlist ;
    }

    /**
     *
     */
    public function removeselectedcustomer($vcustomerphone){
        $this->customerorders =[];
        $this->customerPhone = null;
        $this->selectcustomerinfo = '';
    }

    /**
     *
     */
    public function getCustomerOrders(){
        if(isset($this->customerPhone)){
            unset($this->selectedOrders);
            $this->selectedOrders =[];

            $customer = Customer::where('phone','=',$this->customerPhone)->first();
            if ($customer) {
                # code...
                $this->selectcustomerinfo = $customer->name;

                $orders= DB::table('orders')
                ->whereNull('orders.deleted_at')
                ->join('order_details',function($join){
                    $join->on('orders.id','=','order_details.order_id');
                })
                ->where('orders.customer_id','=',$customer->id)
                ->whereNotIn('orders.id',function($q){
                    $q->select('order_id')
                    ->from('gift_transactions_orders');
                })
                ->select('orders.id',
                'order_details.code as partno',
                'order_details.name as partname',
                'order_details.chassic_no as frameno',
                'order_details.engine_no as engineno',
                'order_details.quantity','order_details.price'
                )->get();
                if (isset($orders)) {
                    # code...
                    $this->customerorders = $orders;
                }
            }
        }
    }

}
