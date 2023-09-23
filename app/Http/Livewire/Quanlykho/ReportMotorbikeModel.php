<?php

namespace App\Http\Livewire\Quanlykho;

use App\Http\Livewire\Base\BaseLive;
use App\Models\Motorbike;
use App\Models\MasterData;
use App\Models\Warehouse;

use App\Enum\EMotorbike;
use App\Exports\ReportMotorbikeModelExport;
use App\Models\Mtoc;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;


class ReportMotorbikeModel extends BaseLive
{
    public $totalprice = 0;
    public $totalnumber = 0;
    public $perPage=10;
    public $warehouse;

    public $Color; // variable for combobox color
    public $Model; // variable for combobox model
    public $FromDate;
    public $ToDate;
    public $key_name;
    public $sortingName;
    public $modelList_color;
    protected $listeners = ['setfromDate', 'settoDate'];





    public function getModelList(){
        $modelList = $modelList = Mtoc::query()
        ->leftjoin('mto_data', 'mtoc.mtocd', 'mto_data.MTOCD')
        ->select(
            DB::raw('CONCAT(REPLACE(mtoc.model_code, " ", ""),mtoc.type_code,
            mtoc.option_code) as m_mto_code'))
            ->groupBy('m_mto_code')
        ->get();
       // dd($modelList);
        if (isset($modelList)) {
            # code...
            $this->modelList = $modelList;
        }
        return $modelList;
       
    }
    public function getModelListColor(){
        $modelList_color=Mtoc::query()
        ->leftjoin('mto_data', 'mtoc.mtocd', 'mto_data.MTOCD')
        ->select(DB::raw('mtoc.color_name as m_color_name'))
            ->groupBy(DB::raw('m_color_name'))
            ->Orderby('mtoc.color_name','asc')
        ->get();
        if (isset($modelList_color)) {
        # code...
        $this->modelList_color = $modelList_color;
        }
        return $modelList_color;
    }
    public function setfromDate($time)
    {
        $this->FromDate = date('Y-m-d', strtotime($time['fromDate']));
    }

    public function settoDate($time)
    {
        $this->ToDate = date('Y-m-d', strtotime($time['toDate']));
    }

    public function render()
    {
        if ($this->reset) {
            $this->reset = null;
            $this->Model = null;
            $this->key_name = 'motorbikes.id';
            $this->sortingName = 'desc';
        }

        //$this->Model = trim($this->Model);
        $this->key_name = trim($this->key_name);
        $this->sortingName = trim($this->sortingName);

        $modelList =$this->getModelList();
        $modelList_color=$this->getModelListColor();
        $warehouseList = Warehouse::orderBy('name')->pluck('name', 'id');
        $data =$this->getData();
        $this->dispatchBrowserEvent('setSelect2');
        return view('livewire.quanlykho.baocaoxetheomodel', [
            'data' => $data,'modelList'=>$modelList,'modelList_color'=>$modelList_color,'warehouselist'=>$warehouseList
        ]);
    }

    /**
     * filter color list from model code
     */
    public function filterColor($modelcode){

    }

    public function getData()
    {
        $data = Motorbike::where('is_out', EMotorbike::NOT_OUT)
        ->join('warehouse',function($join){
            $join->on('motorbikes.warehouse_id','=','warehouse.id');
            $join->whereNull('deleted_at');
        })
        ->join('hms_receive_plan',function ($join){
            $join->on('motorbikes.chassic_no','=','hms_receive_plan.chassic_no');
            $join->on('motorbikes.engine_no','=','hms_receive_plan.engine_no');
        })
        ->where('status',EMotorbike::NEW_INPUT)
        ->whereNull('customer_id')
        ->select('motorbikes.model_code','motorbikes.color',
        DB::raw('count(motorbikes.id) as total_number'),
        DB::raw('warehouse.name as warehouse_name'),
        DB::raw('CONCAT(hms_receive_plan.model_code,hms_receive_plan.type_code,
                hms_receive_plan.option_code) as m_mto_code'),
        DB::raw('hms_receive_plan.color_code as m_color_code'),
        DB::raw('AVG(motorbikes.price) as m_price')
        )

        ->groupBy('motorbikes.model_code','motorbikes.color','warehouse.name',
                    'hms_receive_plan.model_code','hms_receive_plan.type_code',
                    'hms_receive_plan.option_code','hms_receive_plan.color_code'

        );

        $data->where(function () use ($data) {
            if (isset($this->Model) && !empty($this->Model)) {
                $data->where(DB::raw('CONCAT(hms_receive_plan.model_code,hms_receive_plan.type_code,
                hms_receive_plan.option_code)'), $this->Model) ;
                //$data->where('motorbikes.model_code', $this->Model);
            }
        });
       

        $data->where(function () use ($data) {
            if (isset($this->Color) && !empty($this->Color)) {
               // dd($this->Color);
                $data->where(DB::raw('REPLACE(hms_receive_plan.color, " ", "")'), $this->Color) ;
                //$data->where('motorbikes.model_code', $this->Model);
            }
        });

        $data->where(function () use ($data) {
            if (isset($this->warehouse) && !empty($this->warehouse)) {
                $data->where('motorbikes.warehouse_id', $this->warehouse);
            }
        });

        $data = $data->get();
        $this->totalnumber = 0;
        $this->totalprice =0;
        foreach ($data as $key => $item) {
           /*
           $count = "SELECT COUNT(id) AS total FROM motorbikes
            WHERE model_code = '" . $item['model_code'] . "' and color ='".$item->color."'";
            $data[$key]['total_number'] = DB::select($count)[0]->total;
            $this->totalnumber +=DB::select($count)[0]->total;
            */
            $this->totalnumber +=  isset($item['total_number']) ? $item['total_number'] : 0;
            $this->totalprice += (isset($item['total_number']) && isset($item['m_price']) )
                                 ? $item['total_number']  * $item['m_price'] : 0;
        }
       
        return $data;
    }

    public function export()
    {
        $data = $this->getData();

        return Excel::download(new ReportMotorbikeModelExport(
           $data
        ), 'baocaoxetheomodule_' . date('Y-m-d-His') . '.xlsx');
    }
}
