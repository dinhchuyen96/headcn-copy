<?php

namespace App\Http\Livewire\Quanlykho;

use App\Http\Livewire\Base\BaseLive;
use App\Models\Warehouse;
use App\Models\PositionInWarehouse;
use App\Models\Accessory;
use App\Models\AccessoryChangeLog;
use App\Enum\ReasonChangeInput;
use App\Enum\ReasonType;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Nhapngoaile extends BaseLive
{
    public $warehouses;
    public $warehouses_name;
    public $position_in_warehouse;
    public $position_in_warehouse_name;
    public $reason;
    public $description;
    public $accessory_id;
    public $accessory_code;
    public $accessory_name;
    public $accessory_quantity;
    public $accessory_quantity_current = 0;

    public $accessories = [];
    public $i = 0;

    public $key_name;
    public $sortingName;
    protected $listeners = ['changeAccessoryCode', 'changeWarehouses', 'changePosition'];

    public function addAccessory($i)
    {
        $this->validate(
            [
                'reason' => 'required',
                'accessory_code' => 'required',
                'accessory_quantity' => 'required',
                'warehouses_name' => 'required',
            ],
            [
                'reason.required' => 'Bắt buộc chọn lý do',
                'accessory_code.required' => 'Bắt buộc phải nhập mã phụ tùng',
                'accessory_quantity.required' => 'Bắt buộc nhập số lượng',
                'warehouses_name.required' => 'Bắt buộc chọn kho nhập',
            ]
        );
        if ($this->accessory_quantity < 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Số lượng phải lớn hơn 0']);
            return;
        }
        $data = [
            'accessory_id' => $this->accessory_id,
            'accessory_code' => $this->accessory_code,
            'accessory_name' => $this->accessory_name,
            'accessory_quantity' => $this->accessory_quantity,
            'accessory_quantity_current' => $this->accessory_quantity_current,
            'warehouse_id' => $this->warehouses,
            'warehouses_name' => $this->warehouses_name,
            'position_in_warehouse_id' => $this->position_in_warehouse,
            'position_in_warehouse_name' => $this->position_in_warehouse_name,
            'reason' => $this->reason,
            'description' => $this->description,
            'quantity_log' => $this->accessory_quantity_current . '+' . $this->accessory_quantity . '=' . ($this->accessory_quantity_current - $this->accessory_quantity),
        ];

        $i = $i + 1;
        $this->i = $i;

        $this->accessories[$i] = $data;
        $this->resetInput();
    }

    public function removeAccessory($i)
    {
        unset($this->accessories[$i]);
    }

    public function changeAccessoryCode($code)
    {
        $accessory = Accessory::where('code', $code)->firstOrFail();
        $this->accessory_id = $accessory->id;
        $this->accessory_name = $accessory->name;
        $this->accessory_quantity_current = $accessory->quantity;
    }

    public function changeWarehouses($id)
    {
        $warehouse = Warehouse::where('id', $id)->firstOrFail();
        $this->warehouses_name = $warehouse->name;
    }

    public function changePosition($id)
    {
        $position = PositionInWarehouse::where('id', $id)->firstOrFail();
        $this->position_in_warehouse_name = $position->name;
    }

    public function resetInput()
    {
        $this->warehouses = '';
        $this->warehouses_name = '';
        $this->position_in_warehouse = '';
        $this->position_in_warehouse_name = '';
        $this->reason = '';
        $this->description = '';
        $this->accessory_id = '';
        $this->accessory_code = '';
        $this->accessory_name = '';
        $this->accessory_quantity = '';
        $this->quantity_log = '';
    }

    public function render()
    {
        $reasonList = [];
        foreach ([ReasonChangeInput::ONE, ReasonChangeInput::TWO, ReasonChangeInput::THREE, ReasonChangeInput::FOUR, ReasonChangeInput::FIVE, ReasonChangeInput::SIX] as $key => $value) {
            $item = [
                'value' => $value,
                'text' => ReasonChangeInput::getDescription($value),
            ];
            $reasonList[] = $item;
        }
        $warehouseList = Warehouse::orderBy('name')->pluck('name', 'id');
        $positionList = [];
        if ($this->warehouses) {
            $positionList = PositionInWarehouse::where('warehouse_id', $this->warehouses)->orderBy('name')->pluck('name', 'id');
        }

        $this->dispatchBrowserEvent('setSelect2');

        return view('livewire.quanlykho.nhapngoaile', [
            'warehouseList' => $warehouseList,
            'positionList' => $positionList,
            'reasonList' => $reasonList
        ]);
    }

    public function store()
    {
        DB::beginTransaction();
        
        if (!empty($this->accessories)) {
            try {
                $dataSave = [];
                $dataUpdate = [];
                $accessory = new Accessory;
                foreach ($this->accessories as $key => $item) {
                    $dataSave[] = [
                        'accessory_id' => $item['accessory_id'],
                        'accessory_code' => $item['accessory_code'],
                        'accessory_quantity' => $item['accessory_quantity'],
                        'warehouse_id' => $item['warehouse_id'],
                        'position_in_warehouse_id' => $item['position_in_warehouse_id'],
                        'reason' => $item['reason'],
                        'description' => $item['description'],
                        'quantity_log' => $item['quantity_log'],
                        'type' => ReasonType::INPUT,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                    $dataUpdate[] = [
                        'id' => $item['accessory_id'],
                        'quantity' => $item['accessory_quantity_current'] + $item['accessory_quantity'],
                        'updated_at' => Carbon::now()
                    ];
                }

                if (!AccessoryChangeLog::insert($dataSave)) {
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Thêm mới không thành công']);
                    return;
                }

                $updated = \Batch::update($accessory, $dataUpdate, 'id');
                if (!$updated) {
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Thêm mới không thành công']);
                    return;
                }
                
                DB::commit();
                $this->accessories = [];
                $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Thêm mới thành công']);
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Thêm mới không thành công']);
                $this->resetInput();
            
        }} else {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Thêm mới không thành công']);
            
        } 

        
    }
}
