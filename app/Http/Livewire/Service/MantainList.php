<?php

namespace App\Http\Livewire\Service;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Log;

use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Enum\KMCheckStep;
use App\Enum\ERepairTask;
use App\Enum\EUserPosition;
use App\Enum\EMotorbike;

use App\Models\Accessory;
use App\Models\District;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Periodic;
use App\Models\Motorbike;
use App\Models\OrderDetail;
use App\Models\Province;
use App\Models\RepairTask;
use App\Models\PositionInWarehouse;
use App\Models\User;
use App\Models\WorkContent;
use App\Models\RepairBill;
use App\Models\Supplier;

class MantainList extends Component
{
    public $searchChassicNo;
    public $searchEngineNo;
    public $searchPhone;
    public $searchMotorNumber;

    public $chassic_no;
    public $engine_no;
    public $model_code;
    public $model_type;
    public $model_list;
    public $color;
    public $serviceRequest;
    public $resultRepair;

    public $contentSuggest; // Tư vấn sửa chữa
    public $beforeRepair = false; // Trước sửa chữa
    public $afterRepair = false; // Sau sửa chữa
    public $notNeedWash = false; // Không cần rửa xe

    public $km_no;
    public $check_no;
    public $check_date;
    public $next_km_check_no;
    public $next_check_date;
    public $motor_numbers;

    public $customer_name;
    public $customer_address;
    public $customer_phone;
    public $customer_age;
    public $customer_sex;
    public $customerPoint;
    public $customer_job;
    public $sell_date;
    public $province_id;
    public $district_id;

    public $order_id;
    public $customer_id;
    public $motorbike_id;
    public $periodic_id;

    public $checkService = [];
    public $repairNoteHistory = [];

    public $task_content = [];
    public $task_price = [];
    public $task_promotion = [];
    public $task_total = [];
    public $task_payment = [];
    public $task_process_company = [];
    public $task_out_service = [];
    public $task_service_user_fix_id = [];
    public $tasks = [];
    public $i = 0;

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
    // public $accessory_price_vat = [];
    // public $accessory_price_actual = [];
    public $accessory_product = [];
    public $accessories = [];
    public $orderDetailForAccessory = [];
    public $j = 0;

    public $accessory_warehouse_pos_list = [];
    public $positionsList = [];

    public $editStatus = false;
    public $showStatus = false;
    public $addNew = false;
    public $saveFlag = true;

    public $customer;
    public $motorbike;
    public $periodic;

    public $order;
    public $isvirtual;

    public $work_status_update = 1;

    public $listFixer = [];
    public $listExporter = [];
    public $listContent = [];

    protected $listeners = [
        'setCheckDate',
        'setSellDate',
        'search',
        'countTaskPrice',
        'changeUserFixId',
        'changeAccessoryCode',
        'changeWarehousePos',
        'countAccessoryPrice',
        'changeWorkContentId',
        'searchHistory'
    ];

    public $inspectionStaffs;
    public $service_user_check_id;

    public $technicalStaffs;
    public $service_user_id;

    public $service_user_fix_id;
    public $service_user_export_id;

    public $accessories_list = [];
    public $accessories_select = [];

    public $positions_list = [];
    public $positions_select = [];

    public $showViewHistoryBtn = false;
    public $historyTasks = [];
    public $historyAccessories = [];

    public function updatedCustomerPhone($value)
    {
        if ($customer = Customer::where('phone', $this->customer_phone)->first()) {
            $this->customer_name = $customer->name;
            $this->customer_address = $customer->address;
            $this->customer_age = $customer->age;
            $this->customer_sex = $customer->sex;
            $this->customer_job = $customer->job;
            $this->province_id = $customer->city;
            $this->district_id = $customer->district;
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Số điện thoại đã được đăng ký']);
        }
    }

    public function mount()
    {

        if (isset($_GET['show'])) {
            $this->showStatus = true;
            $this->showViewHistoryBtn = true;
        }
        if (isset($_GET['edit'])) {
            $this->editStatus = true;
            $this->showViewHistoryBtn = true;
        }

        $this->listFixer = User::whereIn('positions', [EUserPosition::NV_KI_THUAT, EUserPosition::NV_KIEM_TRA, EUserPosition::NV_SUA_CHUA])->get();
        $this->listExporter = User::whereIn('positions', [EUserPosition::NV_KIEM_KHO])->get();
        $this->listContent = WorkContent::select('id', 'name')->get();
        if (isset($_GET['id'])) {
            $order = Order::with(['customer', 'motorbike', 'periodic'])->where('id', $_GET['id'])
                ->whereHas('customer')
                ->whereHas('motorbike')
                ->first();

            if (empty($order)) {
                return;
            }

            $this->order_id = $_GET['id'];
            $this->customer_id = $order->customer->id;
            $this->customer_name = $order->customer->name;
            $this->customer_address = $order->customer->address;
            $this->customer_phone = $order->customer->phone;
            $this->customer_age = $order->customer->age;
            $this->customer_sex = $order->customer->sex;
            $this->customer_job = $order->customer->job;
            $this->province_id = $order->customer->city;
            $this->district_id = $order->customer->district;
            $this->customerPoint =  $order->customer->point;
            $this->motorbike = $order->motorbike ?? null;
            if ($this->motorbike) {
                $this->motorbike_id = $this->motorbike->id;
                $this->motor_numbers = $this->motorbike->motor_numbers;
                $this->chassic_no = $this->motorbike->chassic_no;
                $this->engine_no = $this->motorbike->engine_no;
                $this->sell_date = $this->motorbike->sell_date;

                $this->periodic = $order->periodic ?? null;
              
                if ($this->periodic) {
                    $this->periodic_id = $this->periodic->id;
                    $this->km_no = $this->periodic->km;
                    $this->check_no = $this->periodic->periodic_level;
                    $this->check_date = $this->periodic->check_date;
                    $this->service_user_id = $this->periodic->service_user_id;
                    $this->service_user_check_id = $this->periodic->service_user_check_id;
                    $this->service_user_fix_id = $this->periodic->service_user_fix_id;
                    $this->service_user_export_id = $this->periodic->service_user_export_id;
                    $this->serviceRequest = $this->periodic->content_request;
                    $this->contentSuggest =  $this->periodic->content_suggest;
                    $this->beforeRepair =  $this->periodic->before_repair == 1;
                    $this->afterRepair =  $this->periodic->after_repair == 1;
                    $this->notNeedWash =  $this->periodic->not_need_wash == 1;

                    $this->resultRepair = $this->periodic->result_repair;
                    $this->checkService = explode(",", $this->periodic->check_service);

                }
            }

            $taskList = RepairTask::where('orders_id', $_GET['id'])->get();

            foreach ($taskList as $key => $task) {
                $this->tasks[$key + 1] = $key + 1;
                $this->task_content[$key + 1] = $task->work_content_id ?? '';
                $this->task_payment[$key + 1] = $task->payment ?? '';
                $this->task_process_company[$key + 1] = $task->process_company ?? '';
                $this->task_price[$key + 1] = $task->price ?? 0;
                $this->task_promotion[$key + 1] = $task->promotion ?? 0;
                $this->task_service_user_fix_id[$key + 1] = $task->id_fixer_main;
                $this->task_total[$key + 1] = $this->showTaskPrice($task->price, $task->promotion);
            }

            $accessoryList = OrderDetail::with(['accessorie', 'accessorie.supplier', 'order', 'positioninwarehouse'])
                ->where('order_id', $_GET['id'])
                ->where('type', EOrderDetail::TYPE_BANLE)
                ->get();

            foreach ($accessoryList as $key => $accessory) {
                $this->accessories[$key + 1] = $key + 1;
                $this->accessory_code[$key + 1] = $accessory->accessorie->code ?? '';
                $this->accessory_warehouse_pos[$key + 1] = $accessory->positioninwarehouse->id ?? '';
                $this->accessory_name[$key + 1] = $accessory->accessorie->name ?? '';
                $this->accessory_supplier[$key + 1] = $accessory->accessorie->supplier->code ?? '';
                $this->accessory_quantity[$key + 1] = $accessory->quantity ?? 0;
                $this->accessory_price[$key + 1] = $accessory->price ?? 0;
                $this->accessory_promotion[$key + 1] = $accessory->promotion ?? 0;
                $this->accessory_total[$key + 1] = $this->showAccessoryPrice($accessory->quantity, $accessory->price, $accessory->promotion);
                // $this->accessory_price_vat[$key + 1] = $accessory->vat_price ?? 0;
                // $this->accessory_price_actual[$key + 1] = $accessory->actual_price ?? 0;
                $this->orderDetailForAccessory[$key + 1] = $accessory->id;
                $this->accessory_product[$key + 1] = OrderDetail::where('id',  $accessory->id)
                    ->pluck('product_id')
                    ->first();

                $this->accessory_available_quantity[$key + 1] = $accessory->accessorie->quantity ?? 0;
                $this->accessory_available_quantity_root[$key + 1] = $accessory->accessorie->quantity ?? 0;

                $this->accessories_list[$key + 1] = Accessory::whereNotNull('code')
                    ->whereNotIn('code', $this->accessories_select)
                    ->where('quantity', '>', 0)
                    ->pluck('code')
                    ->unique();
                $this->accessories_select[] = $accessory->code;

                $positionIds = Accessory::where('code', $accessory->accessorie->code)
                    ->where('quantity', '>', 0)
                    ->select('position_in_warehouse_id')
                    ->pluck('position_in_warehouse_id');

                $this->positions_list[$key + 1] = PositionInWarehouse::with(['warehouse'])
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
        } else {
            $this->check_date = Carbon::now()->format('Y-m-d');
        }
    }

    public function render()
    {

        $districts = [];
        $provinces = Province::get();
        if ($this->province_id) {
            $districts = District::where('province_code', $this->province_id)->orderBy('name')->pluck('name', 'district_code');
        }

        $this->technicalStaffs = User::whereHas(
            'roles',
            function ($q) {
                $q->where('id', EUserPosition::NV_KI_THUAT);
            }
        )->get();
        $this->inspectionStaffs = User::whereHas(
            'roles',
            function ($q) {
                $q->where('id', EUserPosition::NV_KIEM_TRA);
            }
        )->get();

        $this->dispatchBrowserEvent('setSelect2');
        $this->dispatchBrowserEvent('setDateForDatePicker');
        if ($this->check_no) {
            $this->next_km_check_no = $this->getNextKTDK($this->check_no)['nextCheckKm'];
            $this->next_check_date = $this->getNextKTDK($this->check_no)['nextCheckDateTime'];
        }

        $historyData = [];
        if ($this->motorbike_id) {
            $repairBill = RepairBill::where('motorbikes_id', $this->motorbike_id)
                ->with(['order', 'serviceUser', 'user'])
                ->orderBy('in_factory_date', 'desc')
                ->get();

            $periodics = Periodic::where('motorbikes_id', $this->motorbike_id)
                ->with(['order', 'serviceUser', 'user'])
                ->orderBy('check_date', 'desc')
                ->get();

            foreach ($repairBill as $bill) {
                $bill->history_type = 1; //sctt
                $historyData[] = $bill;
            }
            foreach ($periodics as $periodic) {
                $periodic->history_type = 2; //KTĐK
                $historyData[] = $periodic;
            }
        }
        $HEAD_NAME = env('APP_HEADNAME');
        return view('livewire.service.mantain-list', [
            'provinces' => $provinces,
            'districts' => $districts,
            'historyData' => $historyData,
            'HEAD_NAME' => $HEAD_NAME
        ]);
    }

    public function search()
    {
        if (empty($this->searchChassicNo) && empty($this->searchEngineNo) && empty($this->searchPhone) && empty($this->searchMotorNumber)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Hãy nhập thông tin để tìm kiếm']);
            return;
        }

        $this->addNew = false;
        $this->searchChassicNo = trim($this->searchChassicNo);
        $this->searchEngineNo = trim($this->searchEngineNo);
        $this->searchPhone = trim($this->searchPhone);
        $this->searchMotorNumber = trim($this->searchMotorNumber);

        $motorbike = Motorbike::with(['customer']);

        if ($this->searchChassicNo) {
            $motorbike = $motorbike->where('chassic_no', $this->searchChassicNo);
        }
        if ($this->searchEngineNo) {
            $motorbike = $motorbike->where('engine_no', $this->searchEngineNo);
        }
        if ($this->searchMotorNumber) {
            $motorbike = $motorbike->where('motor_numbers', $this->searchMotorNumber);
        }
        if ($this->searchPhone) {
            $motorbike = $motorbike->whereHas('customer', function ($q) {
                $q->where('customers.phone', $this->searchPhone);
            });
        }

        $motorbike = $motorbike->first();

        $this->resetInputFields();
        $this->resetSearchInputFields();

        if (empty($motorbike)) {
            $this->dispatchBrowserEvent('show-confirm', ['type' => 'info', 'message' => 'Không tìm thấy xe trên hệ thống. Bạn có muốn tạo mới thông tin xe và khách hàng không?']);
            return;
        }

        if (empty($motorbike->customer_id)) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Xe chưa đủ điều kiện KTĐK']);
            return;
        }

        if (empty($motorbike->is_out)) {
            $beforeWeek = Carbon::now()->subDays(7);
            if (Carbon::createFromFormat('Y-m-d', $motorbike->sell_date) > $beforeWeek) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Chỉ tạo được phiếu kiểm tra định kỳ đối với những xe bán trước ngày ' . $beforeWeek->format('Y/m/d') . ' (trước 7 ngày)']);
                return;
            }

            $endDateCheck = Carbon::createFromFormat('Y-m-d', $motorbike->sell_date)->addYears(3)->format('Y-m-d');
            $now = Carbon::now()->format('Y-m-d');
            if ($now > $endDateCheck) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Xe vượt quá thời gian KTĐK']);
                return;
            }
        }

        $this->showViewHistoryBtn = true;

        $this->sell_date = $motorbike->sell_date;
        $this->motor_numbers = $motorbike->motor_numbers;

        $this->searchChassicNo = $motorbike->chassic_no ?? '';
        $this->searchEngineNo = $motorbike->engine_no ?? '';
        $this->searchMotorNumber = $motorbike->motor_numbers ?? '';
        $this->searchPhone = $motorbike->customer->phone ?? '';

        $this->chassic_no = $motorbike->chassic_no ?? '';
        $this->engine_no = $motorbike->engine_no ?? '';

        $this->customer_id = isset($motorbike->customer) ? $motorbike->customer->id : '';

        $this->motorbike_id = $motorbike->id;

        $this->province_id = $motorbike->customer->city ?? '';
        $this->district_id = $motorbike->customer->district ?? '';
        $this->customer_address = $motorbike->customer->address ?? '';
        $this->customer_name = $motorbike->customer->name ?? '';
        $this->customer_age = $motorbike->customer->age ?? '';
        $this->customer_sex = $motorbike->customer->sex ?? '';
        $this->customer_phone = $motorbike->customer->phone ?? '';
        $this->customer_job = $motorbike->customer->job ?? '';
        $this->customerPoint =  $motorbike->customer->point;
        $this->periodic = $motorbike->periodics->last() ?? null;
        if ($this->periodic) {
            $this->periodic_id = $this->periodic->id;
            $this->km_no = $this->periodic->km;
            $this->check_no = $this->periodic->periodic_level;
            $this->check_date = $this->periodic->check_date;
        }
    }

    public function store()
    {
        $isRepair = isRepair($this->checkService);
        $this->validate(
            [
                'motor_numbers' => $isRepair ? 'required|max:255' : '',
                'serviceRequest' => 'required',
                //'km_no' => 'required',
                'chassic_no' => 'required|regex:/^[a-zA-Z0-9]*$/',
                'engine_no' => 'required|regex:/^[a-zA-Z0-9-]*$/',
                'customer_name' => 'required',
                'customer_phone' => 'required|regex:/^[0-9]+$/',
                //'service_user_check_id' => $isRepair ? 'required' : '',
                'service_user_id' => 'required',
                //'service_user_fix_id' => $isRepair ? 'required' : '',
                //'service_user_export_id' => 'required',
            ],
            [
                'motor_numbers.required' => 'Bắt buộc nhập biển số',
                'motor_numbers.max' => 'Giá trị nhập vào quá lớn',
                'km_no.required' => 'Bắt buộc nhập số KM',
                'chassic_no.required' => 'Bắt buộc nhập số khung',
                'chassic_no.regex' => 'Số khung chỉ bao gồm chữ cái và số',
                'engine_no.required' => 'Bắt buộc nhập số máy',
                'engine_no.regex' => 'Số mày chỉ bao gồm chữ cái, số và dấu gạch ngang "-"',
                'customer_name.required' => 'Tên khách hàng là bắt buộc',
                'customer_phone.required' => 'Số điện thoại là bắt buộc',
                'customer_phone.regex' => 'Số điện thoại không không đúng định dạng',
                //'service_user_check_id.required' => 'Bắt buộc chọn người kiểm tra',
                'service_user_id.required' => 'Bắt buộc chọn người tiếp nhận',
                'service_user_fix_id.required' => 'Bắt buộc chọn người sửa chữa chính',
                'service_user_export_id.required' => 'Bắt buộc chọn người xuất kho',
                'serviceRequest.required' => 'Triệu chứng yêu cầu kiểm tra băt buộc nhập',
            ]
        );

        if ($this->accessories && empty($this->service_user_export_id)) {
            return $this->addError('service_user_export_id', 'Có phụ tùng cần xuất kho nên người xuất kho là bắt buộc');
        }
        if (!empty($this->tasks)) {
            foreach ($this->tasks as $task) {
                $this->validate(
                    [
                        'task_content.' . $task => 'required',
                        'task_service_user_fix_id.' . $task => 'required',
                        'task_price.' . $task => 'required|numeric|min:0|max:9999999999',
                        'task_promotion.' . $task => 'required|numeric|min:0|max:100',
                    ],
                    [
                        'task_content.' . $task . '.required' => 'Bắt buộc nhập nội dung công việc',
                        'task_service_user_fix_id.' . $task . '.required' => 'Bắt buộc nhập nhân viên sửa chữa',
                        'task_price.' . $task . '.required' => 'Bắt buộc nhập tiền công',
                        'task_price.' . $task . '.min' => 'Tiền công tối thiếu là 0',
                        'task_price.' . $task . '.max' => 'Tiền công tối đa là 9999999999',
                        'task_promotion.' . $task . '.required' => 'Bắt buộc nhập khuyến mại',
                        'task_promotion.' . $task . '.min' => 'Khuyến mãi tối thiếu là 0',
                        'task_promotion.' . $task . '.max' => 'Khuyến mãi tối đa là 100',
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
        if ($this->accessories && empty($this->service_user_fix_id)) {
            $this->work_status_update = 2;
        } else if (!empty($this->service_user_fix_id)) {
            $this->work_status_update = 3;
        } else {
            $this->work_status_update = 1;
        }
        DB::beginTransaction();
        $customerId = $this->customer_id ?? null;
        $motorbikeId = $this->motorbike_id ?? null;
        if (!$this->addNew) {
            $customerData = [
                'name' => $this->customer_name,
                'job' => $this->customer_job ?? null,
                'age' => !empty($this->customer_age) ? $this->customer_age : null,
                'sex' => !empty($this->customer_sex) ? $this->customer_sex : null,
                'phone' => $this->customer_phone,
                'address' => $this->customer_address ?? null,
                'district' => empty($this->district_id) ? null : $this->district_id,
                'city' => empty($this->province_id) ? null : $this->province_id
            ];
            Customer::query()->where('id', $customerId)->update($customerData);
            Motorbike::query()
                ->where('id', $motorbikeId)
                ->update([
                    'motor_numbers' => $this->motor_numbers,
                    'sell_date' => $this->sell_date,
                ]);
        } else {

            $now = DateTime::createFromFormat('U.u', microtime(true))->modify('+ 7 hour');
            $customer = Customer::where('phone', $this->customer_phone)->first();
            if ($customer) {
                $customer->name = $this->customer_name;
                $customer->age = $this->customer_age ?? null;
                $customer->sex = $this->customer_sex ?? null;
                $customer->job = $this->customer_job ?? null;
                $customer->phone = $this->customer_phone ?? null;
                $customer->address = $this->customer_address ?? null;
                $customer->district = empty($this->district_id) ? null : $this->district_id;
                $customer->city = empty($this->province_id) ? null : $this->province_id;
            } else {
                $customerCode = 'CO_' . substr($now->format("ymdhisu"), 0, -3);
                $customer = new Customer();
                $customer->name = $this->customer_name;
                $customer->code = $customerCode;
                $customer->age = $this->customer_age ?? null;
                $customer->sex = $this->customer_sex ?? null;
                $customer->job = $this->customer_job ?? null;
                $customer->phone = $this->customer_phone ?? null;
                $customer->address = $this->customer_address ?? null;
                $customer->district = empty($this->district_id) ? null : $this->district_id;
                $customer->city = empty($this->province_id) ? null : $this->province_id;
            }
            if ($customer->save()) {
                $customerId = $customer->id;
                $motorbike = new Motorbike();
                $motorbike->chassic_no = $this->chassic_no;
                $motorbike->engine_no = $this->engine_no;
                $motorbike->model_code = $this->model_code;
                $motorbike->model_type = $this->model_type;
                $motorbike->model_list = $this->model_list;
                $motorbike->color = $this->color;
                $motorbike->customer_id = $customerId;
                $motorbike->motor_numbers = $this->motor_numbers;
                $motorbike->sell_date = $this->sell_date;
                $motorbike->is_out = 1;
                $motorbike->status = EMotorbike::OUT;
                $motorbike->save();
                $motorbikeId = $motorbike->id;
            }
        }
        if (!$this->check_date) {
            $this->check_date = date('Y-m-d');
        }

        $order = new Order;
        $order->customer_id = $customerId;
        $order->motorbikes_id = $motorbikeId;
        $order->category = EOrder::CATE_MAINTAIN;
        $order->created_by = Auth::user()->id;
        $order->total_items = 1;
        $order->status = EOrder::STATUS_UNPAID;
        $order->isvirtual = $this->isvirtual ?? 0;
        $order->work_status = $this->work_status_update;
        $order->save();
        $periodic = new Periodic;
        $periodic->km = $this->km_no;
        $periodic->motorbikes_id = $motorbikeId;
        $periodic->customers_id = $customerId;
        $periodic->check_date = $this->check_date;
        $periodic->motor_number = $this->motor_numbers;
        $periodic->periodic_level = $this->check_no;
        $periodic->orders_id = $order->id;
        $periodic->service_user_id = empty($this->service_user_id) ? null : $this->service_user_id;
        $periodic->service_user_check_id = empty($this->service_user_check_id) ? null : $this->service_user_check_id;
        $periodic->content_request = $this->serviceRequest;

        $periodic->content_suggest = $this->contentSuggest;
        $periodic->before_repair = $this->beforeRepair;
        $periodic->after_repair = $this->afterRepair;
        $periodic->not_need_wash = $this->notNeedWash;

        $periodic->result_repair = $this->resultRepair;
        $periodic->service_user_fix_id = empty($this->service_user_fix_id) ? null : $this->service_user_fix_id;
        $periodic->service_user_export_id = empty($this->service_user_export_id) ? null : $this->service_user_export_id;
        $periodic->check_service = implode(",", $this->checkService);
        $periodic->save();

        $order_detail = new OrderDetail;
        $order_detail->product_id = $motorbikeId;
        $order_detail->order_id = $order->id;
        $order_detail->admin_id = auth()->id();
        $order_detail->quantity = 1;
        $order_detail->category = EOrderDetail::CATE_MAINTAIN;
        $order_detail->status = EOrderDetail::STATUS_SAVED;
        $order_detail->save();



        if (!empty($this->tasks)) {
            $taskData = [];
            foreach ($this->tasks as $key => $task) {
                $itemTask = [

                    'payment' => isset($this->task_payment[$task]) ? $this->task_payment[$task] : 0,
                    'process_company' => isset($this->task_process_company[$task]) ? $this->task_process_company[$task] : 0,
                    'work_content_id' => $this->task_content[$task],
                    'price' => $this->task_price[$task],
                    'promotion' => isset($this->task_promotion[$task]) ? $this->task_promotion[$task] : 0,
                    'status' => ERepairTask::SAVED,
                    'admin_id' => auth()->id(),
                    'orders_id' => $order->id,
                    'id_fixer_main' => $this->task_service_user_fix_id[$task],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'supply_id' => null,
                    'order_payment_id' => null,
                ];

                if ($this->task_out_service[$task]) {
                    $supply = Supplier::updateOrCreate([
                        'name' => $this->task_process_company[$task],
                        'code' => $this->task_process_company[$task],
                    ], [
                        'phone' => null,
                        'email' => null,
                        'url' => null,
                        'address' => null,
                        'province_id' => null,
                        'district_id' => null,
                        'ward_id' => null,
                    ]);

                    $orderPayment = new Order;
                    $orderPayment->customer_id = null;
                    $orderPayment->total_money = isset($this->task_payment[$task]) ? $this->task_payment[$task] : 0;
                    $orderPayment->total_items = 0;
                    $orderPayment->category = EOrder::CATE_REPAIR;
                    $orderPayment->status = EOrder::STATUS_UNPAID;
                    $orderPayment->type = EOrder::TYPE_NHAP;
                    $orderPayment->order_type = EOrder::ORDER_TYPE_BUY;
                    $orderPayment->motorbikes_id = null;
                    $orderPayment->created_by = Auth::user()->id;
                    $orderPayment->supplier_id = $supply->id;
                    $orderPayment->save();

                    $itemTask['supply_id'] = $supply->id;
                    $itemTask['order_payment_id'] = $orderPayment->id;
                }

                $taskData[] = $itemTask;
            }

            if (!RepairTask::insert($taskData)) {
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
                    'vat_price' => null,
                    'actual_price' => null,
                    // 'vat_price' => $this->accessory_price_vat[$accessory],
                    // 'actual_price' => $this->accessory_price_actual[$accessory],
                    'position_in_warehouse_id' => $this->accessory_warehouse_pos[$accessory],
                    'warehouse_id' => PositionInWarehouse::findOrFail($this->accessory_warehouse_pos[$accessory])->warehouse_id,
                    'promotion' => $this->accessory_promotion[$accessory],
                    'status' => EOrderDetail::STATUS_SAVED,
                    'admin_id' => auth()->id(),
                    'category' => EOrderDetail::CATE_MAINTAIN,
                    'type' => EOrderDetail::TYPE_BANLE,
                    'order_id' => $order->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
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

        $order->total_items = $order->totalItem();
        $order->total_money = $order->totalPriceForKTDK();
        $order->save();
        DB::commit();
        $this->resetSearchInputFields();
        $this->resetInputFields();
        $this->resetTaskInputFields();
        $this->resetAccessoryInputFields();
        $this->resetPeriodicInputFields();
        $this->dispatchBrowserEvent('confirmAddPrintPdf', ['urlPrintf' => route('dichvu.dsdonhang.print', ['id' => $order->id])]);
    }

    public function resetInputFields()
    {
        $this->km_no = null;
        $this->check_no = null;
        $this->check_date = Carbon::now()->format('Y-m-d');
        $this->model_code = null;
        $this->model_list = null;
        $this->model_type = null;
        $this->color = null;
        $this->chassic_no = null;
        $this->engine_no = null;
        $this->head_get = null;
        $this->motor_numbers = null;
        $this->customer_name = null;
        $this->customer_address = null;
        $this->customer_phone = null;
        $this->province_id;
        $this->customer_age = null;
        $this->district_id;
        $this->customer_sex = null;
        $this->ward_id = null;
        $this->sell_date = null;
        $this->customer_job = null;
        $this->emit('reset-input-info-customer');
    }

    public function resetSearchInputFields()
    {
        $this->searchChassicNo = null;
        $this->searchEngineNo = null;
        $this->searchPhone = null;
        $this->searchMotorNumber = null;
    }

    public function resetTaskInputFields()
    {
        $this->task_content = [];
        $this->task_price = [];
        $this->task_promotion = [];
        $this->task_total = [];
        $this->tasks = [];
        $this->i = 0;
    }

    public function resetPeriodicInputFields()
    {
        $this->service_user_check_id = '';
        $this->service_user_id = '';
        $this->service_user_fix_id = '';
        $this->service_user_export_id = '';

        $this->checkService = [];
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
        // $this->accessory_price_vat = [];
        // $this->accessory_price_actual = [];
        $this->accessory_product = [];
        $this->accessories = [];
        $this->j = 0;
        $this->accessory_warehouse_pos_list = [];
        $this->positionsList = [];
        $this->serviceRequest = '';
        $this->resultRepair = '';
        $this->contentSuggest =  '';
        $this->beforeRepair =  false;
        $this->afterRepair =  false;
        $this->notNeedWash =  false;
    }

    public function update()
    {
        $isRepair = isRepair($this->checkService);
        $this->validate(
            [
                'motor_numbers' =>  $isRepair ? 'required|max:255' : '',
                'serviceRequest' => 'required',
                //'km_no' => 'required',
                'chassic_no' => 'required|regex:/^[a-zA-Z0-9]*$/',
                'engine_no' => 'required|regex:/^[a-zA-Z0-9-]*$/',
                'customer_phone' => 'regex:/^[0-9]+$/',
                'service_user_check_id' => $isRepair ? 'required' : '',
                'service_user_id' => 'required',
                'service_user_fix_id' => $isRepair ? 'required' : '',
            ],
            [
                'motor_numbers.required' => 'Bắt buộc nhập biển số',
                'motor_numbers.max' => 'Giá trị nhập vào quá lớn',
                'km_no.required' => 'Bắt buộc nhập số KM',
                'chassic_no.required' => 'Bắt buộc nhập số khung',
                'chassic_no.regex' => 'Số khung chỉ bao gồm chữ cái và số',
                'engine_no.required' => 'Bắt buộc nhập số máy',
                'engine_no.regex' => 'Số mày chỉ bao gồm chữ cái, số và dấu gạch ngang "-"',
                'customer_phone.regex' => 'Số điện thoại không không đúng định dạng',
                'service_user_check_id.required' => 'Bắt buộc chọn người kiểm tra',
                'service_user_id.required' => 'Bắt buộc chọn người tiếp nhận',
                'service_user_fix_id.required' => 'Bắt buộc chọn người sửa chữa chính',
                'serviceRequest.required' => 'Triệu chứng yêu cầu kiểm tra băt buộc nhập',
            ]
        );

        if ($this->accessories && empty($this->service_user_export_id)) {
            return $this->addError('service_user_export_id', 'Có phụ tùng cần xuất kho nên người xuất kho là bắt buộc');
        }
        if (!empty($this->tasks)) {
            foreach ($this->tasks as $task) {
                $this->validate(
                    [
                        'task_content.' . $task => 'required',
                        'task_service_user_fix_id.' . $task => 'required',
                        'task_price.' . $task => 'required|numeric|min:0|max:9999999999',
                        'task_promotion.' . $task => 'numeric|min:0|max:100',
                    ],
                    [
                        'task_content.' . $task . '.required' => 'Bắt buộc nhập nội dung công việc',
                        'task_service_user_fix_id.' . $task . '.required' => 'Bắt buộc nhập nhân viên sửa chữa',
                        'task_price.' . $task . '.required' => 'Bắt buộc nhập tiền công',
                        'task_price.' . $task . '.min' => 'Tiền công tối thiếu là 0',
                        'task_price.' . $task . '.max' => 'Tiền công tối đa là 9999999999',
                        'task_promotion.' . $task . '.min' => 'Khuyến mãi tối thiếu là 0',
                        'task_promotion.' . $task . '.max' => 'Khuyến mãi tối đa là 100',
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
        if ($this->accessories && empty($this->service_user_fix_id)) {
            $this->work_status_update = 2;
        } else if (!empty($this->service_user_fix_id)) {
            $this->work_status_update = 3;
        } else {
            $this->work_status_update = 1;
        }
        try {
            DB::beginTransaction();
            $periodic = Periodic::find($this->periodic_id);
            $periodic->km = $this->km_no;
            $periodic->check_date = $this->check_date;
            $periodic->periodic_level = $this->check_no;
            $periodic->service_user_id = empty($this->service_user_id) ? null : $this->service_user_id;
            $periodic->service_user_check_id = empty($this->service_user_check_id) ? null : $this->service_user_check_id;
            $periodic->service_user_fix_id = empty($this->service_user_fix_id) ? null : $this->service_user_fix_id;
            $periodic->service_user_export_id =  empty($this->service_user_export_id) ? null : $this->service_user_export_id;
            $periodic->check_service = implode(",", $this->checkService);
            $periodic->content_request = $this->serviceRequest;
            $periodic->result_repair = $this->resultRepair;
            $periodic->before_repair = $this->beforeRepair;
            $periodic->after_repair =  $this->afterRepair;
            $periodic->not_need_wash =  $this->notNeedWash;
            $periodic->save();

            $customerData = [
                'name' => $this->customer_name,
                'job' => $this->customer_job ?? null,
                'age' => !empty($this->customer_age) ? $this->customer_age : null,
                'sex' => !empty($this->customer_sex) ? $this->customer_sex : null,
                'phone' => $this->customer_phone ?? null,
                'address' => $this->customer_address ?? null,
                'district' => empty($this->district_id) ? null : $this->district_id,
                'city' => empty($this->province_id) ? null : $this->province_id
            ];

            Customer::query()->where('id', $this->customer_id)->update($customerData);

            Motorbike::query()
                ->where('id', $this->motorbike_id)
                ->update([
                    'motor_numbers' => $this->motor_numbers,
                    'sell_date' => $this->sell_date,
                ]);


            if (!empty($this->tasks)) {
                $taskData = [];
                foreach ($this->tasks as $key => $task) {
                    $taskData[] = [
                        'payment' => isset($this->task_payment[$task]) ? $this->task_payment[$task] : 0,
                        'process_company' => isset($this->task_process_company[$task]) ? $this->task_process_company[$task] : 0,
                        'work_content_id' => $this->task_content[$task],
                        'price' => $this->task_price[$task],
                        'promotion' => isset($this->task_promotion[$task]) ? $this->task_promotion[$task] : 0,
                        'id_fixer_main' => $this->task_service_user_fix_id[$task],
                        'status' => ERepairTask::SAVED,
                        'admin_id' => auth()->id(),
                        'orders_id' => $this->order_id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }

                $task = RepairTask::where('orders_id', $this->order_id)->delete();
                if (!RepairTask::insert($taskData)) {
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Cập nhật không thành công']);
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
                        'vat_price' => null,
                        'actual_price' => null,
                        //'vat_price' => $this->accessory_price_vat[$accessory],
                        //'actual_price' => $this->accessory_price_actual[$accessory],
                        'position_in_warehouse_id' => $this->accessory_warehouse_pos[$accessory],
                        'warehouse_id' => PositionInWarehouse::findOrFail($this->accessory_warehouse_pos[$accessory])->warehouse_id,
                        'promotion' => $this->accessory_promotion[$accessory],
                        'status' => EOrderDetail::STATUS_SAVED,
                        'admin_id' => auth()->id(),
                        'category' => EOrderDetail::CATE_MAINTAIN,
                        'type' => EOrderDetail::TYPE_BANLE,
                        'order_id' => $this->order_id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }

                // Cộng trả lại tất cả số lượng phụ tùng cũ trước khi clear order
                $orderOldForAccessory = OrderDetail::where('order_id', $this->order_id)
                    ->where('category', EOrderDetail::CATE_MAINTAIN)
                    ->where('type', EOrderDetail::TYPE_BANLE)
                    ->where('status', EOrderDetail::STATUS_SAVED)
                    ->get();
                foreach ($orderOldForAccessory as $key => $accessoryOld) {
                    $accessory = Accessory::where('id', $accessoryOld->product_id)
                        ->where('position_in_warehouse_id', $accessoryOld->position_in_warehouse_id)
                        ->first();
                    if ($accessory) {
                        $accessory->quantity += $accessoryOld->quantity;
                        $accessory->save();
                    }
                }

                OrderDetail::where('order_id', $this->order_id)
                    ->where('category', EOrderDetail::CATE_MAINTAIN)
                    ->where('type', EOrderDetail::TYPE_BANLE)
                    ->where('status', EOrderDetail::STATUS_SAVED)->delete();
                if (!OrderDetail::insert($accessoryData)) {
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Thêm mới không thành công']);
                    return;
                }

                foreach ($this->accessory_product as $key => $accessoryId) {
                    $accessory = Accessory::where('id', $accessoryId)
                        ->where('position_in_warehouse_id', $this->accessory_warehouse_pos[$key])
                        ->first();

                    if ($accessory) {
                        if ($accessory->quantity >= (int)$this->accessory_quantity[$key]) {
                            $accessory->quantity -= (int)$this->accessory_quantity[$key];
                            $accessory->save();
                        } else {
                            DB::rollBack();
                            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Tạo phiếu KTĐK thất bại']);
                            return;
                        }
                    }
                }
            }
            $orders = Order::where('id', $this->order_id)->first();
            $orders->total_items = $orders->totalItem();
            $orders->total_money = $orders->totalPriceForKTDK();
            $orders->work_status = $this->work_status_update;
            $orders->save();
            DB::commit();
            $this->dispatchBrowserEvent('confirmEditPrintPdf', ['urlPrintf' => route('dichvu.dsdonhang.print', ['id' => $this->order_id])]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Tạo phiếu KTĐK thất bại']);
            return;
        }
    }

    public function updatedKmNo()
    {
        $now = Carbon::now();
        if ($this->km_no > KMCheckStep::SIX) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Số km vượt quá số km bảo hành (' . numberFormat(KMCheckStep::SIX) . ')']);
            $this->saveFlag = false;
            return;
        } else {
            $this->saveFlag = true;
        }

        //check selldate co ko null va
        if (isset($this->sell_date) && !empty($this->sell_date)) {
            $sellDate = Carbon::createFromFormat('Y-m-d', $this->sell_date);

            if (($now->gte($sellDate->addDays(7)) && $now->lte($sellDate->addMonths(1)))) {
                $this->check_no = 1;
            }
            if (($now->gte($sellDate->addMonths(1)->addDays(1)) && $now->lte($sellDate->addMonths(6)))) {
                $this->check_no = 2;
            }
            if (($now->gte($sellDate->addMonths(6)->addDays(1)) && $now->lte($sellDate->addMonths(12)))) {
                $this->check_no = 3;
            }
            if (($now->gte($sellDate->addMonths(12)->addDays(1)) && $now->lte($sellDate->addMonths(18)))) {
                $this->check_no = 4;
            }
            if (($now->gte($sellDate->addMonths(18)->addDays(1)) && $now->lte($sellDate->addMonths(27)))) {
                $this->check_no = 5;
            }
            if (($now->gte($sellDate->addMonths(27)->addDays(1)) && $now->lte($sellDate->addMonths(36)))) {
                $this->check_no = 6;
            }
        }

        $checkNo = $this->check_no;
        if ($this->km_no > KMCheckStep::ZERO && $this->km_no <= KMCheckStep::ONE) {
            $checkNo = 1;
        }
        if ($this->km_no > KMCheckStep::ONE && $this->km_no <= KMCheckStep::TWO) {
            $checkNo = 2;
        }
        if ($this->km_no > KMCheckStep::TWO && $this->km_no <= KMCheckStep::THREE) {
            $checkNo = 3;
        }
        if ($this->km_no > KMCheckStep::THREE && $this->km_no <= KMCheckStep::FOUR) {
            $checkNo = 4;
        }
        if ($this->km_no > KMCheckStep::FOUR && $this->km_no <= KMCheckStep::FIVE) {
            $checkNo = 5;
        }
        if ($this->km_no > KMCheckStep::FIVE && $this->km_no <= KMCheckStep::SIX) {
            $checkNo = 6;
        }


        if ($checkNo > $this->check_no) {
            $this->check_no = $checkNo;
        }
    }

    public function setCheckDate($time)
    {
        $this->check_date = date('Y-m-d', strtotime($time['check_date']));
    }

    public function setSellDate($time)
    {
        $this->sell_date = date('Y-m-d', strtotime($time['sell_date']));
    }

    public function addTask($i)
    {
        $i = $i + 1;
        $this->i = $i;
        $this->task_promotion[$i] = 0;
        $this->task_out_service[$i] = false;
        $this->task_service_user_fix_id[$i] = $this->service_user_fix_id;
        array_push($this->tasks, $i);
    }

    public function removeTask($i)
    {
        unset($this->tasks[$i]);
    }

    public function addAccessory($j)
    {
        $j = $j + 1;
        if (!in_array($j, $this->accessories)) {
            $this->j = $j;
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
    }

    public function removeAccessory($j)
    {
        unset($this->accessories[$j]);
    }

    public function countTaskPrice($index)
    {
        $price = 0;
        $promotion = 0;
        $total = 0;

        if (isset($this->task_price[$index])) {
            $price = $this->task_price[$index];
        }
        if (isset($this->task_promotion[$index])) {
            $promotion = $this->task_promotion[$index];
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

        $this->task_total[$index] = number_format($total);
    }

    public function showTaskPrice($price, $promotion)
    {
        $total = 0;

        if (empty($price)) {
            $total = 0;
        }

        if (empty($promotion)) {
            $total = $price;
        }

        if (!empty($price) && !empty($promotion)) {
            $total = $price - (($price / 100) * $promotion);
        }

        return number_format($total);
    }

    public function showAccessoryPrice($quantity, $price, $promotion)
    {
        $total = 0;
        $price = $price * $quantity;

        if (empty($promotion)) {
            $total = $price;
        }

        if (!empty($price) && !empty($promotion)) {
            $total = $price - (($price / 100) * $promotion);
        }

        return number_format($total);
    }
    public function printMainListCheckNo()
    {
        $urlPrint = route('dichvu.dsdonhang.printCheckNo', [
            'checkNo' => $this->check_no,
            'km' => $this->km_no,
            'checkDate' => $this->check_date,
            'motorbikeId' => $this->motorbike_id
        ]);
        if ($this->check_no < 1 || $this->check_no > 6) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Số lần kiểm tra định kỳ không đúng']);
            return;
        }

        $this->dispatchBrowserEvent('printMainListCheckNo', ['urlPrintf' => $urlPrint]);
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
                $oldAccessoryQuatity = 0;
                if (count($this->orderDetailForAccessory) + 1 > $data['index']) {
                    $orderDetailId = $this->orderDetailForAccessory[$data['index']];
                    $oldAccessory = OrderDetail::where('id', $orderDetailId)->first();
                    $oldAccessoryQuatity = 0;
                    if ($oldAccessory) {
                        $oldAccessoryQuatity = $oldAccessory->quantity;
                    }
                }

                $this->accessory_product[$data['index']] = $record->id;
                $this->accessory_available_quantity[$data['index']] = $record->quantity + ($oldAccessoryQuatity - 1);
                $this->accessory_available_quantity_root[$data['index']] = $record->quantity;
                $this->accessory_quantity[$data['index']] = 1;
                $this->accessory_total[$data['index']] = $record->price;
                // $this->accessory_price_vat[$data['index']] = $record->price;
                // $this->accessory_price_actual[$data['index']] = $record->price;
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
        $oldAccessoryQuatity = 0;
        if (count($this->orderDetailForAccessory) + 1 > $index) {
            $orderDetailId = $this->orderDetailForAccessory[$index];
            $oldAccessory = OrderDetail::where('id', $orderDetailId)->first();

            if ($oldAccessory) {
                $oldAccessoryQuatity = $oldAccessory->quantity;
            }
        }

        if (count($this->accessory_available_quantity_root) > 0) {
            $this->accessory_available_quantity[$index] = $this->accessory_available_quantity_root[$index] + ($oldAccessoryQuatity - (int)$quantity);
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
        // $this->accessory_price_vat[$index] = $total;
        // $this->accessory_price_actual[$index] = $total;
    }

    public function changeUserFixId($data)
    {
        $this->task_service_user_fix_id[$data['index']] = $data['value'];
    }
    public function changeWorkContentId($data)
    {
        $work = WorkContent::where('id', $data['value'])->first();
        if ($work->type) {
            $this->task_out_service[$data['index']] = true;
        } else {
            $this->task_out_service[$data['index']] = false;
        }
        $this->task_content[$data['index']] = $data['value'];
    }

    public function getNextKTDK($currentCheckNo)
    {
        $data = [];
        $data['nextCheckDateTime'] = '';
        $data['nextCheckKm'] = '';
        if (!empty($this->sell_date)) {
            $sellDate = Carbon::createFromFormat('Y-m-d', $this->sell_date);
            switch ($currentCheckNo) {
                case 1:
                    $data['nextCheckDateTime'] = $sellDate->addMonths(1)->addDays(1)->format('Y-m-d') . ' ~ ' . $sellDate->addMonths(6)->format('Y-m-d');
                    $data['nextCheckKm'] = numberFormat(KMCheckStep::ONE + 1) . ' ~ ' . numberFormat(KMCheckStep::TWO);
                    break;
                case 2:
                    $data['nextCheckDateTime'] = $sellDate->addMonths(6)->addDays(1)->format('Y-m-d') . ' ~ ' . $sellDate->addMonths(12)->format('Y-m-d');
                    $data['nextCheckKm'] = numberFormat(KMCheckStep::TWO + 1) . ' ~ ' . numberFormat(KMCheckStep::THREE);
                    break;
                case 3:
                    $data['nextCheckDateTime'] = $sellDate->addMonths(12)->addDays(1)->format('Y-m-d') . ' ~ ' . $sellDate->addMonths(18)->format('Y-m-d');
                    $data['nextCheckKm'] = numberFormat(KMCheckStep::THREE + 1) . ' ~ ' . numberFormat(KMCheckStep::FOUR);
                    break;
                case 4:
                    $data['nextCheckDateTime'] = $sellDate->addMonths(18)->addDays(1)->format('Y-m-d') . ' ~ ' . $sellDate->addMonths(27)->format('Y-m-d');
                    $data['nextCheckKm'] = numberFormat(KMCheckStep::FOUR + 1) . ' ~ ' . (KMCheckStep::FIVE);
                    break;
                case 5:
                    $data['nextCheckDateTime'] = $sellDate->addMonths(27)->addDays(1)->format('Y-m-d') . ' ~ ' . $sellDate->addMonths(36)->format('Y-m-d');
                    $data['nextCheckKm'] = numberFormat(KMCheckStep::FIVE + 1) . ' ~ ' . (KMCheckStep::SIX);
                    break;
                case 6:
                    $data['nextCheckDateTime'] = '';
                    $data['nextCheckKm'] = '';
                    break;
                default:
                    $data['nextCheckDateTime'] = '';
                    $data['nextCheckKm'] = '';
                    break;
            }
        }

        return  $data;
    }

    public function searchHistory($historyId, $orderId)
    {
        $this->historyTasks = RepairTask::with(['workContent'])
            ->where('orders_id', $orderId)
            ->get();

        $this->historyAccessories = OrderDetail::join('accessories', function ($join) {
            $join->on('accessories.id', '=', 'order_details.product_id');
            $join->whereNull('accessories.deleted_at');
        })
            ->select('order_details.*', 'accessories.code as accessorie_code', 'accessories.name as accessorie_name')
            ->where('order_details.order_id', $orderId)
            ->whereNull('order_details.deleted_at')
            ->get();
    }

    public function onChangeCustomerPhone ()
    {

    }
}
