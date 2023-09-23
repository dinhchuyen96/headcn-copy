<?php

namespace App\Http\Livewire\Quanlykho;
use App\Http\Livewire\Base\BaseLive;
use App\Models\Warehouse;
use App\Models\PositionInWarehouse;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AccessoriesExport;
use Livewire\Component;
use Carbon\Carbon;
use App\Models\Accessory;
use App\Models\CategoryAccessory;
use App\Models\OrderDetail;
use App\Enum\ETranferType;
use App\Enum\EOrderDetail;


class Baocaosudungphutung extends BaseLive
{
    public $accessories=[];
    public $detailpartinfo ;
    public $warehouse;
    public $warehouselist=[];
    public $usingtype =1;
    public $searchFromDate;
    public $searchToDate;
    public $partno;
    public $partnolist=[];
    public $perPage =10;
    public $data;
    public $totalqty = 0;
    public $totalamount = 0;

    protected $listeners = ['setfromDate', 'settoDate'];
    public function mount(){
        $this->warehouselist = Warehouse::orderBy('name')
        ->pluck('name', 'id');
        $this->partnolist =Accessory::orderBy('name')
        ->pluck('name', 'code');

        $this->searchFromDate = Carbon::now();
        $this->searchToDate = Carbon::now();
    }
    public function render()
    {
        $partno =$this->partno;
        $warehouse =$this->warehouse;
        $usingtype = $this->usingtype;
        if(isset($partno) && !empty($partno)
            && isset($warehouse) && !empty($warehouse)
        ){
            //nhap kho
            if ($usingtype==1) {
                # code...
                $query = $this->queryDataIn();
            }else{
                $query = $this->queryDataOut();
            }

            $this->data = $query;
            $this->totalqty   = $query->sum('qty');
            $this->totalamount  = $query->sum('amount');
        }
        $this->dispatchBrowserEvent('setSelect2');
        return view('livewire.quanlykho.baocaosudungphutung');
    }

    /**
     *
     */
    public function queryDataIn(){
        try {
            //code...
            $partno =$this->partno;
            $warehouseid =$this->warehouse;
            $fromDate =$this->searchFromDate;
            $toDate =$this->searchToDate;

            //nhap kho thong thuong
            $queryData = DB::table('accessories');
            $queryData->join('order_details', function ($join) {
                $join->on('accessories.code', '=', 'order_details.code');
                $join->on('accessories.warehouse_id', '=', 'order_details.warehouse_id');
                $join->on('accessories.position_in_warehouse_id', '=', 'order_details.position_in_warehouse_id');
            });
            $queryData->whereNull('accessories.deleted_at');
            $queryData->where('accessories.code',$partno);
            $queryData->where('accessories.warehouse_id',$warehouseid);
            $queryData->where('order_details.category', '=', EOrderDetail::CATE_ACCESSORY);
            $queryData->where('order_details.type', '=', EOrderDetail::TYPE_NHAP);
            $queryData->whereNull('order_details.deleted_at');
            $queryData->whereDate('order_details.buy_date', '>=', $fromDate);
            $queryData->whereDate('order_details.buy_date', '<=', $toDate);
            $queryData->select('accessories.id','accessories.code','accessories.name'
            ,'order_details.buy_date as date',
            'order_details.quantity as qty',
            'order_details.actual_price as price',
            DB::raw("order_details.quantity * order_details.actual_price as amount "),
            DB::raw("'Nhập mua phụ tùng' as reason")
            );

            //nhap chuyen kho

            //1. Get transfer in
            $queryData3 = DB::table('accessories');
            $queryData3->join('warehouse_tranfer_history', function ($join) {
                $join->on('accessories.id', '=', 'warehouse_tranfer_history.product_id');
                $join->on('accessories.warehouse_id', '=', 'warehouse_tranfer_history.to_warehouse_id');
                $join->on('accessories.position_in_warehouse_id', '=', 'warehouse_tranfer_history.to_position_in_warehouse_id');
            });
            $queryData3->whereNull('accessories.deleted_at');
            $queryData3->where('accessories.code',$partno);
            $queryData3->where('accessories.warehouse_id',$warehouseid);
            $queryData3->where('warehouse_tranfer_history.tranfer_date', '>=', $fromDate);
            $queryData3->where('warehouse_tranfer_history.tranfer_date', '<=', $toDate);
            $queryData3->select('accessories.id','accessories.code','accessories.name'
            ,'warehouse_tranfer_history.tranfer_date  as date',
            DB::raw('warehouse_tranfer_history.quantity as qty'),
            DB::raw('accessories.price as price'),
            DB::raw("warehouse_tranfer_history.quantity * accessories.price as amount "),
            DB::raw("'Nhập chuyển kho phụ tùng' as reason")
            );

            $allUnions  = $queryData
            ->union($queryData3);
            $resultdata = $allUnions->get();

            return $resultdata;
        } catch (Exception $e) {
            //throw $th;
            Log::info($e);
            return null;
        }

    }

    /**
     * Get data xuat kho
     */
    public function queryDataOut(){
        $partno =$this->partno;
        $warehouseid =$this->warehouse;
        $usingtype =$this->usingtype;
        $fromDate =$this->searchFromDate;
        $toDate =$this->searchToDate;

        //1. Get sell out
        $queryData2 = DB::table('accessories');
        $queryData2->join('order_details', function ($join) {
            $join->on('accessories.code', '=', 'order_details.code');
            $join->on('accessories.warehouse_id', '=', 'order_details.warehouse_id');
            $join->on('accessories.position_in_warehouse_id', '=', 'order_details.position_in_warehouse_id');
        });
        $queryData2->join('orders',function($join){
            $join->on('orders.id', '=', 'order_details.order_id');
            $join->whereNull('orders.deleted_at');
        });
        $queryData2->whereNull('accessories.deleted_at');
        $queryData2->where('accessories.code',$partno);
        $queryData2->where('accessories.warehouse_id',$warehouseid);
        $queryData2->where('order_details.category', '=', EOrderDetail::CATE_ACCESSORY);
        $queryData2->where('order_details.type', '=', EOrderDetail::TYPE_BANBUON);
        $queryData2->orWhere('order_details.type', '=', EOrderDetail::TYPE_BANLE);
        $queryData2->whereNull('order_details.deleted_at');
        $queryData2->whereDate('order_details.buy_date', '>=', $fromDate);
        $queryData2->whereDate('order_details.buy_date', '<=', $toDate);
        $queryData2->select('accessories.id','accessories.code','accessories.name'
        ,'order_details.created_at  as date',
        DB::raw('order_details.quantity as qty'),
        DB::raw('order_details.actual_price as price'),
        DB::raw('order_details.quantity * order_details.actual_price as amount'),
        DB::raw('orders.seller as user'),
        DB::raw("'Bán phụ tùng' as reason")
    );

        //phu tung sua chua
        $queryData5 = DB::table('accessories');
        $queryData5->whereNull('accessories.deleted_at');
        $queryData5->where('accessories.code',$partno);
        $queryData5->where('accessories.warehouse_id',$warehouseid);
        $queryData5->join('order_details', function ($join) use ($fromDate, $toDate) {
            $join->on('order_details.product_id', '=', 'accessories.id');
            //tudn
            $join->on('order_details.warehouse_id', '=', 'accessories.warehouse_id');
            $join->on('order_details.position_in_warehouse_id', '=', 'accessories.position_in_warehouse_id');
            //end tudn
            $join->where('order_details.status', EOrderDetail::STATUS_SAVED);
            $join->where(function ($q) {
                $q->orWhere('order_details.category', EOrderDetail::CATE_ACCESSORY);
                $q->orWhere('order_details.category', EOrderDetail::CATE_MAINTAIN);
                $q->orWhere('order_details.category', EOrderDetail::CATE_REPAIR);
            });
            $join->where('order_details.is_atrophy', EOrderDetail::NOT_ATROPHY_ACCESSORY);
            $join->whereDate('order_details.created_at', '>=', $fromDate);
            $join->whereDate('order_details.created_at', '<=', $toDate);
        });
        $queryData5->join('orders',function($join){
            $join->on('orders.id', '=', 'order_details.order_id');
            $join->whereNull('orders.deleted_at');
        });
        $queryData5->select('accessories.id','accessories.code','accessories.name'
        ,'order_details.created_at  as date'
        ,DB::raw('order_details.quantity as qty')
        ,DB::raw('order_details.actual_price as price')
        ,DB::raw('order_details.quantity * order_details.actual_price as amount')
        ,DB::raw('orders.fixer as user'),
        DB::raw("'xuất sửa chữa' as reason")
    );

    //1. Get transfer out
    $queryData4 = DB::table('accessories');
    $queryData4->join('warehouse_tranfer_history', function ($join) {
        $join->on('accessories.id', '=', 'warehouse_tranfer_history.product_id');
        $join->on('accessories.warehouse_id', '=', 'warehouse_tranfer_history.from_warehouse_id');
        $join->on('accessories.position_in_warehouse_id', '=', 'warehouse_tranfer_history.from_position_in_warehouse_id');
    });
    $queryData4->whereNull('accessories.deleted_at');
    $queryData4->where('accessories.code',$partno);
    $queryData4->where('accessories.warehouse_id',$warehouseid);
    $queryData4->whereDate('warehouse_tranfer_history.tranfer_date', '>=', $fromDate);
    $queryData4->whereDate('warehouse_tranfer_history.tranfer_date', '<=', $toDate);
    $queryData4->select('accessories.id','accessories.code','accessories.name'
    ,'warehouse_tranfer_history.tranfer_date  as date',
    DB::raw('warehouse_tranfer_history.quantity as qty'),
    DB::raw('accessories.price as price'),
    DB::raw('warehouse_tranfer_history.quantity * accessories.price as amount'),
    DB::raw("'' as user"),
    DB::raw("'xuất chuyển kho phụ tùng' as reason")
);

    $allUnions  = $queryData2
    ->union($queryData4)->union($queryData5);
    $resultdata = $allUnions->get();

        return $resultdata;
    }

    public function setfromDate($time)
    {
        $this->searchFromDate = date('Y-m-d', strtotime($time['fromDate']));
    }
    public function settoDate($time)
    {
        $this->searchToDate = date('Y-m-d', strtotime($time['toDate']));
    }

}
