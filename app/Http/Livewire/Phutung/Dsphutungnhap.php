<?php

namespace App\Http\Livewire\Phutung;

use App\Exports\DsPhutungNhapExport;
use App\Http\Livewire\Base\BaseLive;
use App\Models\Accessory;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Supplier;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class Dsphutungnhap extends BaseLive
{
    public $selectedorderid = 0;
    public $perPage = 10;
    public $keyword;
    public $showAdvancesearch = 0;
    public $searchPartNo;
    public $current_part_no;


    public $deleteId = '';
    public $deleteOrderId = '';
    public $searchName;
    public $searchCode;
    public $searchSupplierName;
    public $searchFromDate;
    public $searchToDate;
    public $key_name = 'order_details.created_at';
    protected $listeners = ['setfromDate', 'settoDate', 'setSelectOrder'];

    public function mount()
    {
        $start = Carbon::now();
        $end = Carbon::now();
        $this->searchFromDate = $start->toDateString();
        $this->searchToDate = $end->toDateString();
    }

    public function render()
    {
        if ($this->reset) {
            $this->searchName = null;
            $this->searchCode = null;
            $this->searchSupplierName = null;
            $start = Carbon::now();
            $end = Carbon::now();
            $this->searchFromDate = $start->toDateString();
            $this->searchToDate = $end->toDateString();
        }
        //get receive orders
        $queryOrders = OrderDetail::query();
        $queryOrders = $this->getOrders($queryOrders);
        $dataorders = $queryOrders->paginate($this->perPage);
        //end get receive orders

        $querySupplier = Supplier::query()->select('name')->get();
        $totalQty = 0;
        $totalAmount  = 0;
        if (isset($this->selectedorderid) && $this->selectedorderid != 0) {
            # code...
            $query = OrderDetail::query();
            $query = $this->querySearch($query);

            if ($this->key_name) {
                $query->orderBy($this->key_name, $this->sortingName);
            }
            $totalQty = $query->sum('order_details.quantity');
            $totalAmount = $query->sum(DB::raw('order_details.quantity * order_details.actual_price'));
            $data = $query->paginate($this->perPage);
        } else $data = [];
        return view('livewire.phutung.dsphutungnhap', compact('dataorders', 'data', 'querySupplier', 'totalQty', 'totalAmount'));
    }
    //Set select order drom client
    public function setSelectOrder($orderid)
    {
        $this->selectedorderid = $orderid;
    }
    public function setfromDate($time)
    {
        $this->searchFromDate = date('Y-m-d', strtotime($time['fromDate']));
    }
    public function settoDate($time)
    {
        $this->searchToDate = date('Y-m-d', strtotime($time['toDate']));
    }

    /**
     * Get order by query
     */
    public function getOrders($query)
    {
        $searchName = trim($this->searchName);
        $searchCode = trim($this->searchCode);
        $searchSupplierName = trim($this->searchSupplierName);
        $query->where('order_details.status', '=', '1')
            ->Where('order_details.category', '=', '1')
            ->Where('order_details.type', '=', '3')
            ->orderBy('order_details.order_id', 'DESC');

        if (isset($this->keyword) && trim($this->keyword) != '') {
            # code...
            $query->orWhere('accessories.code', 'like', trim($this->keyword) . '%');
            $query->orWhere('accessories.name', 'like', trim($this->keyword) . '%');
            $query->orWhere('orders.bill_number', 'like', trim($this->keyword) . '%');
        }
        if ($searchName) {
            $query->where('accessories.name', 'like', $searchName . '%');
        }

        if ($searchCode) {
            $query->where('accessories.code', 'like', $searchCode . '%');
        }

        if ($searchSupplierName) {
            $query->where('suppliers.name', $searchSupplierName);
        }

        if (!empty($this->searchFromDate)) {
            $fromdate = date('Y-m-d', strtotime($this->searchFromDate . ' 00:00:00'));
            $query->whereDate('order_details.buy_date', '>=', $this->searchFromDate);
        }
        if (!empty($this->searchToDate)) {
            $todate = date('Y-m-d', strtotime($this->searchToDate . ' 23:59:59'));
            $query->whereDate('order_details.buy_date', '<=', $this->searchToDate);
        }
        $query->join('accessories', 'order_details.product_id', '=', 'accessories.id')
            ->join('orders', function ($join) {
                $join->on('order_details.order_id', '=', 'orders.id');
                $join->whereNull('orders.deleted_at');
            })
            ->leftjoin('suppliers', 'orders.supplier_id', '=', 'suppliers.id');
        $query->select(
            'orders.id as order_id',
            'orders.bill_number as bill_number',
            'order_details.buy_date as receipt_date',
            //'order_details.code as code', 'order_details.listed_price',
            //'order_details.name as acname', 'order_details.quantity as quantity',
            //'order_details.actual_price as price', 'order_details.buy_date as buy_date',
            'suppliers.name as spname',
            'order_details.order_number as order_number',
            DB::raw('sum(order_details.quantity) as receipt_qty'),
            DB::raw('sum(order_details.quantity * order_details.actual_price) as amount')
        );

        $query->groupBy(
            'orders.id',
            'orders.bill_number',
            'order_details.buy_date',
            'suppliers.name',
            'order_details.order_number'
        );

        return $query;
    }

    public function querySearch($query)
    {
        $searchName = trim($this->searchName);
        $searchCode = trim($this->searchCode);
        $searchSupplierName = trim($this->searchSupplierName);
        $query->where('order_details.status', '=', '1')
            ->Where('order_details.category', '=', '1')
            ->Where('order_details.type', '=', '3')
            ->orderBy('order_details.order_id', 'DESC');

        if (isset($this->keyword) && trim($this->keyword) != '') {
            # code...
            $query->orWhere('accessories.code', 'like', trim($this->keyword) . '%');
            $query->orWhere('accessories.name', 'like', trim($this->keyword) . '%');
            $query->orWhere('orders.bill_number', 'like', trim($this->keyword) . '%');
        }
        if ($searchName) {
            $query->where('accessories.name', 'like', $searchName . '%');
        }

        if ($searchCode) {
            $query->where('accessories.code', 'like', $searchCode . '%');
        }

        if ($searchSupplierName) {
            $query->where('suppliers.name', $searchSupplierName);
        }

        if (!empty($this->searchFromDate)) {
            $fromdate = date('Y-m-d', strtotime($this->searchFromDate . ' 00:00:00'));
            $query->whereDate('order_details.buy_date', '>=', $this->searchFromDate);
        }
        if (!empty($this->searchToDate)) {
            $todate = date('Y-m-d', strtotime($this->searchToDate . ' 23:59:59'));
            $query->whereDate('order_details.buy_date', '<=', $this->searchToDate);
        }

        if (isset($this->selectedorderid) && $this->selectedorderid > 0) {
            # code...
            $query->where('orders.id', '=', $this->selectedorderid);
        }
        $query->join('accessories', 'order_details.product_id', '=', 'accessories.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->leftjoin('suppliers', 'orders.supplier_id', '=', 'suppliers.id');
        $query->select(
            'order_details.id as id',
            'orders.id as order_id',
            'orders.bill_number as bill_number',
            'order_details.code as code',
            'order_details.listed_price',
            'order_details.name as acname',
            'order_details.quantity as quantity',
            'order_details.actual_price as price',
            'order_details.buy_date as buy_date',
            'suppliers.name as spname',
            'order_details.order_number as order_number',
            DB::raw('order_details.quantity * order_details.actual_price as amount')
        );

        return $query;
    }
    public function deleteId($id)
    {
        $this->deleteId = $id;
    }
    public function updatedSearchName()
    {
        $this->resetPage();
    }
    public function resetSearch()
    {
        $this->searchName = "";
        $this->searchCode = "";
        $this->searchSupplierName = "";
        $this->searchFromDate = "";
        $this->searchToDate = "";
        $this->emit('resetDateKendo');
    }
    public function delete()
    {
        OrderDetail::find($this->deleteId)->delete();
        $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Xóa phụ tùng thành công']);
    }

    public function export()
    {
        $query = OrderDetail::query();
        $query = $this->querySearch($query);
        $data = $query->get();
        if ($data->count() == 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'warning', 'message' => 'Ko có bản ghi nào!']);
            $this->emit('close-modal-delete');
        } else {
            $this->emit('close-modal-delete');
            return Excel::download(new DsPhutungNhapExport($data), 'dsdonhang_' . date('Y-m-d-His') . '.xlsx');
        }
    }

    public function deleteOrder()
    {
        DB::beginTransaction();
        try {
            $order = Order::find($this->deleteOrderId);

            foreach($order->details as $item) {
                $accessory = Accessory::where('code', $item->code)
                                        ->where('warehouse_id', $item->warehouse_id)
                                        ->where('position_in_warehouse_id', $item->position_in_warehouse_id)
                                        ->firstOrFail();
                if ($accessory) {
                    // Check nếu là đơn nhập thì xóa đi, đơn bán thì cập nhật thêm số lượng trong kho 1,2 là bán, 3 là nhập
                    if($item->type > 2) {
                        if($accessory->quantity < $item->quantity) {
                            $this->emit('close-modal-delete');
                            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => 'Không thể xóa đơn hàng']);
                        } else {
                            $accessory->quantity -=  $item->quantity;
                        }
                    }

                    if($item->type <= 2) {
                        $accessory->quantity += $item->quantity;
                    }
                    $accessory->save();
                }                  
                  //delete item detail
                  $item->delete(); 
            }
            $order->delete();

            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'message' => 'Xóa thành công']);
            DB::commit();
        } catch(Exception $ex) {

            DB::rollBack();
        }

    }
}
