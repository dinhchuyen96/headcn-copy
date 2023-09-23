<?php

namespace App\Http\Livewire\Quanlykho;

use App\Http\Livewire\Base\BaseLive;
use App\Models\Warehouse;
use App\Models\PositionInWarehouse;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AccessoriesExport;
use App\Enum\EMotorbike;

class ChangeMotobikesWarehouse extends BaseLive
{
    public $ship = 1;
    public $soKhung;
    public $soMay;
    public $modelXe;
    public $theLoai;
    public $sortingName;
    public $nextPage = 0;
    public $tranferDatefrom;
    public $tranferDateto;
    protected $listeners = ['setTranferDatefrom', 'setTranferDateto'];
    public function mount()
    {
        $this->tranferDatefrom = date('Y-m-d');
        $this->tranferDateto = date('Y-m-d');
    }

    public function render()
    {

        $nextPage = request()->page ?? 1;
        $warehouseTranferHistory = DB::table('warehouse_tranfer_history')
            ->join('warehouse as WarehouseFrom', 'warehouse_tranfer_history.from_warehouse_id', '=', 'WarehouseFrom.id')
            ->join('warehouse as WarehouseTo', 'warehouse_tranfer_history.to_warehouse_id', '=', 'WarehouseTo.id')
            ->join('motorbikes', 'warehouse_tranfer_history.product_id', '=', 'motorbikes.id')
            ->where('is_out', EMotorbike::NOT_OUT)
            ->select(
                'warehouse_tranfer_history.*',
                'WarehouseFrom.name as WarehouseFromName',
                'WarehouseTo.name as WarehouseToName',
                'warehouse_tranfer_history.tranfer_date',
                'motorbikes.chassic_no',
                'motorbikes.engine_no',
                'motorbikes.model_code',
                'motorbikes.model_list'
            );

        // sql thô
        $transferto = $this->tranferDateto;
        $transferfrom = $this->tranferDatefrom;
        $warehouseTranferHistory->where(function ($query) use ($transferto, $transferfrom) {
            if ($this->soKhung) {
                $query->where('chassic_no', 'like', '%' . trim($this->soKhung) . '%');
            }
            if ($this->soMay) {
                $query->where('engine_no', 'like', '%' . trim($this->soMay) . '%');
            }
            if ($this->modelXe) {
                $query->where('model_code', 'like', '%' . trim($this->modelXe) . '%');
            }
            if ($this->theLoai) {
                $query->where('model_list', 'like', '%' . trim($this->theLoai) . '%');
            }
            if (!empty($transferfrom)) {
                $query->where('warehouse_tranfer_history.tranfer_date', '>=', $transferfrom);
            }
            if (!empty($transferto)) {
                $query->where('warehouse_tranfer_history.tranfer_date', '<=', $transferto);
            }
        });
        if ($this->reset) {
            $this->soKhung = '';
            $this->soMay = '';
            $this->modelXe = '';
            $this->theLoai = '';
            $this->tranferDatefrom = '';
            $this->tranferDateto = '';
        }
        if ($this->key_name && $this->sortingName) {
            $warehouseTranferHistory = $warehouseTranferHistory->orderBy($this->key_name, $this->sortingName);
        }
        $groupByCode = $warehouseTranferHistory->skip($nextPage - 1)->take($this->perPage)->get();
        $dataAC = $warehouseTranferHistory->paginate($this->perPage);
        $data = $groupByCode->groupBy(function ($item, $key) {
            return $item->chassic_no . '@##@' . $item->engine_no . '@##@' . $item->model_code . '@##@' . $item->model_list;
        });
        return view('livewire.quanlykho.lichsuchuyenxe', compact('data', 'dataAC'));
    }
    public function setTranferDatefrom($timefrom)
    {
        $this->tranferDatefrom = date('Y-m-d', strtotime($timefrom['timefrom']));
    }
    public function setTranferDateto($timeto)
    {
        $this->tranferDateto = date('Y-m-d', strtotime($timeto['timeto']));
    }
}
