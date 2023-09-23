<?php

namespace App\Http\Livewire\Ketoan\baocao;

use App\Http\Livewire\Base\BaseLive;
use App\Http\Livewire\Traits\WithPagination;
use Livewire\Component;
use App\Models\Customer;
use App\Models\User;
use App\Models\Order;
use App\Exports\ThuListExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Enum\EOrder;
use Carbon\Carbon;

class TongHopThuList extends BaseLive
{

    public $sumtotalbegin = 0;
    public $sumtotalbuy = 0;
    public $sumtotalpaid = 0;
    public $sumtotalremain = 0;

    // use WithPagination;
    public $customerSelectedId;
    public $listSelected = [];
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
    public $showId;
    public $key_name = 'id';
    public $type = 1; // Còn nợ
    protected $listeners = ['setfromDate1', 'settoDate1'];
    public function mount()
    {
        $this->fromDate =  Carbon::create(Carbon::now()->year, Carbon::now()->month, 1)->format('Y-m-d');
        // $this->fromDate =  Carbon::now()->format('Y-m-01');
        $this->toDate = Carbon::now()->format('Y-m-d');
        $this->fromDateAfter = $this->fromDate . $this->timeEndDay;
        $this->toDateAfter = $this->toDate . $this->timeEndDay;
        $this->fromDateBefore = $this->fromDate . $this->timeStartDay;
        $this->toDateBefore = $this->toDate . $this->timeStartDay;
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
        // order::query()->update([
        //     'order_type'=>1
        // ]);
        if ($this->reset) {
            $this->reset = null;
            // $this->mount();
            $this->customerName = null;
            $this->customerAddress = null;
            $this->customerCode = null;
            $this->customerPhone = null;
            $this->customerID = null;
            $this->key_name = 'id';
            $this->sortingName = 'desc';
        }
        $sql = 'SELECT 0';
        // Lấy tổng tiền của các hóa đơn chưa thanh toán và đã thanh toán trong kì
        $userMoney2 = Customer::leftJoin('orders', function ($leftJoin) {
            $leftJoin->on('orders.customer_id', '=', 'customers.id')
                ->where('orders.order_type', EOrder::ORDER_TYPE_SELL)
                ->where('orders.category', '<>', EOrder::OTHER)
                ->where('orders.isvirtual', false)
                ->where('orders.created_at', '>=', $this->fromDateBefore)
                ->where('orders.created_at', '<=', $this->toDateAfter)
                ->whereNull('orders.deleted_at')
                ->where(function ($query) {
                    $query->where('orders.status', EOrder::STATUS_PAID);
                    $query->orWhere('orders.status', EOrder::STATUS_UNPAID);
                });
        })
            ->groupBy('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->selectSub($sql, 'total_money1') // Dư nợ đầu kì
            ->addSelect(DB::raw('SUM(total_money) AS total_money2')) // Giá trị bán
            ->selectSub($sql, 'total_money3') // Giá trị thanh toán
            ->selectSub($sql, 'total_money4'); // Dư nợ phải thu
        $userMoney2 = $this->getQuerySearch($userMoney2);
        //dd($userMoney2->get());
        // Lấy tổng tiền mà khách hàng đã thanh toán trong kì
        $userMoney3 = Customer::leftJoin('receipts', function ($leftJoin) {
            $leftJoin->on('receipts.customer_id', '=', 'customers.id')
                ->where('receipts.receipt_date', '>=', $this->fromDateBefore)
                ->where('receipts.receipt_date', '<=', $this->toDateAfter);
        })
            ->groupBy('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->selectSub($sql, 'total_money1') // Dư nợ đầu kì
            ->selectSub($sql, 'total_money2') // Giá trị bán
            ->addSelect(DB::raw('SUM(money) AS total_money3')) // Giá trị thanh toán
            ->selectSub($sql, 'total_money4'); // Dư nợ phải thu
        $userMoney3 = $this->getQuerySearch($userMoney3);
        // dd($userMoney3->get());
        // Tổng tiền đã thanh toán đầu kì
        $userMoney1a = Customer::leftJoin('receipts', function ($leftJoin) {
            $leftJoin->on('receipts.customer_id', '=', 'customers.id')
                ->where('receipts.receipt_date', '<', $this->fromDateBefore);
        })
            ->groupBy('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address', DB::raw('SUM(money) AS money1a'))
            ->selectSub($sql, 'total_money1b');
        $userMoney1a = $this->getQuerySearch($userMoney1a);

        // Tổng tiền hóa đơn trước kì
        $userMoney1b = Customer::leftJoin('orders', function ($leftJoin) {
            $leftJoin->on('orders.customer_id', '=', 'customers.id')
                ->where('orders.order_type', EOrder::ORDER_TYPE_SELL)
                ->where('orders.category', '<>', EOrder::OTHER)
                ->where('orders.isvirtual', false)
                ->whereNull('orders.deleted_at')
                ->where('orders.created_at', '<', $this->fromDateBefore)
                ->where(function ($query) {
                    $query->where('orders.status', EOrder::STATUS_PAID);
                    $query->orWhere('orders.status', EOrder::STATUS_UNPAID);
                });
        })
            ->groupBy('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->selectSub($sql, 'total_money1a')
            ->addSelect(DB::raw('SUM(total_money) AS total_money1b'))
            ->unionAll($userMoney1a);
        $userMoney1b = $this->getQuerySearch($userMoney1b);

        // Dư nợ đầu kì = Tổng tiền hóa đơn đầu kì($userMoney1b) - Tổng tiền đã thanh toán đầu kì($userMoney1a)
        $userMoney1 = DB::table(DB::raw("({$userMoney1b->toSql()}) as sub"))
            ->mergeBindings($userMoney1b->getQuery())
            ->groupBy('id', 'code', 'name', 'phone', 'address')
            ->select('id', 'code', 'name', 'phone', 'address', DB::raw('SUM(total_money1b) - SUM(total_money1a) AS total_money1'))
            ->selectSub($sql, 'total_money2')
            ->selectSub($sql, 'total_money3')
            ->selectSub($sql, 'total_money4');
        // dd($userMoney1->get());

        // Tổng tiền đã trả trước ngày endDate
        $userMoney4a = Customer::leftJoin('receipts', function ($leftJoin) {
            $leftJoin->on('receipts.customer_id', '=', 'customers.id')
                ->where('receipts.receipt_date', '<', $this->toDateAfter);
        })
            ->groupBy('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address', DB::raw('SUM(money) AS total_money4a'))
            ->selectSub($sql, 'total_money4b');
        $userMoney4a = $this->getQuerySearch($userMoney4a);

        // Tổng tiền hóa đơn trước ngày endDate
        $userMoney4b = Customer::leftJoin('orders', function ($leftJoin) {
            $leftJoin->on('orders.customer_id', '=', 'customers.id')
                ->where('orders.order_type', EOrder::ORDER_TYPE_SELL)
                ->where('orders.category', '<>', EOrder::OTHER)
                ->where('orders.isvirtual', false)
                ->whereNull('orders.deleted_at')
                ->where('orders.created_at', '<', $this->toDateAfter)
                ->where(function ($query) {
                    $query->where('orders.status', EOrder::STATUS_PAID);
                    $query->orWhere('orders.status', EOrder::STATUS_UNPAID);
                });
        })
            ->groupBy('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->select('customers.id', 'customers.code', 'customers.name', 'customers.phone', 'customers.address')
            ->selectSub($sql, 'total_money4a')
            ->addSelect(DB::raw('SUM(total_money) AS total_money4b'))
            ->unionAll($userMoney4a);
        $userMoney4b = $this->getQuerySearch($userMoney4b);

        // Dư nợ phải thu = Tổng tiền hóa đơn trước ngày endDate($userMoney4b) - Tổng tiền đã thanh toán trước ngày ednDate($userMoney4a)

        $userMoney4 = DB::table(DB::raw("({$userMoney4b->toSql()}) as sub4"))
            ->mergeBindings($userMoney4b->getQuery())
            ->groupBy('id', 'code', 'name', 'phone', 'address')
            ->select('id', 'code', 'name', 'phone', 'address')
            ->selectSub($sql, 'total_money1')  // Dư nợ đầu kì
            ->selectSub($sql, 'total_money2') // Giá trị bán
            ->selectSub($sql, 'total_money3') // Giá trị thanh toán
            ->addSelect(DB::raw('(SUM(total_money4b) - SUM(total_money4a)) AS total_money4')); // Dư nợ phải thu

        // dd($userMoney4->get());
        // Hợp 4 phần tính toán vào làm một
        $userMoney2->unionAll($userMoney1)
            ->unionAll($userMoney3)
            ->unionAll($userMoney4);

        // dd($userMoney2->get());
        // Lấy 4 phần tính toán sau khi hợp
        $userOrders = DB::table(DB::raw("({$userMoney2->toSql()}) as sub5"))
            ->mergeBindings($userMoney2->getQuery())
            ->groupBy('id', 'code', 'name', 'phone', 'address')
            ->select(
                'id',
                'code',
                'name',
                'phone',
                'address',
                DB::raw('SUM(total_money1) AS total_money1'), // Dư nợ đầu kì -> final
                DB::raw('SUM(total_money2) AS total_money2'), // Giá trị bán -> final
                DB::raw('SUM(total_money3) AS total_money3'), // Giá trị thanh toán -> final
                DB::raw('SUM(total_money4) AS total_money4') // Dư nợ phải thu -> final
            );
        if ($this->type == 1) {
            $userOrders = $userOrders->havingRaw('SUM(total_money4) > 0');
        }
        if ($this->type == 2) {
            $userOrders = $userOrders->havingRaw('SUM(total_money4) = 0');
        }


        $userOrders = $userOrders->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        $this->updateUI();
        return view('livewire.ketoan.baocao.tong-hop-thu', ['data' => $userOrders]);
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
    public function getQuerySearch($query)
    {
        if ($this->customerID || $this->customerCode) {
            $query->where('customers.id', $this->customerID);
        }
        if ($this->customerName) {
            $query->where('customers.name', 'like', '%' . $this->customerName . '%');
        }
        if ($this->customerAddress) {
            $query->where('customers.address', 'like', '%' . $this->customerAddress . '%');
        }
        return $query;
    }

    public function updatedlistSelected()
    {
        if (count($this->listSelected) > 0) {
            $firstSelected = $this->listSelected[count($this->listSelected) - 1];
            $this->listSelected = [];
            $this->listSelected[] = $firstSelected;
            $this->customerSelectedId = $firstSelected;
        }
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
            $customer = Customer::where('code', $this->customerCode)->first();
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
            $customer = Customer::where('code', $this->customerCode)->first();
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
            $customer = Customer::where('phone', $this->customerPhone)->get()->first();
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
            $customer = Customer::where('phone', $this->customerPhone)->get()->first();
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
        // $this->emit('setDatePicker');
        $this->updateUI();
        return Excel::download(new ThuListExport($this->customerID, $this->fromDateBefore, $this->toDateAfter, $this->customerName, $this->customerAddress, $this->customerCode, $this->customerPhone, $this->fromDate, $this->toDate, $this->key_name, $this->sortingName,$this->type), 'Tổng-Hợp-Phải-Thu-' . $today . '.xlsx');
    }
}
