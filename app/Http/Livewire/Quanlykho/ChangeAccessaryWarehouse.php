<?php

namespace App\Http\Livewire\Quanlykho;

use App\Http\Livewire\Base\BaseLive;
use App\Models\Warehouse;
use App\Models\PositionInWarehouse;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AccessoriesExport;

class ChangeAccessaryWarehouse extends BaseLive
{
    public $ship = 1;
    public $nameProduct;
    public $codeProduct;
    public $sortingName;
    public $nextPage = 0;
    public $FromDate;
    public $ToDate;
    public $tranferDateto;
    public $tranferDatefrom;
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
            ->join('position_in_warehouse as toPos', 'toPos.id', '=', 'warehouse_tranfer_history.from_position_in_warehouse_id')
            ->join('position_in_warehouse as fromPos', 'fromPos.id', '=', 'warehouse_tranfer_history.to_position_in_warehouse_id')
            ->join('warehouse as toWarehouse', 'toWarehouse.id', '=', 'toPos.warehouse_id')
            ->join('warehouse as fromWarehouse', 'fromWarehouse.id', '=', 'fromPos.warehouse_id')
            ->join('accessories', 'accessories.id', '=', 'warehouse_tranfer_history.product_id')
            ->select(
                DB::raw("CONCAT(toWarehouse.name,'-',toPos.name) as namePosTo"),
                DB::raw("CONCAT(fromWarehouse.name,'-',fromPos.name) as namePosFrom"),
                'warehouse_tranfer_history.quantity as totalProduct',
                'accessories.name as nameProducts',
                'warehouse_tranfer_history.tranfer_date as dayChange',
                'accessories.code'
            );
        // sql thô
        $transferto = $this->tranferDateto;
        $transferfrom = $this->tranferDatefrom;
        $warehouseTranferHistory->where(function ($query) use ($transferto, $transferfrom) {
            if ($this->nameProduct) {
                $query->where('accessories.name', 'like', '%' . trim($this->nameProduct) . '%');
            }
            if ($this->codeProduct) {
                $query->where('code', 'like', '%' . trim($this->codeProduct) . '%');
            }
            if (!empty($transferfrom)) {
                $query->where('warehouse_tranfer_history.tranfer_date', '>=', $transferfrom);
            }
            if (!empty($transferto)) {
                $query->where('warehouse_tranfer_history.tranfer_date', '<=', $transferto . ' 23:59:59');
            }
        });
        if ($this->reset) {
            $this->nameProduct = '';
            $this->codeProduct = '';
        }
        if ($this->key_name && $this->sortingName) {
            $warehouseTranferHistory = $warehouseTranferHistory->orderBy($this->key_name, $this->sortingName);
        }
        $groupByCode = $warehouseTranferHistory->skip($nextPage - 1)->take($this->perPage)->get();
        $dataAC = $warehouseTranferHistory->paginate($this->perPage);
        $data = $groupByCode->groupBy(function ($item, $key) {
            return $item->code . '@##@' . $item->nameProducts;
        });
        return view('livewire.quanlykho.lichsuchuyenphutung', compact('data', 'dataAC'));
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
