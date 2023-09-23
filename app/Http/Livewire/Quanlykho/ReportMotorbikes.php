<?php

namespace App\Http\Livewire\Quanlykho;

use App\Http\Livewire\Base\BaseLive;
use App\Models\Warehouse;
use App\Models\Motorbike;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NavigationMotorbikeExport;
use Log;
use App\Enum\EMotorbike;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class ReportMotorbikes extends BaseLive
{
    public $totalbegin_qty = 0;
    public $totalbuy_qty = 0;
    public $totalsale_qty = 0;
    public $totaltransferin_qty = 0;
    public $totaltransferout_qty = 0;
    public $totalremain_qty = 0;

    public $Warehouses;
    public $Model;
    public $Color;
    public $ChassicNumber;
    public $EngineNumber;
    public $FromDate;
    public $ToDate;
    public $key_name;
    public $total_money;
    public $sortingName;

    //Bikeinfo for change bike
    public $bikelist = [];
    public $firstbikeframeno;
    public $firstmodelname;
    public $firstbikeprice;
    public $secondbikeframeno;
    public $secondmodelname;
    public $secondbikeprice;
    public $bikenote;




    protected $listeners = ['setfromDate', 'settoDate'];

    public function setfromDate($time)
    {
        $this->FromDate = date('Y-m-d', strtotime($time['fromDate']));
    }

    public function settoDate($time)
    {
        $this->ToDate = date('Y-m-d', strtotime($time['toDate']));
    }

    public function mount(){
        $this->FromDate = Carbon::now()->startOfMonth();
        $this->ToDate = Carbon::now();

    }

    public function render()
    {
        $this->GetBikeList();
        $warehouseList = Warehouse::orderBy('name')->pluck('name', 'id');
        $this->dispatchBrowserEvent('setSelect2');
        if ($this->reset) {
            $this->reset = null;
            $this->Warehouses = null;
            $this->Model = null;
            $this->Color = null;
            $this->ChassicNumber = null;
            $this->EngineNumber = null;
            $this->FromDate = Carbon::now()->startOfMonth();

            $this->ToDate = Carbon::now();
            $this->key_name = 'motorbikes.id';
            $this->sortingName = 'desc';
        }

        $this->Warehouses = trim($this->Warehouses);
        $this->Model = trim($this->Model);
        $this->Color = trim($this->Color);
        $this->ChassicNumber = trim($this->ChassicNumber);
        $this->EngineNumber = trim($this->EngineNumber);
        $this->FromDate = trim($this->FromDate);
        $this->ToDate = trim($this->ToDate);
        $this->key_name = trim($this->key_name);
        $this->sortingName = trim($this->sortingName);

        $query = $this->getMotobikes();

        if ($this->key_name) {
            $query->orderBy($this->key_name, $this->sortingName);
        } else {
            $query->orderBy('motorbikes.quantity', 'asc');
        }
        $data = $query->paginate($this->perPage);

        //get total
         $this->totalbegin_qty=$data->sum('begin_qty');
         $this->totalbuy_qty =$data->sum('buyin_qty');
         $this->totaltransferin_qty =$data->sum('transferin_qty');
         $this->totaltransferout_qty =$data->sum('transferout_qty');
         $this->totalsale_qty =$data->sum('sale_qty');
         $this->totalstock_qty =$data->sum('stock_qty');

        return view('livewire.quanlykho.baocaokhoxemay', [
            'warehouseList' => $warehouseList,
            'data' => $data,
        ]);
    }


    /**
     * TODO TUDN remake report of motobike
     */
    public function getMotobikes(){

       //Get begin qty
        $beginquery = DB::table('motorbikes')
        ->where('buy_date','<' ,$this->FromDate)
        ->whereRaw("(sell_date >='".$this->FromDate."' or sell_date is null)")
        ->select('id','chassic_no','warehouse_id','quantity');

        //buy qty
        $buyinquery = DB::table('motorbikes')
        ->where('buy_date','>=' ,$this->FromDate)
        ->where('buy_date','<=' ,$this->ToDate)
        ->select('id','chassic_no','warehouse_id','quantity');

        //transfer in qty
        $transferinquery = DB::table('motorbikes')
        ->join('warehouse_tranfer_history',function($join){
            $join->on('motorbikes.id','=','warehouse_tranfer_history.to_warehouse_id');
            $join->on('motorbikes.id','=','warehouse_tranfer_history.product_id');
            $join->where('warehouse_tranfer_history.to_warehouse_id','=',0);
            $join->where('warehouse_tranfer_history.tranfer_date','>=', $this->FromDate);
            $join->where('warehouse_tranfer_history.tranfer_date','<=', $this->ToDate);
        })
        ->select('motorbikes.id','motorbikes.chassic_no','motorbikes.warehouse_id',
        'warehouse_tranfer_history.quantity');

        //get transfer out qty
        $transferoutquery = DB::table('motorbikes')
        ->join('warehouse_tranfer_history',function($join){
            $join->on('motorbikes.id','=','warehouse_tranfer_history.to_warehouse_id');
            $join->on('motorbikes.id','=','warehouse_tranfer_history.product_id');
            $join->where('warehouse_tranfer_history.from_warehouse_id','=',0);
            $join->where('warehouse_tranfer_history.tranfer_date','>=',$this->FromDate);
            $join->where('warehouse_tranfer_history.tranfer_date','<=',$this->ToDate);
        })
        ->select('motorbikes.id','motorbikes.chassic_no','motorbikes.warehouse_id',
        'warehouse_tranfer_history.quantity');

        //get sale qty
        $salequery = DB::table('motorbikes')
        ->where('sell_date','>=' ,$this->FromDate)
        ->where('sell_date','<=' ,$this->ToDate)
        ->select('id','chassic_no','warehouse_id','quantity');

        $query = DB::table('motorbikes')
        ->join('warehouse',function($join){
            $join->on('warehouse.id','=','motorbikes.warehouse_id');
        })
        ->leftJoinSub($beginquery, 'beginquery', function ($join) {
            $join->on('motorbikes.id','=','beginquery.id');
            $join->on('motorbikes.warehouse_id','=','beginquery.warehouse_id');
        })
        ->leftJoinSub($buyinquery, 'buyinquery', function ($join) {
            $join->on('motorbikes.id','=','buyinquery.id');
            $join->on('motorbikes.warehouse_id','=','buyinquery.warehouse_id');
        })
        ->leftJoinSub($transferinquery, 'transferinquery', function ($join) {
            $join->on('motorbikes.id','=','transferinquery.id');
            $join->on('motorbikes.warehouse_id','=','transferinquery.warehouse_id');
        })
        ->leftJoinSub($transferoutquery, 'transferoutquery', function ($join) {
            $join->on('motorbikes.id','=','transferoutquery.id');
            $join->on('motorbikes.warehouse_id','=','transferoutquery.warehouse_id');
        })
        ->leftJoinSub($salequery, 'salequery', function ($join) {
            $join->on('motorbikes.id','=','salequery.id');
            $join->on('motorbikes.warehouse_id','=','salequery.warehouse_id');
        });

        if (!empty($this->Warehouses)) {
            $query->where('motorbikes.warehouse_id', '=', $this->Warehouses);
        }
        if (!empty($this->Model)) {
            $query->where('motorbikes.model_code', 'like', '%' . $this->Model . '%');
        }
        if (!empty($this->Color)) {
            $query->where('motorbikes.color', 'like', '%' . $this->Color . '%');
        }
        if (!empty($this->ChassicNumber)) {
            $query->where('motorbikes.chassic_no', 'like', '%' . $this->ChassicNumber . '%');
        }
        if (!empty($this->EngineNumber)) {
            $query->where('motorbikes.engine_no', 'like', '%' . $this->EngineNumber . '%');
        }
        $query->select(
            'motorbikes.id',
            'motorbikes.model_code',
            'motorbikes.color',
            'motorbikes.warehouse_id',
            'motorbikes.chassic_no',
            'motorbikes.engine_no',
            'warehouse.name as warehouse_name',
            DB::raw('motorbikes.quantity'),
                DB::raw('COALESCE (beginquery.quantity, 0 ) AS begin_qty'),
                DB::raw('COALESCE (buyinquery.quantity, 0 ) AS buyin_qty'),
                DB::raw('COALESCE (transferinquery.quantity, 0 ) AS transferin_qty'),
                DB::raw('COALESCE (transferoutquery.quantity, 0 ) AS transferout_qty'),
                DB::raw('COALESCE (salequery.quantity, 0) AS sale_qty'),

                DB::raw('COALESCE (beginquery.quantity, 0 )+
                COALESCE (buyinquery.quantity, 0 )+
                COALESCE (transferinquery.quantity, 0 )-
                COALESCE (transferoutquery.quantity, 0 )-
                COALESCE (salequery.quantity, 0) as stock_qty')
        );
        return $query;
    }

    public function export()
    {
        return Excel::download(new NavigationMotorbikeExport(
            $this->Warehouses,
            $this->Model,
            $this->Color,
            $this->ChassicNumber,
            $this->EngineNumber,
            $this->FromDate,
            $this->ToDate,
            $this->key_name,
            $this->sortingName
        ), 'baocaokhoxemay_' . date('Y-m-d-His') . '.xlsx');
    }


    /**
     * Get bike list
     */
    public function GetBikeList()
    {
        $this->bikelist = Motorbike::where('is_out', EMotorbike::NOT_OUT)
            ->whereNull('sell_date')
            ->select('chassic_no', 'engine_no', 'model_type', 'color')->get();
    }
    /**Onchange first bike */
    public function Updatedfirstbikeframeno()
    {
        if (isset($this->firstbikeframeno)) {
            $bikeinfo =  Motorbike::where('chassic_no', $this->firstbikeframeno)
                ->select('chassic_no', 'engine_no', 'model_type', 'color', 'price')->first();
            if (isset($bikeinfo)) {
                $this->firstmodelname = $bikeinfo->model_type . ' - ' . $bikeinfo->color;;
                $this->firstbikeprice = $bikeinfo->price;
            }
        }
    }

    /**Onchange first bike */
    public function Updatedsecondbikeframeno()
    {
        if (isset($this->secondbikeframeno)) {
            $bikeinfo =  Motorbike::where('chassic_no', $this->secondbikeframeno)
                ->select('chassic_no', 'engine_no', 'model_type', 'color', 'price')->first();
            if (isset($bikeinfo)) {
                $this->secondmodelname = $bikeinfo->model_type . ' - ' . $bikeinfo->color;
                $this->secondbikeprice = $bikeinfo->price;
            }
        }
    }

    /**
     * @change 2 bike
     * update new price
     */
    public function ChangeBikeInfo()
    {
        try {

            $this->validate([
                'firstbikeframeno' => 'required',
                'firstbikeprice' => 'required|integer|gt:0|digits_between:1,11',
                'secondbikeframeno' => 'required',
                'secondbikeprice' => 'required|integer|gt:0|digits_between:1,11',
                'bikenote' => 'required'
            ], [
                'firstbikeframeno.required' => 'Bạn chưa chọn xe đổi',
                'firstbikeprice.required' => 'Bạn chưa nhập giá xe',
                'firstbikeprice.integer' => 'Giá xe phải kiểu số',
                'firstbikeprice.gt' => 'Giá xe phải lớn hơn 0',
                'firstbikeprice.digits_between' => 'Giá xe phải nhỏ hơn 999999999',

                'secondbikeframeno.required' => 'Bạn chưa chọn xe cần đổi',
                'secondbikeprice.required' => 'Bạn chưa nhập giá xe',
                'secondbikeprice.integer' => 'Giá xe phải kiểu số',
                'secondbikeprice.gt' => 'Giá xe phải lớn hơn 0',
                'secondbikeprice.digits_between' => 'Giá xe phải nhỏ hơn 999999999',
                'bikenote.required' => 'Nội dung thay đổi bắt buộc nhập'
            ], []);

            if ($this->firstbikeframeno == $this->secondbikeframeno) {
                $message = 'Hai số khung không được trùng nhau';
                $this->dispatchBrowserEvent('show-toast', [
                    'type' => 'error',
                    'message' => $message
                ]);
            } else {
                //update price of bike and comment note
                $firstbikeinfo =  Motorbike::where('chassic_no', $this->firstbikeframeno)->first();
                if (isset($firstbikeinfo)) {
                    $firstbikeinfo->price = $this->firstbikeprice;
                    $firstbikeinfo->note = $this->bikenote;
                    $firstbikeinfo->updated_at = Carbon::now();
                    $firstbikeinfo->save();
                }
                $secondbikeinfo =  Motorbike::where('chassic_no', $this->secondbikeframeno)->first();
                if (isset($secondbikeinfo)) {
                    $secondbikeinfo->price = $this->firstbikeprice;
                    $secondbikeinfo->note = $this->bikenote;
                    $secondbikeinfo->updated_at = Carbon::now();
                    $secondbikeinfo->save();
                }
                $this->resetInputChangeBike();
                $message = 'Cập nhật thay đổi thành công';
                $this->dispatchBrowserEvent('show-toast', [
                    'type' => 'success',
                    'message' => $message
                ]);
            }
        } catch (\Exception $e) {
            $message = 'Không thể cập nhật thay đổi! vui lòng liên hệ quản trị hệ thống';
            $this->dispatchBrowserEvent('show-toast', [
                'type' => 'error',
                'message' => $message
            ]);
        }
    }

    /***
     *
     */
    public function resetInputChangeBike()
    {
        $this->firstbikeframeno = '';
        $this->firstmodelname = '';
        $this->firstbikeprice = '';

        $this->secondbikeframeno = '';
        $this->secondmodelname = '';
        $this->secondbikeprice = '';
        $this->bikenote = '';
    }
}
