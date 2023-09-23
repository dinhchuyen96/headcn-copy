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
use App\Imports\ChuyenKhoPhuTungImport;
use App\Models\WarehouseTranferHistory;
use App\Models\PositionInWarehouse;
use App\Models\Accessory;

class TransferAccessories extends BaseLive
{
    public $tranferDate;
    public $positionSourceWarehouseId = '';
    public $positionDestinationWarehouseId = '';
    public $accessoryCode;
    public $quatity;

    public $positionSourceWarehouseList;
    public $positionDetinationWarehouseList;

    public $accessoryCodeList;

    public $tranferAccessoryList;
    public $file;


    protected $listeners = ['changeSource', 'changeDestination', 'addAccessory', 'changeAccessoryCode', 'setTranferDate', 'removeFromChild', 'updateToParent'];

    public function mount()
    {
        $this->tranferDate = date('Y-m-d');
        $positionSourceWarehouseListDb = PositionInWarehouse::with(['warehouse'])->whereHas('warehouse', function ($q) {
            $q->whereNull('deleted_at');
        })->get();
        $this->positionSourceWarehouseList = $positionSourceWarehouseListDb->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'name' => $item->warehouse->name . " - " . $item->name
            ];
        });

        $this->positionDetinationWarehouseList = $positionSourceWarehouseListDb->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'name' => $item->warehouse->name . " - " . $item->name
            ];
        });

        $this->accessoryCodeList = collect([]);

        $this->tranferAccessoryList = collect([]);
    }
    public function render()
    {
        if (!empty($this->key_name)) {
            if ($this->sortingName == 'desc') {
                $this->tranferAccessoryList = collect(array_values($this->tranferAccessoryList->sortByDesc($this->key_name)->toArray()));
            } else {
                $this->tranferAccessoryList = collect(array_values($this->tranferAccessoryList->sortBy($this->key_name)->toArray()));
            }
        }
        if (!empty($this->positionSourceWarehouseId)) {
            $this->accessoryCodeList = Accessory::where('position_in_warehouse_id', $this->positionSourceWarehouseId)->get()->pluck('code');
        }

        $this->updateUI();
        return view('livewire.quanlykho.chuyenkhophutung', $this->tranferAccessoryList);
    }
    public function changeSource($itemSelected)
    {
        $warehouseId = null;
        if (!empty($itemSelected)) {
            $warehouseId = PositionInWarehouse::find($itemSelected)->warehouse_id;
        }

        $positionWarehouseListDb = PositionInWarehouse::whereHas(
            'warehouse',
            function ($q) use ($warehouseId) {
                $q->where('id', '<>', $warehouseId);
            }
        )->get();
        $this->positionDetinationWarehouseList = $positionWarehouseListDb->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'name' => $item->warehouse->name . " - " . $item->name
            ];
        });
    }
    public function changeDestination($itemSelected)
    {
        $warehouseId = null;
        if (!empty($itemSelected)) {
            $warehouseId = PositionInWarehouse::find($itemSelected)->warehouse_id;
        }
        $positionWarehouseListDb = PositionInWarehouse::whereHas(
            'warehouse',
            function ($q) use ($warehouseId) {
                $q->where('id', '<>', $warehouseId);
            }
        )->get();

        $this->positionSourceWarehouseList = $positionWarehouseListDb->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'name' => $item->warehouse->name . " - " . $item->name
            ];
        });
    }
    public function changeAccessoryCode($itemSelected)
    {
    }

    public function setTranferDate($time)
    {
        $this->tranferDate = date('Y-m-d', strtotime($time['tranferDate']));
    }
    public function addAccessory()
    {
        if (empty($this->positionSourceWarehouseId)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Hãy chọn vị trí kho nguồn']);
            return;
        }
        if (empty($this->accessoryCode)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Hãy chọn mã phụ tùng cần xuất']);
            return;
        }
        $positionSourceWarehouseIdTmp = $this->positionSourceWarehouseId;
        $accessory = Accessory::where('code', $this->accessoryCode)->with(['positionInWarehouse', 'warehouse'])
            ->whereHas(
                'positionInWarehouse',
                function ($q) use ($positionSourceWarehouseIdTmp) {
                    $q->where('id', $positionSourceWarehouseIdTmp);
                }
            )
            ->first();
        if (empty($accessory)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Mã phụ tùng không tồn tại']);
            return;
        }
        $isExistAccessory = $this->tranferAccessoryList->where('accessory_code', $this->accessoryCode)->first();

        if (empty($isExistAccessory)) {
            $this->validate([
                'quatity' => 'required|numeric|min:1|max:' . $accessory->quantity
            ], [
                'quatity.required' => 'Hãy nhập số lượng',
                'quatity.numeric' => 'Sô lượng phải là số',
                'quatity.min' => 'Số lượng tối thiểu là 1',
                'quatity.max' => 'Số lượng vượt quá số lượng trong kho (' . $accessory->quantity . ')',
            ]);

            if ($this->quatity > $accessory->quantity) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Số lượng nhập lớn hơn số lượng trong kho (' . $accessory->quantity] . ')');
                return;
            }
            $accessoryItem = [
                'accessory_code' => $accessory->code,
                'name' => $accessory->name,
                'position' => $accessory->warehouse->name . " - " . $accessory->positionInWarehouse->name,
                'amount_in_warehouse' => $accessory->quantity,
                'quatity_tranfer' => $this->quatity,
                'remain' => $accessory->quantity - $this->quatity
            ];
            $this->tranferAccessoryList->push($accessoryItem);
        } else {
            $oldQuatity = (int)$isExistAccessory['quatity_tranfer'];
            $this->validate([
                'quatity' => 'required|numeric|min:1|max:' . ($accessory->quantity - $oldQuatity)
            ], [
                'quatity.required' => 'Hãy nhập số lượng',
                'quatity.numeric' => 'Sô lượng phải là số',
                'quatity.min' => 'Số lượng tối thiểu là 1',
                'quatity.max' => 'Số lượng vượt quá số lượng trong kho nếu chuyển (' . ($accessory->quantity - $oldQuatity) . ')',
            ]);

            $accessoryIndex = $this->tranferAccessoryList->search(function ($accessory) {
                return $accessory['accessory_code'] === $this->accessoryCode;
            });
            $quatityTmp = $this->quatity;
            $this->tranferAccessoryList = $this->tranferAccessoryList->map(function ($item, $key) use ($accessoryIndex, $oldQuatity, $quatityTmp) {
                if ($key == $accessoryIndex) {
                    $item['quatity_tranfer'] = $oldQuatity + $quatityTmp;
                    $item['remain'] = $item['amount_in_warehouse'] - $quatityTmp - $oldQuatity;
                }
                return $item;
            });

            $this->emit('updateItem', [
                'quatity_tranfer' => $this->quatity,
                'index' => $accessoryIndex
            ]);
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
        return Storage::disk('public')->download('mau_file_chuyen_kho_phu_tung.xlsx');
    }

    public function import()
    {
        if (empty($this->positionSourceWarehouseId)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Hãy chọn vị trí kho nguồn']);
            $this->dispatchBrowserEvent('closeModalImport');
            return;
        }
        $this->validate([
            'file' => 'required'
        ], [
            'file.required' => 'Hãy chọn file để import',
        ]);
        $collection = Excel::toCollection(new ChuyenKhoPhuTungImport, $this->file);
        $sheetData = $collection[0];
        $positionSourceWarehouseIdTmp = $this->positionSourceWarehouseId;
        $listAccessory = Accessory::where('position_in_warehouse_id', $this->positionSourceWarehouseId)->get();
        if ($listAccessory->isEmpty()) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Kho nguồn không có phụ tùng để chuyển']);
            $this->dispatchBrowserEvent('closeModalImport');
            return;
        }
        $listAccessory = collect($listAccessory->toArray());
        $rowNo = 1;
        $this->tranferAccessoryList = collect([]);
        foreach ($sheetData as $key => $value) {
            $accessoryCodeImport = trim($value[0]);
            $quatityImport = trim($value[1]);
            $itemAccessory = $listAccessory->where('code', $accessoryCodeImport)->first();
            if (empty($itemAccessory)) {
                $errorMessage = 'Dòng ' . $rowNo . ': Mã phụ tùng không có trong kho';
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $errorMessage]);
                return;
            }
            if ($itemAccessory['quantity'] <= 0) {
                $errorMessage = 'Dòng ' . $rowNo . ': Mã phụ tùng không còn trong kho';
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $errorMessage]);
                return;
            }
            if ((int)$quatityImport <= 0) {
                $errorMessage = 'Dòng ' . $rowNo . ': Số lượng tối thiểu là 1';
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $errorMessage]);
                return;
            }
            if ((int)$quatityImport > $itemAccessory['quantity']) {
                $errorMessage = 'Dòng ' . $rowNo . ': Số lượng vượt quá số lượng trong kho (' . $itemAccessory['quantity'] . ')';
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $errorMessage]);
                return;
            }
            $isExistTmp = $this->tranferAccessoryList->where('accessory_code', $accessoryCodeImport)->first();
            if (empty($isExistTmp)) {
                $accessory = Accessory::where('code', $accessoryCodeImport)->with(['positionInWarehouse', 'warehouse'])
                    ->whereHas(
                        'positionInWarehouse',
                        function ($q) use ($positionSourceWarehouseIdTmp) {
                            $q->where('id', $positionSourceWarehouseIdTmp);
                        }
                    )
                    ->first();
                $accessoryItem = [
                    'accessory_code' => $accessory->code,
                    'name' => $accessory->name,
                    'position' => $accessory->warehouse->name . " - " . $accessory->positionInWarehouse->name,
                    'amount_in_warehouse' => $accessory->quantity,
                    'quatity_tranfer' => (int)$quatityImport,
                    'remain' => $accessory->quantity - (int)$quatityImport
                ];
                $this->tranferAccessoryList->push($accessoryItem);
            } else {
                $accessoryIndex = $this->tranferAccessoryList->search(function ($accessory) use ($isExistTmp) {
                    return $accessory['accessory_code'] === $isExistTmp['accessory_code'];
                });
                $isValid = true;
                $this->tranferAccessoryList = $this->tranferAccessoryList->map(function ($item, $key) use ($accessoryIndex, $quatityImport, $isValid) {
                    if ($key == $accessoryIndex) {
                        $item['quatity_tranfer'] += (int)$quatityImport;
                        $item['remain'] = $item['amount_in_warehouse'] - $item['quatity_tranfer'];
                        if ($item['remain'] <= 0)
                            $isValid = false;
                    }
                    return $item;
                });
                if (!$isValid) {
                    $errorMessage = 'Dòng ' . $rowNo . ': Số lượng vượt quá số lượng trong kho (' . $itemAccessory['quantity'] . ')';
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $errorMessage]);
                    return;
                }
                $this->tranferAccessoryList = collect([]);
            }
        }
        $this->dispatchBrowserEvent('closeModalImport');
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => "Dữ liệu phụ tùng (" . $this->tranferAccessoryList->count() . " item) import thành công"]);
    }
    public function removeFromChild($index)
    {
        $this->tranferAccessoryList->pull($index);
    }
    public function tranferAccessory()
    {
        $this->validate([
            'tranferDate' => 'required',
            'positionSourceWarehouseId' => 'required',
            'positionDestinationWarehouseId' => 'required'
        ], [
            'tranferDate.required' => 'Ngày chuyển kho bắt buộc phải chọn',
            'positionSourceWarehouseId.required' => 'Vị trí kho nguồn bắt buộc',
            'positionDestinationWarehouseId.required' => 'Vị trí kho đích bắt buộc'
        ]);
        if ($this->tranferAccessoryList->isEmpty()) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Không có phụ tùng trong danh sách xuất kho']);
            return;
        }
        $isValid = true;
        foreach ($this->tranferAccessoryList as $key => $item) {
            if ((int)$item['amount_in_warehouse'] < (int)$item['quatity_tranfer']) {
                $isValid = false;
                break;
            }
        }
        if (!$isValid) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Kiểm tra lại số lượng phụ tùng chuyển']);
            return;
        }

        try {
            foreach ($this->tranferAccessoryList as $key => $value) {
                $accessoryUpdateFrom = Accessory::where('code', $value['accessory_code'])
                    ->where('position_in_warehouse_id', $this->positionSourceWarehouseId)
                    ->get()->first();
                $accessoryUpdateFrom->quantity = $value['remain'];
                $accessoryUpdateFrom->save();

                $accessoryUpdateTo = Accessory::where('code', $value['accessory_code'])
                    ->where('position_in_warehouse_id', $this->positionDestinationWarehouseId)
                    ->get()->first();
                $warehouseIdFrom = PositionInWarehouse::find($this->positionSourceWarehouseId)->warehouse_id;
                $warehouseIdTo = PositionInWarehouse::find($this->positionDestinationWarehouseId)->warehouse_id;
                if (empty($accessoryUpdateTo)) {
                    $dataAccessory = [
                        'supplier_id' => $accessoryUpdateFrom->supplier_id,
                        'order_id' => $accessoryUpdateFrom->order_id,
                        'name' => $accessoryUpdateFrom->name,
                        'code' => $accessoryUpdateFrom->code,
                        'quantity' => (int)$value['quatity_tranfer'],
                        'price' => $accessoryUpdateFrom->price,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'buy_date' => $accessoryUpdateFrom->buy_date,
                        'admin_id' => $accessoryUpdateFrom->admin_id,
                        'warehouse_id' => $warehouseIdTo,
                        'position_in_warehouse_id' => (int)$this->positionDestinationWarehouseId
                    ];
                    Accessory::create($dataAccessory);
                } else {
                    $accessoryUpdateTo->quantity += (int)$value['quatity_tranfer'];
                    $accessoryUpdateTo->save();
                }

                $dataTranferHistory = [
                    'from_warehouse_id' => $warehouseIdFrom,
                    'to_warehouse_id' => $warehouseIdTo,
                    'from_position_in_warehouse_id' => (int)$this->positionSourceWarehouseId,
                    'to_position_in_warehouse_id' => (int)$this->positionDestinationWarehouseId,
                    'product_id' => $accessoryUpdateFrom->id,
                    'tranfer_type' => ETranferType::Accessory,
                    'tranfer_date' => $this->tranferDate,
                    'quantity' => (int)$value['quatity_tranfer'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
                WarehouseTranferHistory::create($dataTranferHistory);
            }
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Đã chuyển ' . $this->tranferAccessoryList->count() . ' mã phụ tùng thành công']);
            $this->resetData();
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Có lỗi xảy ra khi lưu vào databasse']);
        }
    }
    public function resetData()
    {
        $this->tranferAccessoryList = collect([]);
        $this->positionSourceWarehouseId = '';
        $this->positionDestinationWarehouseId = '';
        $this->quatity = '';
    }
    public function updateToParent($updateParameter)
    {
        $this->tranferAccessoryList = $this->tranferAccessoryList->map(function ($item, $key) use ($updateParameter) {
            if ($key == $updateParameter['index']) {
                $item['quatity_tranfer'] = (int)$updateParameter['quatity_tranfer'];
                $item['remain'] = $item['amount_in_warehouse'] - $item['quatity_tranfer'];
            }
            return $item;
        });
    }
}
