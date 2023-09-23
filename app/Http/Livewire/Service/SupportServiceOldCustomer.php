<?php

namespace App\Http\Livewire\Service;

use App\Http\Livewire\Base\BaseLive;
use Illuminate\Support\Facades\DB;
use App\Models\Motorbike;
use App\Models\Warehouse;
use App\Models\Customer;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MotorbikeListExport;
use App\Enum\EOrderDetail;
use App\Http\Controllers\API\SmsGatewayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Enum\EMotorbike;
use App\Models\Order;
use App\Enum\EOrder;
use Illuminate\Database\Eloquent\Builder;

class SupportServiceOldCustomer extends BaseLive
{
    // danh sách customer
    public $listCustomer;
    public $listSelectCustomer;
    // item search
    public $searchInfoCustomer;
    public $customerType = 1;
    public $key_name = "name";
    public $sortingName = "asc";


    public function mount()
    {
        $this->listSelectCustomer = Customer::select('id', 'name', 'phone')->get()->toArray();
    }
    public function render()
    {
        // <option value="1">Tất cả</option>
        // <option value="2">Quá 6 tháng chưa dùng dịch vụ</option>
        // <option value="3">KTĐK lần 1</option>
        // <option value="4">KTĐK lần 2</option>
        // <option value="5">KTĐK lần 3</option>
        // <option value="6">KTĐK lần 4</option>
        // <option value="7">KTĐK lần 5</option>
        // <option value="8">KTĐK lần 6</option>
        // <option value="9">Khách hàng sau sửa chữa</option>
        // <option value="10">Khách hàng mua xe</option>
        // <option value="11">Khách hàng yếu kém có phụ tùng cần thay thế</option>
        // <option value="12">Khách hàng hết bảo hành sau 3 năm</option>

        $listCustomer = Customer::query();

        $listCustomerBoughtMotorbike = Customer::withCount(['motorbikes' => function (Builder $query) {
            $query->whereNotNull("motorbikes.sell_date");
            $query->where("motorbikes.status", EMotorbike::SOLD);
        }])->get();
        // Danh sách khách hàng mua xe
        $listCustomerBoughtMotorbike = $listCustomerBoughtMotorbike->where('motorbikes_count', '>', 0)->pluck('id')->unique()->toArray();
        if ($this->customerType != 1) {
            $listCustomer = $listCustomer->whereIn('id', $listCustomerBoughtMotorbike);
        }

        // Danh sách hàng có dịch vụ trong 6 tháng
        if ($this->customerType == 2) {
            $before6Month =  Carbon::today()->addMonths(-6)->format('Y-m-d');
            $listCustomerHaveOrderRepair = Order::join('customers', 'customers.id', '=', 'orders.customer_id')
                ->where(function ($q) {
                    $q->orWhere('orders.category', EOrder::CATE_MAINTAIN);
                    $q->orWhere('orders.category', EOrder::CATE_REPAIR);
                })
                ->where('orders.created_at', '>=', $before6Month)
                ->select("orders.customer_id")->get()->pluck('customer_id')->unique()->toArray();
            // Quá 6 tháng chưa dùng dịch vụ
            $listCustomer = $listCustomer->whereNotIn('id', $listCustomerHaveOrderRepair);
        }
        // Danh sách kiểm tra định kì theo lần


        switch ($this->customerType) {
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 12:
                $listCustomerBoughtMotorbikeTimes = Customer::withCount(['motorbikes' => function (Builder $query) {
                    switch ($this->customerType) {
                        case 3:
                            $query->where("motorbikes.sell_date", '<=', Carbon::now()->addDays(-7));
                            $query->where("motorbikes.sell_date", '>=', Carbon::now()->addMonths(-1));
                            break;
                        case 4:
                            $query->where("motorbikes.sell_date", '<=', Carbon::now()->addMonths(-1)->addDays(1));
                            $query->where("motorbikes.sell_date", '>=', Carbon::now()->addMonths(-6));
                            break;
                        case 5:
                            $query->where("motorbikes.sell_date", '<=', Carbon::now()->addMonths(-6)->addDays(1));
                            $query->where("motorbikes.sell_date", '>=', Carbon::now()->addMonths(-12));
                            break;
                        case 6:
                            $query->where("motorbikes.sell_date", '<=', Carbon::now()->addMonths(-12)->addDays(1));
                            $query->where("motorbikes.sell_date", '>=', Carbon::now()->addMonths(-18));
                            break;
                        case 7:
                            $query->where("motorbikes.sell_date", '<=', Carbon::now()->addMonths(-18)->addDays(1));
                            $query->where("motorbikes.sell_date", '>=', Carbon::now()->addMonths(-27));
                            break;
                        case 8:
                            $query->where("motorbikes.sell_date", '<=', Carbon::now()->addMonths(-27)->addDays(1));
                            $query->where("motorbikes.sell_date", '>=', Carbon::now()->addMonths(-36));
                            break;
                        case 12:
                            $query->where("motorbikes.sell_date", '<=', Carbon::now()->addMonths(-36)->addDays(1));
                            break;
                        default:
                            break;
                    }
                    $query->where("motorbikes.status", EMotorbike::SOLD);
                }])->get();
                $listCustomerBoughtMotorbikeTimes = $listCustomerBoughtMotorbikeTimes->where('motorbikes_count', '>', 0)->pluck('id')->unique()->toArray();
                $listCustomer = $listCustomer->whereIn('id', $listCustomerBoughtMotorbikeTimes);
                break;
            default:
                break;
        }
        // Khách hàng sau sửa chữa
        if ($this->customerType == 9) {
            $listCustomerHaveOrderRepair = Order::join('customers', 'customers.id', '=', 'orders.customer_id')
                ->where(function ($q) {
                    $q->orWhere('orders.category', EOrder::CATE_MAINTAIN);
                    $q->orWhere('orders.category', EOrder::CATE_REPAIR);
                })
                ->select("orders.customer_id")->get()->pluck('customer_id')->unique()->toArray();
            $listCustomer = $listCustomer->whereIn('id', $listCustomerHaveOrderRepair);
        }
        // Khách hàng có phụ tùng yếu kém cần thay thế

        if ($this->customerType == 11) {
            $listCustomerBoughtMotorbikeTimesHaveWeakAccessory = Order::withCount(['details' => function (Builder $query) {
                $query->where('order_details.is_atrophy', EOrderDetail::ATROPHY_ACCESSORY);
            }])->get();
            $listCustomerBoughtMotorbikeTimesHaveWeakAccessory = $listCustomerBoughtMotorbikeTimesHaveWeakAccessory->where('details_count', '>', 0)->pluck('customer_id')->unique()->toArray();
            $listCustomer = $listCustomer->whereIn('id', $listCustomerBoughtMotorbikeTimesHaveWeakAccessory);
        }

        if ($this->searchInfoCustomer) {
            $listCustomer->where(function ($q) {
                $q->where('phone', $this->searchInfoCustomer);
            });
        }

        $data = $listCustomer->with('motorbikes')->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        $this->dispatchBrowserEvent('setSelect2');
        $this->dispatchBrowserEvent('select2Customer');
        return view('livewire.service.support-service-old-customer', ['data' => $data]);
    }
}
