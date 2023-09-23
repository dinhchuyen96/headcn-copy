<?php

namespace App\Http\Livewire\Quanlykho;

use App\Enum\EOrder;
use App\Enum\EWarehouse;
use App\Http\Livewire\Base\BaseLive;
use App\Models\Warehouse;
use App\Models\PositionInWarehouse;
use App\Models\CategoryAccessory;
use App\Models\Accessory;
use App\Models\OrderDetail;
use App\Enum\ETranferType;
use App\Enum\EOrderDetail;
use App\Models\WarehouseTranferHistory;
use App\Models\AccessoryChangeLog;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AccessoriesExport;

class ReportAccessories extends BaseLive
{
    public $selectedpartno ;
    public $selectedwarehouse ;
    public $selectedposition;
    public $selectedbegin;
    public $detailpartinfo =[];

    public $totalBegin = 0;
    public $totalIn = 0;
    public $totalOut = 0;
    public $totalTransIn = 0;
    public $totalTransOut = 0;
    public $totalStock = 0;
    public $totalPriceIn=0;
    public $totalPriceOut=0;


    public $PositionInWarehouse;
    public $Warehouses;
    public $AccessoriesCode;
    public $FromDate;
    public $ToDate;
    public $key_name = 'code';
    public $sortingName = 'desc';
    protected $listeners = ['setfromDate', 'settoDate'];

    public function setfromDate($time)
    {
        $this->FromDate = date('Y-m-d', strtotime($time['fromDate']));
    }

    public function settoDate($time)
    {
        $this->ToDate = date('Y-m-d', strtotime($time['toDate']));
    }

    public function mount()
    {
        $this->FromDate = Carbon::now()->startOfMonth();
        $this->ToDate = Carbon::today();
    }

    public function updatedWarehouses($value)
    {
        $this->PositionInWarehouse = '';
        $this->resetPage();
    }

    public function updatingAccessoriesCode()
    {
        $this->resetPage();
    }

    public function updatingPositionInWarehouse()
    {
        $this->resetPage();
    }

    public function render()
    {
        $fromDate = Carbon::parse($this->FromDate)->format('Y-m-d 00:00:00');
        $toDate = Carbon::parse($this->ToDate)->format('Y-m-d 23:59:59');
        if (isset($this->selectedpartno)) {
            # code...
            $this->detailpartinfo = $this->getpartnoinfo($this->selectedpartno ,
            $this->selectedwarehouse ,
            $this->selectedposition);

        }

        $positionWarehouse = [];
        $warehouseList = Warehouse::orderBy('name')->pluck('name', 'id');
        if ($this->Warehouses) {
            $positionWarehouse = PositionInWarehouse::where('warehouse_id', $this->Warehouses)->orderBy('name')->pluck('name', 'id');
        }
        $this->dispatchBrowserEvent('setSelect2');

        if ($this->reset) {
            $this->reset = null;
            $this->Warehouses = null;
            $this->PositionInWarehouse = null;
            $this->AccessoriesCode = null;
            $this->FromDate = Carbon::now()->startOfMonth();
            $this->ToDate =  Carbon::today();
            $this->key_name = 'accessories.id';
            $this->sortingName = 'desc';
        }
        $this->Warehouses = trim($this->Warehouses);
        $this->PositionInWarehouse = trim($this->PositionInWarehouse);
        $this->AccessoriesCode = trim($this->AccessoriesCode);
        $this->FromDate = trim($this->FromDate);
        $this->ToDate = trim($this->ToDate);
        $this->key_name = trim($this->key_name);
        $this->sortingName = trim($this->sortingName);
        $accessories = $this->getAccessories($fromDate, $toDate)->paginate($this->perPage);
        $accessoryRests = $this->getAccessories('1970-01-01 00:00:00', $fromDate)->paginate($this->perPage);
        $rests = [];
        foreach ($accessoryRests as $value) {
            $rests[$value->id] = $value->quantity_buy_input +
                $value->quantity_input_trans -
                $value->quantity_sell_output -
                $value->quantity_output_trans;
        }
        $this->totalBegin = 0;
        $this->totalIn = 0;
        $this->totalTransIn = 0;
        $this->totalOut = 0;
        $this->totalTransOut  = 0;
        $this->totalStock = 0;
//        if (!empty($accessories)) {
//            foreach ($accessories as $value) {
//                $this->totalBegin +=  $value->quantity_first_log ?? 0;
//                $this->totalIn += $value->quantity_buy_input ?? 0;
//                $this->totalPriceIn+=$value->priceIn ??0;
//                $this->totalPriceOut+=$value->priceOut ??0;
//                $this->totalTransIn += $value->quantity_input_trans ?? 0;
//                $this->totalOut += $value->quantity_sell_output ?? 0;
//                $this->totalTransOut  += $value->quantity_output_trans ?? 0;
//                $this->totalStock += $value->quantity_first_log +  $value->quantity_buy_input
//                    + $value->quantity_input_trans
//                    - $value->quantity_sell_output - $value->quantity_output_trans;
//            }
//        }

//        $accessories = paginate($this->getData(), $this->perPage);
        return view('livewire.quanlykho.baocaokhophutung', [
            'accessories' => $accessories,
            'rests' => $rests,
            'warehouseList' => $warehouseList,
            'positionWarehouse' => $positionWarehouse,
        ]);
    }

     /**
     * Get detail of part no
     * 1. begin
     * 2. buyin
     * 2. transfer in
     * 3. transfer out
     * 4. sale
     * 5. remain
     * output list of table List<partno, begin, in,transfer in, out, transfer out, balance
     */
    public function getpartnoinfo($partno,$warehouseid, $positionid){
        $fromDate = $this->FromDate;
        $toDate= $this->ToDate;


        //1. Get receive data
        $queryData = DB::table('accessories');
        $queryData->join('order_details', function ($join) {
            $join->on('accessories.code', '=', 'order_details.code');
            $join->on('accessories.warehouse_id', '=', 'order_details.warehouse_id');
            $join->on('accessories.position_in_warehouse_id', '=', 'order_details.position_in_warehouse_id');
        });
        $queryData->whereNull('accessories.deleted_at');
        $queryData->where('accessories.code',$partno);
        $queryData->where('accessories.warehouse_id',$warehouseid);
        $queryData->where('accessories.position_in_warehouse_id',$positionid);
        $queryData->where('order_details.category', '=', EOrderDetail::CATE_ACCESSORY);
        $queryData->where('order_details.type', '=', EOrderDetail::TYPE_NHAP);
        $queryData->whereNull('order_details.deleted_at');
        $queryData->where('order_details.buy_date', '>=', $fromDate);
        $queryData->where('order_details.buy_date', '<=', $toDate);
        $queryData->select('accessories.id','accessories.code','accessories.name'
        ,'order_details.buy_date as date',
        'order_details.quantity as buy_in',
        DB::raw('0 as sell_out'),
        DB::raw('0 as transfer_in'),
        DB::raw('0 as transfer_out')
        ,DB::raw('0 as repair_qty'));


        //1. Get sell out
        $queryData2 = DB::table('accessories');
        $queryData2->join('order_details', function ($join) {
            $join->on('accessories.code', '=', 'order_details.code');
            $join->on('accessories.warehouse_id', '=', 'order_details.warehouse_id');
            $join->on('accessories.position_in_warehouse_id', '=', 'order_details.position_in_warehouse_id');
        });
        $queryData2->whereNull('accessories.deleted_at');
        $queryData2->where('accessories.code',$partno);
        $queryData2->where('accessories.warehouse_id',$warehouseid);
        $queryData2->where('accessories.position_in_warehouse_id',$positionid);
        $queryData2->where('order_details.category', '=', EOrderDetail::CATE_ACCESSORY);
        $queryData2->where('order_details.type', '=', EOrderDetail::TYPE_BANBUON);
        $queryData2->orWhere('order_details.type', '=', EOrderDetail::TYPE_BANLE);
        $queryData2->whereNull('order_details.deleted_at');
        $queryData2->where('order_details.buy_date', '>=', $fromDate);
        $queryData2->where('order_details.buy_date', '<=', $toDate);
        $queryData2->select('accessories.id','accessories.code','accessories.name'
        ,'order_details.created_at  as date',
        DB::raw('0 as buy_in'),
        DB::raw('order_details.quantity as sell_out'),
        DB::raw('0 as transfer_in'),
        DB::raw('0 as transfer_out')
        ,DB::raw('0 as repair_qty'));


        $queryData5 = DB::table('accessories');
        $queryData5->whereNull('accessories.deleted_at');
        $queryData5->where('accessories.code',$partno);
        $queryData5->where('accessories.warehouse_id',$warehouseid);
        $queryData5->where('accessories.position_in_warehouse_id',$positionid);

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
            $join->where('order_details.created_at', '>=', $fromDate);
            $join->where('order_details.created_at', '<=', $toDate);
        });
        $queryData5->select('accessories.id','accessories.code','accessories.name'
        ,'order_details.created_at  as date',
        DB::raw('0 as buy_in'),
        DB::raw('0 as sell_out'),
        DB::raw('0 as transfer_in'),
        DB::raw('0 as transfer_out')
        ,DB::raw('order_details.quantity as repair_qty'));



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
        $queryData3->where('accessories.position_in_warehouse_id',$positionid);
        $queryData3->where('warehouse_tranfer_history.tranfer_date', '>=', $fromDate);
        $queryData3->where('warehouse_tranfer_history.tranfer_date', '<=', $toDate);
        $queryData3->select('accessories.id','accessories.code','accessories.name'
        ,'warehouse_tranfer_history.tranfer_date  as date',
        DB::raw('0 as buy_in'),
        DB::raw('0 as sell_out'),
        DB::raw('warehouse_tranfer_history.quantity as transfer_in'),
        DB::raw('0 as transfer_out')
        ,DB::raw('0 as repair_qty'));



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
        $queryData4->where('accessories.position_in_warehouse_id',$positionid);
        $queryData4->where('warehouse_tranfer_history.tranfer_date', '>=', $fromDate);
        $queryData4->where('warehouse_tranfer_history.tranfer_date', '<=', $toDate);
        $queryData4->select('accessories.id','accessories.code','accessories.name'
        ,'warehouse_tranfer_history.tranfer_date  as date',
        DB::raw('0 as buy_in'),
        DB::raw('0 as sell_out'),
        DB::raw('0 as transfer_in'),
        DB::raw('warehouse_tranfer_history.quantity as transfer_out')
        ,DB::raw('0 as repair_qty'));

        $allUnions  = $queryData->union($queryData2)
        ->union($queryData3)->union($queryData4)->union($queryData5);
        $resultdata = $allUnions->get();

        return $resultdata;

    }

    public function getAccessories ($from, $to) {
        $accessories = Accessory::with(['accessoryChangeLogs' => function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [$from, $to]);
        }, 'orderDetails' => function ($q) use ($from, $to) {
            $q->whereHas('order')->whereHas('order', function ($q) {
                $q->where('isvirtual', EOrder::REAL);
            })->whereBetween('created_at', [$from, $to]);
        }, 'warehouse', 'positionInWarehouse', 'relatedAccessories.warehouseTransferHistories' => function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [$from, $to]);
        }])
            ->whereHas('warehouse')
            ->whereHas('positionInWarehouse');
        if ($this->AccessoriesCode) {
            $accessories->where('code', 'like', "%$this->AccessoriesCode%");
        }

        if ($this->Warehouses) {
            $accessories->where('warehouse_id', $this->Warehouses);
        }

        if ($this->PositionInWarehouse) {
            $accessories->where('position_in_warehouse_id', $this->PositionInWarehouse);
        }
        if ($this->key_name) {
            $accessories->orderBy($this->key_name, $this->sortingName);
        }
        return $accessories;
    }

    public function getData ()
    {
        return [];
    }

    public function getPriceIn()
    {
        $queryPriceIn = DB::table('order_details');
        $queryPriceIn->where('order_details.category', '=', EOrderDetail::CATE_ACCESSORY);
        $queryPriceIn->where('order_details.type', '=', EOrderDetail::TYPE_NHAP);
        $queryPriceIn->whereNull('order_details.deleted_at');
        $queryPriceIn->select(
            'code',
            DB::raw('AVG(order_details.actual_price) as priceIn')
        );
        $queryPriceIn->groupBy('order_details.code');

        return $queryPriceIn;
    }

    //gia ban niem yet cua nha may
    public function getPriceOut()
    {
        $queryPriceOut = DB::table('category_accessories');
        $queryPriceOut->whereNull('deleted_at');
        $queryPriceOut->select('code', DB::raw('netprice as priceOut'));
        return $queryPriceOut;
    }

    public function export()
    {
        $fromDate = Carbon::parse($this->FromDate)->format('Y-m-d 00:00:00');
        $toDate = Carbon::parse($this->ToDate)->format('Y-m-d 23:59:59');
        $accessories = $this->getAccessories($fromDate, $toDate)->get();
        $accessoryRests = $this->getAccessories('1970-01-01 00:00:00', $fromDate)->get();
        $rests = [];
        foreach ($accessoryRests as $value) {
            $rests[$value->id] = $value->quantity_buy_input +
                $value->quantity_input_trans -
                $value->quantity_sell_output -
                $value->quantity_output_trans;
        }

        return Excel::download(new AccessoriesExport(
            $accessories, $rests
        ), 'baocaokhophutung_' . date('Y-m-d-His') . '.xlsx');
    }
}
