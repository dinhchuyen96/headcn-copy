<?php

namespace App\Http\Livewire\Service;

use App\Http\Livewire\Base\BaseLive;
use App\Models\Accessory;
use App\Models\OrderDetail;
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AtrophyExport;

class AtrophyAccessory extends BaseLive
{
    public $listSelectAccessory;
    public $accessoryId;
    public $key_name = "accessories_code";
    public $sortingName = "asc";
    public function mount()
    {
        $this->listSelectAccessory = Accessory::select('id', 'code', 'name')->get();
    }
    public function render()
    {
        $data = $this->getQuerySearch()->paginate($this->perPage);
        $this->updateUI();
        return view('livewire.service.atrophy-accessory', compact('data'));
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
    public function export()
    {
        $this->updateUI();
        $dataExport =  $this->getQuerySearch()->get();
        if ($dataExport->isEmpty()) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Không có bản ghi nào!']);
            $this->emit('close-modal-export');
        } else {
            return Excel::download(new AtrophyExport($dataExport), 'dsphutungchothaythe_' . date('Y-m-d-His') . '.xlsx');
        }
    }
    public function getQuerySearch()
    {
        $query = OrderDetail::join('orders', 'order_details.order_id', 'orders.id')
            ->join('accessories', 'order_details.product_id', 'accessories.id')
            ->join('repair_bill', 'orders.id', 'repair_bill.orders_id')
            ->join('motorbikes', 'repair_bill.motorbikes_id', 'motorbikes.id')
            ->join('customers', 'orders.customer_id', 'customers.id')
            ->where('order_details.status', EOrderDetail::STATUS_SAVED)
            ->where('order_details.category', EOrderDetail::CATE_REPAIR)
            ->where('order_details.type', EOrderDetail::TYPE_BANLE)
            ->where('order_details.is_atrophy', EOrderDetail::ATROPHY_ACCESSORY);

        if ($this->accessoryId) {
            $query = $query->where('accessories.id',  $this->accessoryId);
        }

        $query = $query->select(DB::raw('accessories.code as accessories_code
        ,accessories.name as accessories_name
        ,order_details.quantity as order_details_quantity
        ,order_details.id as order_details_id
        ,customers.name as customer_name
        ,customers.phone as customer_phone
        ,motorbikes.motor_numbers as mortorbike_number
        ,repair_bill.in_factory_date as repair_date
        '))->orderBy($this->key_name, $this->sortingName);
        return $query;
    }
}
