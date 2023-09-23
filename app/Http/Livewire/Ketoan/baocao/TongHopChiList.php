<?php

namespace App\Http\Livewire\Ketoan\baocao;

use App\Http\Livewire\Base\BaseLive;
use App\Http\Livewire\Traits\WithPagination;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Order;
use App\Exports\ChiListExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TongHopChiList extends BaseLive
{
    // use WithPagination;
    public $customerName;
    public $customerAddress;
    public $customerCode;
    public $customerPhone;
    public $customerID;
    public $fromDate;
    public $toDate;
    public $fromDateAfter;
    public $toDateAfter;
    public $fromDateBefore;
    public $toDateBefore;
    public $timeStartDay = ' 00:00:00';
    public $timeEndDay = ' 23:59:59';
    protected $listeners = ['setfromDate1', 'settoDate1'];
    public $showId;
    // public $canShow;
    public function mount()
    {
        $this->fromDate =  Carbon::create( Carbon::now()->year, Carbon::now()->month,1)->format('Y-m-d');
        // $this->fromDate =  Carbon::now()->format('Y-m-01');
        $this->toDate = Carbon::now()->format('Y-m-d');
        $this->fromDateAfter = $this->fromDate . $this->timeEndDay;
        $this->toDateAfter = $this->toDate . $this->timeEndDay;
        $this->fromDateBefore = $this->fromDate . $this->timeStartDay;
        $this->toDateBefore = $this->toDate . $this->timeStartDay;
        $this->key_name = 'id';
        $this->sortingName = 'desc';
    }
    public function setfromDate1($time)
    {
        $this->fromDate = date('Y-m-d', strtotime($time['fromDate1']));
        $this->updatedFromDate();
    }
    public function settoDate1($time)
    {
        $this->toDate = date('Y-m-d', strtotime($time['toDate1']));
        $this->updatedToDate();
    }
    public function render()
    {
        $this->emit('setDatePicker');
        // Order::query()->update([
        //     'order_type' => 2,
        // ]);
        if ($this->reset) {
            $this->reset = null;
            $this->mount();
            $this->customerName = null;
            $this->customerAddress = null;
            $this->customerCode = null;
            $this->customerPhone = null;
            $this->customerID = null;
        }
        $sql = 'SELECT 0';
        $userMoney2 = Supplier::leftJoin('orders', function ($leftJoin) {
            $leftJoin->on('orders.supplier_id', '=', 'suppliers.id')
                ->where('orders.order_type', 2)
                ->where('orders.created_at', '>=', $this->fromDateBefore)
                ->where('orders.created_at', '<=', $this->toDateAfter)
                ->whereNull('orders.deleted_at')
                ->where(function ($query) {
                    $query->where('orders.status', '=', 1);
                    $query->orWhere('orders.status', '=', 2);
                });
        })
            ->groupBy('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->selectSub($sql, 'total_money1')
            ->addSelect(DB::raw('SUM(total_money) AS total_money2'))
            ->selectSub($sql, 'total_money3')
            ->selectSub($sql, 'total_money4');
        $userMoney2 = $this->getQuerySearch($userMoney2);
        // dd($userMoney2->get());
        $userMoney3 = Supplier::leftJoin('payments', function ($leftJoin) {
            $leftJoin->on('payments.supplier_id', '=', 'suppliers.id')
                ->where('payments.payment_date', '>=', $this->fromDateBefore)
                ->where('payments.payment_date', '<=', $this->toDateAfter);
        })
            ->groupBy('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->selectSub($sql, 'total_money1')
            ->selectSub($sql, 'total_money2')
            ->addSelect(DB::raw('SUM(money) AS total_money3'))
            ->selectSub($sql, 'total_money4');
        $userMoney3 = $this->getQuerySearch($userMoney3);
        // dd($userMoney3->get());
        $userMoney1a = Supplier::leftJoin('payments', function ($leftJoin) {
            $leftJoin->on('payments.supplier_id', '=', 'suppliers.id')
                ->where('payments.payment_date', '<', $this->fromDateBefore);
        })
            ->groupBy('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address', DB::raw('SUM(money) AS money1a'))
            ->selectSub($sql, 'total_money1b');
        $userMoney1a = $this->getQuerySearch($userMoney1a);
        $userMoney1b = Supplier::leftJoin('orders', function ($leftJoin) {
            $leftJoin->on('orders.supplier_id', '=', 'suppliers.id')
                ->where('orders.order_type', 2)
                ->where('orders.created_at', '<', $this->fromDateBefore)
                ->whereNull('orders.deleted_at')
                ->where(function ($query) {
                    $query->where('orders.status', '=', 1);
                    $query->orWhere('orders.status', '=', 2);
                });
        })
            ->groupBy('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->selectSub($sql, 'total_money1a')
            ->addSelect(DB::raw('SUM(total_money) AS total_money1b'))
            ->unionAll($userMoney1a);
        $userMoney1b = $this->getQuerySearch($userMoney1b);
        $userMoney1 = DB::table(DB::raw("({$userMoney1b->toSql()}) as sub"))
            ->mergeBindings($userMoney1b->getQuery())
            ->groupBy('id', 'code', 'name', 'phone', 'address')
            ->select('id', 'code', 'name', 'phone', 'address', DB::raw('SUM(total_money1b) - SUM(total_money1a) AS total_money1'))
            ->selectSub($sql, 'total_money2')
            ->selectSub($sql, 'total_money3')
            ->selectSub($sql, 'total_money4');
        // dd($userMoney1->get());
        $userMoney4a = Supplier::leftJoin('payments', function ($leftJoin) {
            $leftJoin->on('payments.supplier_id', '=', 'suppliers.id')
                ->where('payments.payment_date', '<', $this->toDateAfter);
        })
            ->groupBy('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address', DB::raw('SUM(money) AS money4a'))
            ->selectSub($sql, 'total_money4b');
        $userMoney4a = $this->getQuerySearch($userMoney4a);
        $userMoney4b = Supplier::leftJoin('orders', function ($leftJoin) {
            $leftJoin->on('orders.supplier_id', '=', 'suppliers.id')
                ->where('orders.order_type', 2)
                ->whereNull('orders.deleted_at')
                ->where('orders.created_at', '<', $this->toDateAfter)
                ->where(function ($query) {
                    $query->where('orders.status', '=', 1);
                    $query->orWhere('orders.status', '=', 2);
                });
        })
            ->groupBy('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->select('suppliers.id', 'suppliers.code', 'suppliers.name', 'suppliers.phone', 'suppliers.address')
            ->selectSub($sql, 'total_money4a')
            ->addSelect(DB::raw('SUM(total_money) AS total_money4b'))
            ->unionAll($userMoney4a);
        $userMoney4b = $this->getQuerySearch($userMoney4b);
        $userMoney4 = DB::table(DB::raw("({$userMoney4b->toSql()}) as sub4"))
            ->mergeBindings($userMoney4b->getQuery())
            ->groupBy('id', 'code', 'name', 'phone', 'address')
            ->select('id', 'code', 'name', 'phone', 'address')
            ->selectSub($sql, 'total_money1')
            ->selectSub($sql, 'total_money2')
            ->selectSub($sql, 'total_money3')
            ->addSelect(DB::raw('(SUM(total_money4b) - SUM(total_money4a)) AS total_money4'));
        // dd($userMoney4->get());

        $userMoney2->unionAll($userMoney1)
            ->unionAll($userMoney3)
            ->unionAll($userMoney4);
        // dd($userMoney2->get());
        $userOrders = DB::table(DB::raw("({$userMoney2->toSql()}) as sub5"))
            ->mergeBindings($userMoney2->getQuery())
            ->groupBy('id', 'code', 'name', 'phone', 'address')
            ->select('id', 'code', 'name', 'phone', 'address', DB::raw('SUM(total_money1) AS total_money1'), DB::raw('SUM(total_money2) AS total_money2'), DB::raw('SUM(total_money3) AS total_money3'), DB::raw('SUM(total_money4) AS total_money4'));
        $userOrders = $userOrders->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        return view('livewire.ketoan.baocao.tong-hop-chi', ['data' => $userOrders]);
    }
    public function getQuerySearch($query)
    {
        if ($this->customerID) {
            $query->where('suppliers.id', $this->customerID);
        }
        if ($this->customerName) {
            $query->where('suppliers.name', 'like', '%' . $this->customerName . '%');
        }
        if ($this->customerAddress) {
            $query->where('suppliers.address', 'like', '%' . $this->customerAddress . '%');
        }
        return $query;
    }
    public function updatedFromDate()
    {
        $this->fromDateAfter = $this->fromDate . $this->timeEndDay;
        $this->fromDateBefore = $this->fromDate . $this->timeStartDay;
        $this->resetPage();
    }

    public function updatedTodate()
    {
        $this->toDateAfter = $this->toDate . $this->timeEndDay;
        $this->toDateBefore = $this->toDate . $this->timeStartDay;
        // $this->fromDate = $this->toDate;
        // $this->fromDateAfter = $this->fromDate.$this->timeEndDay;
        // $this->fromDateBefore = $this->fromDate.$this->timeStartDay;
        $this->resetPage();
    }

    public function updatedCustomerAddress()
    {
        $this->customerAddress = trim($this->customerAddress);
        $this->resetPage();
    }
    public function updatedCustomerName()
    {
        $this->customerName = trim($this->customerName);
        $this->resetPage();
    }

    public function updatedCustomerCode()
    {
        if ($this->customerID) {
            $this->customerCode = trim($this->customerCode);
            $customer = Supplier::where('code', $this->customerCode)->get()->first();
            if ($customer) {
                $this->customerName = $customer->name;
                $this->customerAddress = $customer->address;
                $this->customerCode = $customer->code;
                $this->customerPhone = $customer->phone;
                $this->customerID = $customer->id;
            } else {
                $this->customerID = null;
                $this->customerPhone = null;
                $this->customerAddress = null;
                $this->customerName = null;
            }
        } else {
            $this->customerCode = trim($this->customerCode);
            $customer = Supplier::where('code', $this->customerCode)->get()->first();
            if ($customer) {
                $this->customerName = $customer->name;
                $this->customerAddress = $customer->address;
                $this->customerCode = $customer->code;
                $this->customerPhone = $customer->phone;
                $this->customerID = $customer->id;
            } else {
                $this->customerID = null;
            }
        }
        $this->resetPage();
    }
    public function updatedCustomerPhone()
    {
        if ($this->customerID) {
            $this->customerCode = trim($this->customerCode);
            $customer = Supplier::where('phone', $this->customerPhone)->get()->first();
            if ($customer) {
                $this->customerName = $customer->name;
                $this->customerAddress = $customer->address;
                $this->customerCode = $customer->code;
                $this->customerPhone = $customer->phone;
                $this->customerID = $customer->id;
            } else {
                $this->customerID = null;
                $this->customerCode = null;
                $this->customerAddress = null;
                $this->customerName = null;
            }
        } else {
            $this->customerCode = trim($this->customerCode);
            $customer = Supplier::where('phone', $this->customerPhone)->get()->first();
            if ($customer) {
                $this->customerName = $customer->name;
                $this->customerAddress = $customer->address;
                $this->customerCode = $customer->code;
                $this->customerPhone = $customer->phone;
                $this->customerID = $customer->id;
            } else {
                $this->customerID = null;
            }
        }
        $this->resetPage();
    }
    public function setShowID($id)
    {
        $this->showId = $id;
        $this->emit('setIDCustomer', $id, $this->fromDateBefore, $this->toDateAfter);
    }

    public function export()
    {
        $today = date("d_m_Y");
        $this->emit('setDatePicker');
        return Excel::download(new ChiListExport($this->customerID, $this->fromDateBefore, $this->toDateAfter, $this->customerName, $this->customerAddress, $this->customerCode, $this->customerPhone, $this->fromDate, $this->toDate, $this->key_name, $this->sortingName), 'Tổng-Hợp-Phải-Trả-' . $today . '.xlsx');
    }
}
