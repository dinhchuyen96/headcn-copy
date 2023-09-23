<?php

namespace App\Http\Livewire\Service\Other;

use App\Exports\OdersExport;
use App\Http\Livewire\Base\BaseLive;
use App\Models\Order;
use App\Models\Accessory;
use Maatwebsite\Excel\Facades\Excel;
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Models\ListService;
use App\Enum\ListServiceType;
use App\Models\Customer;
use App\Models\User;
use App\Exports\ServiceListExport;
use Illuminate\Support\Facades\DB;

class Index extends BaseLive
{
    public $perPage = 10;
    public $deleteId = '';
    public $sortingName = "desc";
    public $key_name = "created_at";
    public $customer = '';
    public $service;
    public $fixerId;
    public $search_id;
    public $search_status;
    public $search_address;
    public $search_total_price;

 
    public $listService = [];
    public $users = [];

    protected $listeners = ['setfromDate', 'settoDate'];
    public function mount()
    {
        $this->listService = ListService::select('id', 'title')->where('type', ListServiceType::IN)->get();
        $this->fromDate = $this->toDate = date('Y-m-d');
        $this->users = User::select(['id', 'name'])->get();
    }

    public function setfromDate($time)
    {
        $this->fromDate = date('Y-m-d', strtotime($time['fromDate']));
    }
    public function settoDate($time)
    {
        $this->toDate = date('Y-m-d', strtotime($time['toDate']));
    }

    public function render()
    {
        $this->dispatchBrowserEvent('setSelect2');
        $this->customer  = trim($this->customer);
        if ($this->reset) {
            $this->customer = '';
        }
        $query = Order::query()
            ->with(['customer', 'customer.provinceCustomer', 'customer.districtCustomer', 'details.motorbike', 'otherService.listService', 'fixBy'])
            ->where('category', EOrder::SERVICE_OTHER);

        if ($this->key_name) {
            $query->orderBy($this->key_name, $this->sortingName);
        }
        if ($this->customer) {
            $query->where('customer_id', $this->customer);
        }
        if ($this->fixerId) {
            $query->where('fixer', $this->fixerId);
        }
        if ($this->service) {
            $query = $query->whereHas('otherService', function ($q) {
                $q->where('list_service_id', $this->service);
            });
        }
        if ($this->fromDate) {
            $query->where('created_at', '>=', $this->fromDate . ' 00:00:00');
        }
        if ($this->toDate) {
            $query->where('created_at', '<=', $this->toDate . ' 23:59:59');
        }
        if($this->search_id) {
            $query->where('id', $this->search_id);
        }
        if($this->search_status) {
            $query->where('status', $this->search_status);
        }
        if ($this->search_address) {
            $query = $query->whereHas('customer', function ($q) {
                $q->where('address', 'LIKE', '%' . trim($this->search_address) . '%');
            });
        }
        if($this->search_total_price) {
            $query->where('total_money', '=', $this->search_total_price);
        }
        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        $totalPrice = $query->sum('total_money');

        $this->dispatchBrowserEvent('setSelect2');
        return view('livewire.service.other.index', compact('data', 'totalPrice'));
    }

    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        $order = Order::where('id', $this->deleteId)->with('details')->first();
        $orderDetail = $order->details->where('status', EOrderDetail::STATUS_SAVED)
            ->where('admin_id', auth()->id())
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->where('category', EOrder::CATE_REPAIR);
        foreach ($orderDetail as $key => $item) {
            $accessory = Accessory::where('id', $item->product_id)
                ->where('position_in_warehouse_id', $item->position_in_warehouse_id)
                ->first();
            if ($accessory) {
                $accessory->quantity += $item->quantity;
                $accessory->save();
            }
        }
        $order->delete();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Xóa dịch vụ thành công']);
    }

    public function export()
    {

        $dataForListService = ListService::leftJoin('other_services', function ($q) {
            $q->on('list_services.id', '=', 'other_services.list_service_id');
            $q->whereNull('other_services.deleted_at');
        })
            ->leftJoin('orders', function ($q) {
                $q->on('other_services.order_id', '=', 'orders.id');
                $q->whereNull('orders.deleted_at');
                if ($this->fromDate) {
                    $q->where('orders.created_at', '>=', $this->fromDate . ' 00:00:00');
                }
                if ($this->toDate) {
                    $q->where('orders.created_at', '<=', $this->toDate . ' 23:59:59');
                }
            })
            ->leftJoin('users', function ($q) {
                $q->on('users.id', '=', 'orders.fixer');
                $q->whereNull('users.deleted_at');
            })
            ->where('list_services.type', ListServiceType::IN)
            ->groupBy('list_services.id', 'list_services.title')
            ->select('list_services.id as list_services_id', 'list_services.title', DB::raw('SUM(orders.total_money) as total'));

        if ($this->service) {
            $dataForListService =  $dataForListService->where('list_services.id', $this->service);
        }

        $dataForListService = $dataForListService->get();

        $dataForListServiceAndUser = ListService::leftJoin('other_services', function ($q) {
            $q->on('list_services.id', '=', 'other_services.list_service_id');
            $q->whereNull('other_services.deleted_at');
        })
            ->leftJoin('orders', function ($q) {
                $q->on('other_services.order_id', '=', 'orders.id');
                $q->whereNull('orders.deleted_at');
                if ($this->fromDate) {
                    $q->where('orders.created_at', '>=', $this->fromDate . ' 00:00:00');
                }
                if ($this->toDate) {
                    $q->where('orders.created_at', '<=', $this->toDate . ' 23:59:59');
                }
            })
            ->leftJoin('users', function ($q) {
                $q->on('users.id', '=', 'orders.fixer');
                $q->whereNull('users.deleted_at');
            })
            ->where('list_services.type', ListServiceType::IN)
            ->groupBy('list_services.id', 'list_services.title', 'users.id', 'users.name', 'users.email')
            ->select('list_services.id as list_services_id', 'list_services.title', 'users.id as user_id', 'users.name', 'users.email', DB::raw('SUM(orders.total_money) as total'));

        if ($this->service) {
            $dataForListServiceAndUser =  $dataForListServiceAndUser->where('list_services.id', $this->service);
        }
        $dataForListServiceAndUser = $dataForListServiceAndUser->get();

        $dataForListServiceAndUserDetail = ListService::leftJoin('other_services', function ($q) {
            $q->on('list_services.id', '=', 'other_services.list_service_id');
            $q->whereNull('other_services.deleted_at');
        })
            ->leftJoin('orders', function ($q) {
                $q->on('other_services.order_id', '=', 'orders.id');
                $q->whereNull('orders.deleted_at');
                if ($this->fromDate) {
                    $q->where('orders.created_at', '>=', $this->fromDate . ' 00:00:00');
                }
                if ($this->toDate) {
                    $q->where('orders.created_at', '<=', $this->toDate . ' 23:59:59');
                }
            })
            ->leftJoin('users', function ($q) {
                $q->on('users.id', '=', 'orders.fixer');
                $q->whereNull('users.deleted_at');
            })
            ->where('list_services.type', ListServiceType::IN)
            ->select('list_services.id as list_services_id', 'list_services.title', 'users.id as user_id', 'users.name', 'users.email', 'other_services.price', 'other_services.promotion');

        if ($this->service) {
            $dataForListServiceAndUserDetail =  $dataForListServiceAndUserDetail->where('list_services.id', $this->service);
        }
        $dataForListServiceAndUserDetail = $dataForListServiceAndUserDetail->get();

        $data = [
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
            'dataForListService' => $dataForListService,
            'dataForListServiceAndUser' => $dataForListServiceAndUser,
            'dataForListServiceAndUserDetail' => $dataForListServiceAndUserDetail
        ];


        return Excel::download(new ServiceListExport($data), 'baocaodichvukhac_' . date('Y-m-d-His') . '.xlsx');
    }
}
