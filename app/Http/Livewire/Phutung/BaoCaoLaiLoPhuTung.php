<?php

namespace App\Http\Livewire\Phutung;

use Livewire\Component;
use App\Exports\BaocaoBanHangPhutungExport;
use App\Http\Livewire\Base\BaseLive;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Accessory;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BaoCaoLaiLoPhuTung extends BaseLive
{
    public $keyword;
    public $showAdvancesearch =1;
    public $searchPartNo;

    public $searchName;
    public $searchType;
    public $searchAddr;
    public $searchStatus;
    public $searchSupplier;
    public $searchFromDate;
    public $searchToDate;
    public $perPage = 10;

    public $chkIsVirtual=true;
    public $chkIsReal=true;
    protected $listeners = ['setfromDate', 'settoDate'];

    public function mount(){
        $start = Carbon::now();
        $end = Carbon::now();
        $this->searchFromDate = $start;
        $this->searchToDate = $end;
    }

    public function render()
    {

        $query = Order::query();
        $query = $this->searchQuery($query);
        if ($this->key_name2) {
            //$query->orderBy($this->key_name2, $this->sortingName2);
        }

        $totalQty = 0 ;//= $query->sum('order_details.quantity');
        $totalAmount = 0 ;// $query->sum(DB::raw('order_details.quantity * order_details.actual_price'));
        $totalRevenue = 0;

        $this->dispatchBrowserEvent('setSelect2');
        $data = $query->paginate($this->perPage);

        return view('livewire.phutung.bao-cao-lai-lo-phu-tung',[
            'data' => $data,
            'totalQty' =>$totalQty,
            'totalAmount' =>$totalAmount,
            'totalRevenue'=>$totalRevenue
        ]);
    }

    public function searchQuery($query)
    {
        $query->where('orders.category', '=', '1')->whereIn('orders.type', [1, 2]);
        $query->join('order_details','orders.id','=','order_details.order_id');
        //$query->leftJoin('customers', 'customers.id', '=', 'orders.customer_id');
        $query->join('accessories', function($join){
            $join->on('accessories.id', '=', 'order_details.product_id');
            $join->on('accessories.code', '=', 'order_details.code');
        });



        if (isset($this->keyword) && !empty(($this->keyword))) {
            # code...
            $query->where('order_details.code', 'like', trim($this->keyword) . '%');
            $query->orWhere('order_details.name', 'like', trim($this->keyword) . '%');
            $query->orWhere('customers.name', 'like', trim($this->keyword) . '%');
            $query->orWhere('customers.address', 'like', trim($this->keyword) . '%');
        }


        if ($this->searchType)
            $query->where('orders.type', '=', $this->searchType);
        if ($this->searchStatus)
            $query->where('orders.status', '=', $this->searchStatus);
        if (isset($this->searchPartNo))
            $query->where('order_details.code', 'like',trim($this->searchPartNo) . '%');

        if (!empty($this->searchFromDate)) {
            $query->whereDate('orders.created_at', '>=', $this->searchFromDate);
        }
        if (!empty($this->searchToDate)) {
            $query->whereDate('orders.created_at', '<=', $this->searchToDate);
        }

        if($this->chkIsVirtual && !$this->chkIsReal){
            $query->where('orders.isvirtual','=',1);
        }elseif(!$this->chkIsVirtual && $this->chkIsReal){
            $query->where('orders.isvirtual','=',0);
        }
       // $query->orderBy('orders.id','ASC');
        $query->groupBy('order_details.code','order_details.name');
        $query->select(DB::raw('order_details.code as part_no'),
        DB::raw('order_details.name as part_name'),
        DB::raw('sum(order_details.quantity) as qty'),
        DB::raw('avg(order_details.actual_price) as actual_price'),
        DB::raw('sum(order_details.quantity*order_details.actual_price) as amount'),
        DB::raw('sum(order_details.quantity*accessories.price) as cost_mount'));
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

        $start = Carbon::now() ;//->startOfMonth();
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
            $orderdetails =  OrderDetail::where('order_id',$this->deleteId)
            ->where('deleted_at',null)
            ->get();
            foreach($orderdetails as $item) {
                $productId = $item->product_id;
                //update lai stock
                $accessory = Accessory::findOrFail($productId);
                if($accessory){
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
        }finally{

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
            return Excel::download(new BaocaoBanHangPhutungExport($data), 'baocaobanhangphutung_' . date('Y-m-d-His') . '.xlsx');
        }
    }

    public function simpleSearch(){

    }
}
