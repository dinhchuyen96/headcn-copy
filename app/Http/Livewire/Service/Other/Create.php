<?php

namespace App\Http\Livewire\Service\Other;

use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Base\BaseLive;

use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Enum\ListServiceType;

use App\Models\Accessory;
use App\Models\Customer;
use App\Models\PositionInWarehouse;
use App\Models\ListService;
use App\Models\OtherService;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\District;
use App\Models\Province;
use App\Models\Ward;

use DB;
use Carbon\Carbon;
use DateTime;

class Create extends BaseLive
{
    public $service_content = [];
    public $service_price = [];
    public $service_promotion = [];
    public $service_total = [];
    public $list_service = [];
    public $services = [];
    public $i = 0;
    public $disabled = true;
    public $disabled_customer = false;
    public $customer = '';
    public $fixerId;
    public $users = [];
    public $accessory_code = [];
    public $accessory_warehouse_pos = [];
    public $accessory_name = [];
    public $accessory_supplier = [];
    public $accessory_quantity = [];
    public $accessory_available_quantity = [];
    public $accessory_available_quantity_root = [];
    public $accessory_price = [];
    public $accessory_promotion = [];
    public $accessory_total = [];
    public $accessory_price_vat = [];
    public $accessory_price_actual = [];
    public $accessory_product = [];
    public $accessories = [];
    public $j = 0;

    public $accessories_list = [];
    public $accessories_select = [];
    public $positions_list = [];
    public $positions_select = [];
    public $o_service_list = [];
    public $o_service_select = [];

    public $province_id;
    public $PhoneNumber;
    public $transactionDate;
    public $CustomerName;

    public $phone;
    public $name;
    public $email;
    public $address;
    public $birthday;
    public $job;
    public $sex = 1;
    public $district_id;
    public $ward_id;
    public $technicalId;
    public $accountingDate;
    public $autoFill = false;
    public $selectCustomer = true;

    protected $listeners = ['countServicePrice', 'changeListService', 'changeAccessoryCode', 'changeWarehousePos', 'countAccessoryPrice', 'changeCustomer'];

    public function mount()
    {
        $this->accountingDate = Carbon::now()->format('Y-m-d');
        $this->users = User::select(['id', 'name'])->get();
        if (isset($_GET['customerId'])) {
            $this->customer = $_GET['customerId'];
        }
    }
    public function render()
    {
        $ward = [];
        $district = [];
        $province = Province::orderBy('name')->pluck('name', 'province_code');
        if ($this->province_id) {
            $district = District::where('province_code', $this->province_id)->orderBy('name')->pluck('name', 'district_code');
            if ($this->district_id) {
                $ward = Ward::where('district_code', $this->district_id)->orderBy('name')->pluck('name', 'ward_code');
            }
        }
        $this->dispatchBrowserEvent('setSelect2');
        $this->dispatchBrowserEvent('setDatePicker');
        return view('livewire.service.other.create', compact('province', 'ward', 'district'));
    }

    public function store()
    {
        $this->validate(
            [
                'fixerId' => 'required',
            ],
            [
                'fixerId.required' => 'Bắt buộc chọn NV sửa chữa',
            ]
        );

        if (!empty($this->services)) {
            foreach ($this->services as $service) {
                $this->validate(
                    [
                        'service_content.' . $service => 'required',
                        'service_price.' . $service => 'required|numeric|min:0|max:9999999999',
                        'service_promotion.' . $service => 'required|numeric|min:0|max:100',
                        'list_service.' . $service => 'required',
                    ],
                    [
                        'service_content.' . $service . '.required' => 'Bắt buộc nhập nội dung dịch vụ',
                        'service_price.' . $service . '.required' => 'Bắt buộc nhập tiền công',
                        'service_price.' . $service . '.min' => 'Tiền công tối thiếu là 0',
                        'service_price.' . $service . '.max' => 'Tiền công tối đa là 9999999999',
                        'service_promotion.' . $service . '.required' => 'Bắt buộc nhập khuyến mại',
                        'service_promotion.' . $service . '.min' => 'Khuyến mãi tối thiếu là 0',
                        'service_promotion.' . $service . '.max' => 'Khuyến mãi tối đa là 100',
                        'list_service.' . $service . '.required' => 'Bắt buộc chọn dịch vụ',
                    ]
                );
            }
        }

        if (!empty($this->accessories)) {
            foreach ($this->accessories as $accessory) {
                $this->validate(
                    [
                        'accessory_code.' . $accessory => 'required',
                        'accessory_warehouse_pos.' . $accessory => 'required',
                    ],
                    [
                        'accessory_code.' . $accessory . '.required' => 'Bắt buộc nhập mã phụ tùng',
                        'accessory_warehouse_pos.' . $accessory . '.required' => 'Bắt buộc nhập vị trí kho',
                    ]
                );
            }
        }

        DB::beginTransaction();

        if (empty($this->customer)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Thêm mới không thành công']);
        }

        $now = DateTime::createFromFormat('U.u', microtime(true))->modify('+ 7 hour');
        $customerInfo = Customer::updateOrCreate([
            'phone' => $this->phone,
        ], [
            'name' => $this->name,
            'code' => $this->code ?? 'CO_' . substr($now->format("ymdhisu"), 0, -3),
            'email' => $this->email,
            'job' => $this->job,
            'birthday' => $this->birthday,
            'sex' => $this->sex,
            'address' => $this->address,
            'city' => $this->province_id,
            'district' => $this->district_id,
            'ward' => $this->ward_id,
        ]);

        $order = new Order;
        $order->customer_id = $customerInfo->id;
        $order->category = EOrder::SERVICE_OTHER;
        $order->created_by = Auth::user()->id;
        $order->total_items = 1;
        $order->status = EOrder::STATUS_UNPAID;
        $order->fixer = empty($this->fixerId) ? null : $this->fixerId;
        if (!empty($this->accountingDate)) {
            $order->accounting_date = $this->accountingDate;
        }
        $order->save();

        $totalItem = 0;
        $totalPrice = 0;

        if (!empty($this->services)) {
            $serviceData = [];
            foreach ($this->services as $key => $service) {
                $serviceData[] = [
                    'list_service_id' => $this->list_service[$service],
                    'content' => $this->service_content[$service],
                    'price' => $this->service_price[$service],
                    'promotion' => isset($this->service_promotion[$service]) ? $this->service_promotion[$service] : 0,
                    'order_id' => $order->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];

                $totalItem += 1;
                $totalPrice += $this->service_price[$service];
            }

            if (!OtherService::insert($serviceData)) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Thêm mới không thành công']);
                return;
            }
        }

        if (!empty($this->accessories)) {
            $accessoryData = [];
            foreach ($this->accessories as $key => $accessory) {
                if ($this->accessory_available_quantity[$accessory] < 0) {
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Số lượng phụ tùng không đủ bán']);
                    return;
                }

                $accessoryData[] = [
                    'code' => $this->accessory_code[$accessory],
                    'quantity' => $this->accessory_quantity[$accessory],
                    'price' => $this->accessory_price[$accessory],
                    'product_id' => $this->accessory_product[$accessory],
                    'vat_price' => $this->accessory_price_vat[$accessory],
                    'actual_price' => $this->accessory_price_actual[$accessory],
                    'position_in_warehouse_id' => $this->accessory_warehouse_pos[$accessory],
                    'warehouse_id' => PositionInWarehouse::findOrFail($this->accessory_warehouse_pos[$accessory])->warehouse_id,
                    'promotion' => $this->accessory_promotion[$accessory],
                    'status' => EOrderDetail::STATUS_SAVED,
                    'admin_id' => auth()->id(),
                    'category' => EOrderDetail::CATE_REPAIR,
                    'type' => EOrderDetail::TYPE_BANLE,
                    'order_id' => $order->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];

                $totalItem += 1;
                $totalPrice += $this->accessory_price[$accessory];
            }
            if (!OrderDetail::insert($accessoryData)) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Thêm mới không thành công']);
                return;
            }

            foreach ($this->accessory_product as $key => $accessoryId) {
                $accessory = Accessory::where('id', $accessoryId)
                    ->where('position_in_warehouse_id', $this->accessory_warehouse_pos[$key])
                    ->first();

                if ($accessory) {
                    $accessory->quantity -= $this->accessory_quantity[$key];
                    $accessory->save();
                }
            }
        }

        $order->total_items = $totalItem;
        $order->total_money = $totalPrice;
        $order->save();

        DB::commit();

        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Thêm mới thành công']);
        $this->resetInputFields();
        $this->resetServiceInputFields();
        $this->resetAccessoryInputFields();
    }

    public function addService($i)
    {
        $i = $i + 1;
        $this->i = $i;
        $this->disabled = false;
        $this->service_promotion[$i] = 0;
        $this->o_service_list[$i] = ListService::select('id', 'title')->where('type', ListServiceType::IN)->get();

        array_push($this->services, $i);
    }

    public function changeListService($data)
    {
        $this->list_service[$data['index']] = $data['value'];
        $this->o_service_select[] = $data['value'];
    }

    public function removeService($i)
    {
        unset($this->services[$i]);
    }

    public function countServicePrice($index)
    {
        $price = 0;
        $promotion = 0;
        $total = 0;

        if (isset($this->service_price[$index])) {
            $price = $this->service_price[$index];
        }
        if (isset($this->service_promotion[$index])) {
            $promotion = $this->service_promotion[$index];
        }

        if (empty($price)) {
            $total = 0;
        }

        if (empty($promotion)) {
            $total = $price;
        }

        if (!empty($price) && !empty($promotion)) {
            $total = $price - (($price / 100) * $promotion);
        }

        $this->service_total[$index] = number_format($total);
    }

    public function addAccessory($j)
    {
        $j = $j + 1;
        $this->j = $j;
        $this->disabled = false;
        $this->accessory_available_quantity[$j] = 0;
        $this->accessory_promotion[$j] = 0;

        $this->accessories_list[$j] = Accessory::whereNotNull('code')
            ->whereNotIn('code', $this->accessories_select)
            ->where('quantity', '>', 0)
            ->pluck('code')
            ->unique();

        $this->positions_list[$j] = [];

        array_push($this->accessories, $j);
    }

    public function removeAccessory($j)
    {
        unset($this->accessories[$j]);
    }

    public function changeAccessoryCode($data)
    {
        $this->accessory_code[$data['index']] = $data['value'];
        $this->accessories_select[] = $data['value'];

        if (!empty($data['value'])) {
            $accessory = Accessory::where('code', $data['value'])->where('quantity', '>', 0)->first();
            $this->accessory_supplier[$data['index']] = isset($accessory->supplier) ? $accessory->supplier->code : '';
            $this->accessory_price[$data['index']] = $accessory->price;
            $this->accessory_name[$data['index']] = $accessory->name;
            $this->accessory_promotion[$data['index']] = 0;

            $positionIds = Accessory::where('code', $data['value'])
                ->where('quantity', '>', 0)
                ->select('position_in_warehouse_id')
                ->pluck('position_in_warehouse_id');

            $this->positions_list[$data['index']] = PositionInWarehouse::with(['warehouse'])
                ->whereHas('warehouse', function ($q) {
                    $q->whereNull('deleted_at');
                })
                ->whereIn('id', $positionIds)
                ->get()
                ->map(function ($item, $key) {
                    return [
                        'id' => $item->id,
                        'name' => $item->warehouse->name . " - " . $item->name
                    ];
                });
        }
    }

    public function changeWarehousePos($data)
    {
        $this->accessory_warehouse_pos[$data['index']] = $data['value'];

        if (!empty($data['value'])) {
            $record = Accessory::with(['supplier', 'positionInWarehouse', 'positionInWarehouse.warehouse'])
                ->whereHas(
                    'warehouse',
                    function ($q) {
                        $q->whereNull('deleted_at');
                    }
                )
                ->where('code', $this->accessory_code[$data['index']])
                ->where('quantity', '>', 0)
                ->where('position_in_warehouse_id', $this->accessory_warehouse_pos[$data['index']])
                ->first();

            if ($record) {
                $this->accessory_product[$data['index']] = $record->id;
                $this->accessory_available_quantity[$data['index']] = $record->quantity - 1;
                $this->accessory_available_quantity_root[$data['index']] = $record->quantity;
                $this->accessory_quantity[$data['index']] = 1;
                $this->accessory_total[$data['index']] = $record->price;
                $this->accessory_price_vat[$data['index']] = $record->price;
                $this->accessory_price_actual[$data['index']] = $record->price;
            }
        }
    }

    public function countAccessoryPrice($index)
    {
        if (!isset($this->accessory_quantity[$index]) || !isset($this->accessory_price[$index])) {
            return;
        }

        $quantity = $this->accessory_quantity[$index];
        $price = $this->accessory_price[$index];
        if (count($this->accessory_available_quantity_root) > $index) {
            $this->accessory_available_quantity[$index] = $this->accessory_available_quantity_root[$index] - $quantity;
        }


        $promotion = 0;
        $total = 0;

        $price = $price * $quantity;

        if (isset($this->accessory_promotion[$index])) {
            $promotion = $this->accessory_promotion[$index];
        }

        if (empty($promotion)) {
            $total = $price;
        }

        if (!empty($promotion)) {
            $total = $price - (($price / 100) * $promotion);
        }

        $this->accessory_total[$index] = number_format($total);
        $this->accessory_price_vat[$index] = $total;
        $this->accessory_price_actual[$index] = $total;
    }

    public function resetInputFields()
    {
        $this->customer = null;
        $this->disabled = true;
    }

    public function resetServiceInputFields()
    {
        $this->service_content = [];
        $this->service_price = [];
        $this->service_promotion = [];
        $this->service_total = [];
        $this->services = [];
        $this->i = 0;
    }

    public function resetAccessoryInputFields()
    {
        $this->accessory_code = [];
        $this->accessory_warehouse_pos = [];
        $this->accessory_name = [];
        $this->accessory_supplier = [];
        $this->accessory_quantity = [];
        $this->accessory_available_quantity = [];
        $this->accessory_price = [];
        $this->accessory_promotion = [];
        $this->accessory_total = [];
        $this->accessory_price_vat = [];
        $this->accessory_price_actual = [];
        $this->accessory_product = [];
        $this->accessories = [];
        $this->j = 0;
        $this->accessory_warehouse_pos_list = [];
        $this->positionsList = [];
        $this->fixerId = '';
    }

    public function updatedPhone()
    {
        $customer = Customer::where('phone', $this->phone)->get()->first();
        if ($customer) {
            $this->autoFill = true;
            $this->email = $customer->email;
            $this->code = $customer->code;
            $this->name = $customer->name;
            $this->customer = $customer->id;
            $this->birthday = $customer->birthday;
            $this->job = $customer->job;
            $this->sex = $customer->sex;
            $this->address = $customer->address;
            $this->district_id = $customer->district;
            $this->province_id = $customer->city;
            $this->ward_id = $customer->ward;

            $order = Order::whereNotNull('accounting_date')
                ->orderBy('accounting_date', 'DESC')
                ->first();

            if ($order) {
                $this->accountingDate = $order->accounting_date;
            }
        } else {
            $this->selectCustomer = false;
            $this->autoFill = false;
            $this->name = null;
            $this->email = null;
            $this->code = null;
            $this->address = null;
            $this->district_id = null;
            $this->province_id = null;
            $this->ward_id = null;
            $this->emit('resetAddress');
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Số điện thoại này chưa có thông tin khách hàng trong hệ thống']);
        }
    }

    public function changeCustomer($data)
    {
        $customer = Customer::where('id', $data['customer_id'])->first();
        if ($customer) {
            $this->autoFill = true;
            $this->email = $customer->email;
            $this->phone = $customer->phone;
            $this->code = $customer->code;
            $this->name = $customer->name;
            $this->customer = $customer->id;
            $this->birthday = $customer->birthday;
            $this->job = $customer->job;
            $this->sex = $customer->sex;
            $this->address = $customer->address;
            $this->district_id = $customer->district;
            $this->province_id = $customer->city;
            $this->ward_id = $customer->ward;

            $order = Order::whereNotNull('accounting_date')
                ->orderBy('accounting_date', 'DESC')
                ->first();
        }
    }
}
