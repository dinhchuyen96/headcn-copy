<?php

namespace App\Http\Livewire\Quanlykho;

use App\Http\Livewire\Base\BaseLive;
use App\Enum\ReasonType;
use Illuminate\Support\Facades\DB;

class Lichsunhapxuatngoaile extends BaseLive
{
    public $log_type;

    public $key_name;
    public $sortingName;

    public function render()
    {
        $reasonType = [];
        foreach ([ReasonType::INPUT, ReasonType::OUTPUT] as $key => $value) {
            $item = [
                'value' => $value,
                'text' => ReasonType::getDescription($value),
            ];
            $reasonType[] = $item;
        }
        $this->dispatchBrowserEvent('setSelect2');

        if ($this->reset) {
            $this->reset = null;
            $this->log_type = null;
            $this->key_name = 'accessory_change_logs.created_at';
            $this->sortingName = 'desc';
        }

        $query = DB::table("accessory_change_logs")
            ->select(
                'accessory_change_logs.accessory_code',
                'accessory_change_logs.accessory_quantity',
                'accessory_change_logs.reason',
                'accessory_change_logs.description',
                'accessory_change_logs.type',
                'accessory_change_logs.created_at',
                'accessories.name AS accessory_name',
                'warehouse.name AS warehouse_name',
                'position_in_warehouse.name AS position_in_warehouse_name',
            )
            ->leftJoin('accessories', function ($join) {
                $join->on('accessory_change_logs.accessory_id', '=', 'accessories.id');
            })
            ->leftJoin('warehouse', function ($join) {
                $join->on('accessory_change_logs.warehouse_id', '=', 'warehouse.id');
            })
            ->leftJoin('position_in_warehouse', function ($join) {
                $join->on('accessory_change_logs.position_in_warehouse_id', '=', 'position_in_warehouse.id');
            });

        $query->where(function () use ($query) {
            if (!empty($this->log_type)) {
                $query->where('accessory_change_logs.type', $this->log_type);
            }
        });
        if ($this->key_name) {
            $query->orderBy($this->key_name, $this->sortingName);
        }
        $historyList = $query->paginate($this->perPage)->setPath(route('quanlykho.lichsunhapxuatngoaile.index'));

        return view('livewire.quanlykho.lichsunhapxuatngoaile', [
            'historyList' => $historyList,
            'reasonType' => $reasonType,
        ]);
    }
}
