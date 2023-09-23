<?php

namespace App\Http\Livewire\Quanlykho;

use App\Http\Livewire\Base\BaseLive;

use App\Models\Warehouse;
use App\Models\Motorbike;
use App\Enums\MotobikeStatus;
use App\Enum\ETranferType;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use App\Imports\ChuyenKhoMotorbikeImport;
use App\Models\WarehouseTranferHistory;
use App\Enum\EMotorbike;

class TransferMotobikes extends BaseLive
{
    public $tranferDate;
    public $sourceWarehouseId='';
    public $destinationWarehouseId='';
    public $chassicNo;
    public $engineNo;
    public $barcode;
    public $warehouseSourceList;
    public $warehouseDetinationList;


    public $chassicNoId;
    public $engineNoId;

    public $chassicNoList;
    public $engineNoList;


    public $tranferMotobikeList;
    public $file;

    public $barCode;

    protected $listeners = ['changeSource', 'changeDestination', 'addMotobike', 'changeChassicNo', 'changeEngineNo', 'setTranferDate',  'addBarCode'];

    public function mount()
    {
        $this->tranferDate = date('Y-m-d');
        $this->warehouseSourceList = Warehouse::select('id', 'name')->get();
        $this->warehouseDetinationList  = Warehouse::select('id', 'name')->get();
        $this->chassicNoList = collect([]);
        $this->engineNoList = collect([]);
        $this->tranferMotobikeList = collect([]);
    }
    public function render()
    {
        if (!empty($this->key_name)) {
            if ($this->sortingName == 'desc') {
                $this->tranferMotobikeList = collect(array_values($this->tranferMotobikeList->sortByDesc($this->key_name)->toArray()));
            } else {
                $this->tranferMotobikeList = collect(array_values($this->tranferMotobikeList->sortBy($this->key_name)->toArray()));
            }
        }
        if ($this->sourceWarehouseId) {
            if (isset($this->engineNoId)) {
                $this->chassicNoList = Motorbike::whereNull('customer_id')
                    ->where('is_out', EMotorbike::NOT_OUT)
                    ->where('warehouse_id', $this->sourceWarehouseId)
                    ->where('engine_no', $this->engineNoId)
                    ->get()->pluck('chassic_no')->filter(function ($value, $key) {
                    $this->chassicNoId = isset($this->chassicNoList[0]) ? $this->chassicNoList[0] : '' ;
                    return !$this->tranferMotobikeList->contains('chassic_no', $value);
                });

            }elseif(isset($this->chassicNoId)){
                $this->engineNoList = Motorbike::whereNull('customer_id')
                    ->where('is_out', EMotorbike::NOT_OUT)
                    ->where('warehouse_id', $this->sourceWarehouseId)
                    ->where('chassic_no', $this->chassicNoId)
                    ->get()->pluck('chassic_no')->filter(function ($value, $key) {
                        $this->engineNoId= isset($this->engineNoList[0]) ? $this->engineNoList[0] : '' ;
                    return !$this->tranferMotobikeList->contains('chassic_no', $value);
                });
            }
            else{
                $this->chassicNoList = Motorbike::whereNull('customer_id')->where('is_out', EMotorbike::NOT_OUT)->where('warehouse_id', $this->sourceWarehouseId)->get()->pluck('chassic_no')->filter(function ($value, $key) {
                return !$this->tranferMotobikeList->contains('chassic_no', $value);
                });
            }



            if (isset($this->chassicNoId)) {
                # code...
                $this->engineNoList = Motorbike::whereNull('customer_id')->where('is_out', EMotorbike::NOT_OUT)->where('warehouse_id', $this->sourceWarehouseId)
                ->where('chassic_no', $this->chassicNoId)
                ->get()->pluck('engine_no')->filter(function ($value, $key) {
                    $this->engineNoId = isset($this->engineNoList[0]) ? $this->engineNoList[0] : '';
                    return !$this->tranferMotobikeList->contains('engine_no', $value);
                });
            }else
            {
                $this->engineNoList = Motorbike::whereNull('customer_id')->where('is_out', EMotorbike::NOT_OUT)->where('warehouse_id', $this->sourceWarehouseId)
                ->get()->pluck('engine_no')->filter(function ($value, $key) {
                    return !$this->tranferMotobikeList->contains('engine_no', $value);
                });
            }

        }

        $this->updateUI();
        return view('livewire.quanlykho.chuyenkhoxemay');
    }
    public function changeSource($itemSelected)
    {
        $listWarehouse = Warehouse::select('id', 'name')->get();
        $this->warehouseDetinationList = $listWarehouse->where('id', '!=',  $itemSelected);
        $this->warehouseSourceList =  $listWarehouse;
    }
    public function changeDestination($itemSelected)
    {
        $listWarehouse = Warehouse::select('id', 'name')->get();
        $this->warehouseDetinationList = $listWarehouse;
        $this->warehouseSourceList =  $listWarehouse->where('id', '!=',  $itemSelected);
    }
    public function changeChassicNo($itemSelected)
    {
        if (isset($itemSelected) && !empty($itemSelected)) {
            $this->chassicNoId = $itemSelected;
            $engineNo = Motorbike::whereNull('customer_id')
                ->where('is_out', EMotorbike::NOT_OUT)
                ->where('chassic_no', $itemSelected)->select('engine_no')
                ->get();
            $this->engineNoList = $engineNo->pluck('engine_no');
        } else {
            $this->chassicNoList = Motorbike::whereNull('customer_id')->where('warehouse_id', $this->sourceWarehouseId)
                ->where('is_out', EMotorbike::NOT_OUT)
                ->get()->pluck('chassic_no');
        }
    }
    public function changeEngineNo($itemSelected)
    {
        if (isset($itemSelected) && !empty($itemSelected)) {
            $this->engineNoId = $itemSelected ;
            $chassicNo = Motorbike::whereNull('customer_id')
                ->where('is_out', EMotorbike::NOT_OUT)
                ->where('engine_no', $itemSelected)
                ->get();
            $this->chassicNoList = $chassicNo->pluck('chassic_no');
        } else {
            $this->engineNoList = Motorbike::whereNull('customer_id')
                ->where('is_out', EMotorbike::NOT_OUT)
                ->where('warehouse_id', $this->sourceWarehouseId)
                ->get()->pluck('engine_no');
        }
    }
    public function setTranferDate($time)
    {
        $this->tranferDate = date('Y-m-d', strtotime($time['tranferDate']));
    }
    public function addMotobike()
    {
        if (empty($this->sourceWarehouseId)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Hãy chọn kho nguồn']);
            return;
        }
        if (empty($this->chassicNoId) && empty($this->engineNoId)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Hãy chọn số khung và số máy cần xuất']);
            return;
        }
        $isExist =false;
        if (isset( $this->chassicNoId)) {
            # code...
            $isExist = $this->tranferMotobikeList
        ->where('chassic_no', $this->chassicNoId)
        ->first();
        }elseif(isset($this->engineNoId)){
            $isExist = $this->tranferMotobikeList
            ->where('engine_no', $this->engineNoId)
            ->first();
        }

        if (empty($isExist)) {
            $motoBike = Motorbike::whereNull('customer_id')
                ->where('is_out', EMotorbike::NOT_OUT)
                ->where('chassic_no', $this->chassicNoId)
                ->orWhere('engine_no', $this->engineNoId)
                ->select('chassic_no', 'engine_no', 'model_type', 'color', 'quantity')->first();

            $this->tranferMotobikeList->push($motoBike);
            $this->chassicNoId = null ;
            $this->engineNoId = null ;

        } else {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Xe đã có trong danh sách chuẩn bị xuất kho']);
        }
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
        $this->dispatchBrowserEvent('setTranferDatePicker');
    }

    public function downloadExample()
    {
        $this->updateUI();
        return Storage::disk('public')->download('mau_file_chuyen_kho_xe_may.xlsx');
    }

    public function import()
    {
        if (empty($this->sourceWarehouseId)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Hãy chọn kho nguồn']);
            $this->dispatchBrowserEvent('closeModalImport');
            return;
        }
        $this->validate([
            'file' => 'required'
        ], [
            'file.required' => 'Hãy chọn file để import',
        ]);
        $collection = Excel::toCollection(new ChuyenKhoMotorbikeImport, $this->file);
        $sheetData = $collection[0];
        $listMotobike = Motorbike::whereNull('customer_id')
            ->where('is_out', EMotorbike::NOT_OUT)
            ->where('warehouse_id', $this->sourceWarehouseId)->get();
        if ($listMotobike->isEmpty()) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Kho nguồn không có xe để chuyển']);
            $this->dispatchBrowserEvent('closeModalImport');
            return;
        }
        $listMotobike = collect($listMotobike->toArray());
        $rowNo = 1;
        $this->tranferMotobikeList = collect([]);
        foreach ($sheetData as $key => $value) {
            $chassicNo = trim($value[0]);
            $engineNo = trim($value[1]);
            $itemMotobike = $listMotobike->where('chassic_no', $chassicNo)->where('engine_no', $engineNo)->first();
            if (empty($itemMotobike)) {
                $errorMessage = 'Dòng ' . $rowNo . ': Cặp số khung - Số máy không có trong kho';
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $errorMessage]);
                return;
            }
            $isExistTmp = $this->tranferMotobikeList->where('chassic_no', $chassicNo)->where('engine_no', $engineNo)->first();
            if (empty($isExistTmp)) {
                $this->tranferMotobikeList->push($itemMotobike);
            }
        }
        $this->dispatchBrowserEvent('closeModalImport');
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => "Dữ liệu xe (" . $this->tranferMotobikeList->count() . " item) import thành công"]);
    }
    public function remove($index)
    {
        $this->tranferMotobikeList->pull($index);
    }
    public function tranferMotobike()
    {
        $this->validate([
            'tranferDate' => 'required',
            'sourceWarehouseId' => 'required',
            'destinationWarehouseId' => 'required'
        ], [
            'tranferDate.required' => 'Ngày chuyển kho bắt buộc phải chọn',
            'sourceWarehouseId.required' => 'Kho nguồn bắt buộc',
            'destinationWarehouseId.required' => 'Kho đích bắt buộc'
        ], []);
        if ($this->tranferMotobikeList->isEmpty()) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Không có xe nào trong danh sách xe xuất kho']);
            return;
        }
        try {
            foreach ($this->tranferMotobikeList as $key => $value) {
                $motobikeUpdate = Motorbike::where('chassic_no', $value['chassic_no'])
                    ->where('is_out', EMotorbike::NOT_OUT)
                    ->where('engine_no', $value['engine_no'])->get()->first();
                $motobikeUpdate->warehouse_id = $this->destinationWarehouseId;
                $motobikeUpdate->save();
                $dataTranferHistory = [
                    'from_warehouse_id' => (int)$this->sourceWarehouseId,
                    'to_warehouse_id' => (int)$this->destinationWarehouseId,
                    'from_position_in_warehouse_id' => null,
                    'to_position_in_warehouse_id' => null,
                    'product_id' => $motobikeUpdate->id,
                    'tranfer_type' => ETranferType::Motobike,
                    'tranfer_date' => $this->tranferDate,
                    'quantity' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
                WarehouseTranferHistory::create($dataTranferHistory);
            }
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Đã chuyển ' . $this->tranferMotobikeList->count() . ' xe thành công']);
            $this->resetData();
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Có lỗi xảy ra khi lưu vào databasse']);
        }
    }
    public function resetData()
    {
        $this->tranferMotobikeList = collect([]);
        $this->sourceWarehouseId = '';
        $this->destinationWarehouseId = '';
        $this->barcode = '';
    }
    public function addBarCode($code)
    {
        $this->validate([
            'barCode' => 'required',
            'sourceWarehouseId' => 'required'

        ], [
            'barCode.required' => 'Barcode bắt buộc phải nhập',
            'sourceWarehouseId.required' => 'Kho nguồn bắt buộc',
        ], []);

        $motobike = Motorbike::whereNull('customer_id')
            ->where('is_out', EMotorbike::NOT_OUT)
            ->where('warehouse_id', $this->sourceWarehouseId)
            ->where('chassic_no', $code)
            ->get()->first();
        if (empty($motobike)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Không tồn tại số khung trong kho']);
            return;
        }
        $isExisted = $this->tranferMotobikeList
            ->where('chassic_no', $motobike['chassic_no'])
            ->where('engine_no', $motobike['engine_no'])->first();
        if (!empty($isExisted)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Số khung đã nằm trong danh sách xe xuất kho']);
            return;
        }
        $this->tranferMotobikeList->push($motobike);
        $this->barcode = null;
    }
}
