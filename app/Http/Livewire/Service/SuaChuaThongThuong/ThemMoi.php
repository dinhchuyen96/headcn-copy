<?php

namespace App\Http\Livewire\Service\SuaChuaThongThuong;

use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Enum\EUserPosition;
use App\Enum\EMotorbike;
use App\Enum\ERepairTask;
use Livewire\Component;
use App\Models\User;
use App\Models\Customer;
use App\Models\Motorbike;
use App\Models\Order;
use App\Models\Province;
use App\Models\RepairBill;
use App\Models\RepairTask;
use App\Models\Accessory;
use App\Models\District;
use App\Enum\EWorkContent;
use App\Models\HMSReceivePlan;
use App\Models\ServiceType;
use App\Models\OrderDetail;
use App\Models\Supplier;
use App\Models\WorkContent;
use App\Models\Periodic;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use App\Service\Community;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ThemMoi extends Component
{
    // Tìm kiếm
    public $chassicNoSearch = ''; // Số khung
    public $engineNoSearch = ''; // Số máy
    public $customerPhoneSearch; // Số điện thoại KH
    public $numberMotorSearch; // Biển số

    // Thông tin sửa chữa
    public $serviceRequest; // Triệu chứng/Yêu cầu KT
    public $contentSuggest; // Tư vấn sửa chữa
    public $beforeRepair = false; // Trước sửa chữa
    public $afterRepair = false; // Sau sửa chữa
    public $notNeedWash = false; // Không cần rửa xe

    public $serviceType; // Loại sửa chữa
    public $serviceRequestCode; // Mã SR
    public $serviceUserCheckId; // Người kiểm tra
    public $serviceUserId; // Người tiếp nhận
    public $serviceFixerId; // người sửa chữa chính
    public $exportWarehouseId; // người xuất kho
    public $resultRepair; // Ghi chú sau KT
    public $checkService = [];
    public $repairNoteHistory = [];
    //Thông tin xe
    public $motorbikeId;
    public $modelName;
    public $km;
    public $buyDate;
    public $chassicNo;
    public $engineNo;
    public $numberMotor;

    // // Thông tin khách hàng
    public $customerId;
    public $customerName;
    public $customerPhone;
    public $customerAddress;
    public $customerCity;
    public $customerDistrict;
    public $customerSex;
    public $customerPoint;
    //Dữ liệu data select
    public $inspectionStaffs;
    public $technicalStaffs;
    public $serviceTypeList;
    public $sexList;
    public $listFixer;
    public $listExporter;

    // Trạng thái trang
    public $isCreate = false;
    public $isDisableAccesory, $isDisableTask = false;
    // Đối tượng update
    public $work_status_update = 1;
    public $customer;
    public $motobike;
    protected $listeners = [
        'search' => 'search',
        'disableButtonParentAccesory',
        'enableButtonParentAccesory',
        'disableButtonParentTask',
        'enableButtonParentTask',
        'setbuyDate',
        'searchHistory'
    ];

    public $showViewHistoryBtn = false;
    public $historyTasks = [];
    public $historyAccessories = [];

    public function setbuyDate($time)
    {
        $this->buyDate = date('Y-m-d', strtotime($time['buyDate']));
    }
    public function mount()
    {
        $this->serviceRequestCode = 'SR-' . Carbon::now()->format('YmdHisu');
        $this->deleteDraftRepairTask();
        $this->deleteDraftRepairAccessory();
        $this->serviceTypeList = ServiceType::select('id', 'name')->get();
        $this->technicalStaffs = User::whereHas(
            'roles',
            function ($q) {
                $q->where('id', EUserPosition::NV_KI_THUAT);
            }
        )->get();
        $this->inspectionStaffs = User::whereIn('positions', [EUserPosition::NV_KI_THUAT, EUserPosition::NV_KIEM_TRA, EUserPosition::NV_SUA_CHUA])->get();
        $this->listFixer = User::whereIn('positions', [EUserPosition::NV_KI_THUAT, EUserPosition::NV_KIEM_TRA, EUserPosition::NV_SUA_CHUA])->get();
        $this->listExporter = User::whereIn('positions', [EUserPosition::NV_KIEM_KHO])->get();

        $this->sexList = collect([
            [
                'id' => 1,
                'name' => 'Nam'
            ],
            [
                'id' => 2,
                'name' => 'Nữ'
            ]
        ]);
    }
    public function render()
    {
        $districts = $engineNoList = collect([]);
        $provinces = Province::select('province_code', 'name')->orderBy('name')->get();
        if ($this->customerCity) {
            $districts = District::where('province_code', $this->customerCity)->select('district_code', 'name')->orderBy('name')->get();
        }
        $historyData = [];
        if ($this->motorbikeId) {
            $repairBill = RepairBill::where('motorbikes_id', $this->motorbikeId)
                ->with(['order', 'serviceUser', 'user'])
                ->orderBy('in_factory_date', 'desc')
                ->get();
            $periodics = Periodic::where('motorbikes_id', $this->motorbikeId)
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


        $this->updateUI();
    
        return view('livewire.service.suachuathongthuong.themmoi', compact('provinces', 'districts', 'historyData'));
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
        $this->dispatchBrowserEvent('setDateForDatePicker');
    }
    public function search()
    {
        $this->loadInfoMotobikes();
        $this->emit('loadListInput');
    }
    public function deleteDraftRepairTask()
    {
        RepairTask::where('status', ERepairTask::DRAFT)->where('admin_id', auth()->id())->forceDelete();
    }
    public function deleteDraftRepairAccessory()
    {
        OrderDetail::where('order_id', null)
            ->where('status', EOrderDetail::STATUS_SAVE_DRAFT)
            ->where('admin_id', auth()->id())->forceDelete();
    }
    public function loadInfoMotobikes()
    {
        $query = Motorbike::whereNotNull('customer_id')->where('status', EMotorbike::SOLD)->with(['periodics', 'customer']);
        $queryHmsReceivePlan = HMSReceivePlan::select('chassic_no', 'engine_no', 'model_name');
        if (!$this->chassicNoSearch && !$this->engineNoSearch && !$this->customerPhoneSearch && !$this->numberMotorSearch) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Hãy nhập các thông tin xe để tìm kiếm']);
            return;
        }
        if ($this->chassicNoSearch) {
            $query = $query->where('chassic_no', $this->chassicNoSearch);
            
            $queryHmsReceivePlan = $queryHmsReceivePlan->where('chassic_no', $this->chassicNoSearch);
        }
        if ($this->engineNoSearch) {
            $query = $query->where('engine_no', $this->engineNoSearch);
            $queryHmsReceivePlan = $queryHmsReceivePlan->where('engine_no', $this->engineNoSearch);
        }
        if ($this->customerPhoneSearch) {
            $phone = $this->customerPhoneSearch;
            $query = $query->whereHas('customer', function ($q) use ($phone) {
                $q->where('phone', $phone);
            });
        }
        if ($this->numberMotorSearch) {
            $query = $query->where('motor_numbers', $this->numberMotorSearch);
        }
        $motorbikeInfo = $query->get()->first();
        $motorbikeFromHMS = $queryHmsReceivePlan->get()->first();

        if (!$motorbikeInfo) {
            $this->dispatchBrowserEvent('showAskCreate');
            $this->resetInputFields();
            $this->serviceRequestCode = 'SR-' . Carbon::now()->format('YmdHisu');
            return;
        }
        if (!$motorbikeInfo->customer) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Thông tin khách hàng mua xe không tồn tại']);
            return;
        }
        

        $this->isCreate = false;
        $this->showViewHistoryBtn = true;
        $this->customerId = $motorbikeInfo->customer->id;
        $this->motorbikeId = $motorbikeInfo->id;

        $this->chassicNo = $motorbikeInfo->chassic_no;
        $this->engineNo = $motorbikeInfo->engine_no;
        $this->modelName = empty($motorbikeFromHMS) ? '' : $motorbikeFromHMS->model_name;
        $this->customerName = $motorbikeInfo->customer->name ?? '';
        $this->customerAddress = $motorbikeInfo->customer->address ?? '';
        $this->customerPhone = $motorbikeInfo->customer->phone ?? '';
        $this->customerCity = $motorbikeInfo->customer->city ?? '';
        $this->customerDistrict = $motorbikeInfo->customer->district ?? '';
        $this->customerSex = $motorbikeInfo->customer->sex ?? '';
        $this->customerPoint = $motorbikeInfo->customer->point;
        $this->numberMotor = $motorbikeInfo->motor_numbers ?? '';
        $this->buyDate = date('Y-m-d', strtotime($motorbikeInfo->sell_date ?? $motorbikeInfo->buy_date));

    

        $this->emit('loadMotobikeInfo', ['motorbikeId' => $this->motorbikeId]);
    }
    public function resetInputFields()
    {
        $this->chassicNoSearch = '';
        $this->engineNoSearch = '';
        $this->customerPhoneSearch = '';
        $this->numberMotorSearch = '';
        $this->chassicNo = '';
        $this->engineNo = '';
        $this->serviceRequest = '';
        $this->serviceType = '';
        $this->serviceRequestCode = '';
        $this->serviceUserCheckId = '';
        $this->serviceUserId = '';
        $this->serviceFixerId = '';
        $this->km = '';
        $this->customerName = '';
        $this->customerAddress = '';
        $this->customerPhone = '';
        $this->customerCity = '';
        $this->customerDistrict = '';
        $this->customerSex = '';
        $this->numberMotor = '';
        $this->buyDate = '';
        $this->servicFixerId = '';
        $this->exportWarehouseId = '';
        $this->checkService = [];
        $this->resultRepair = '';

        $this->contentSuggest = '';
        $this->beforeRepair = false;
        $this->afterRepair = false;
        $this->notNeedWash = false;
    }
    public function store()
    {
        // Validate các trường bắt buộc
        // $isRepair = isRepair($this->checkService);
        $this->validate([
            'chassicNo' => 'required',
            'engineNo' => 'required',
            'modelName' => 'required',
            'buyDate' => !$this->isCreate ? 'required' : '',
            'serviceRequest' =>  'required',
            'serviceType' => 'required',
            'serviceRequestCode' => 'required',
            //'serviceUserCheckId' => !$this->isCreate && $isRepair ? 'required' : '',
            'serviceUserId' => 'required',
            'customerName' => 'required',
            'customerPhone' => 'required',
            'km' => !$this->isCreate ? 'required|numeric|min:0' : '',
            //'serviceFixerId' => !$this->isCreate && $isRepair ? 'required' : '',
        ], [
            'chassicNo.required' => 'Số khung băt buộc',
            'engineNo.required' => 'Số máy bắt buộc',
            'modelName.required' => 'Loại xe bắt buộc',
            'buyDate.required' => 'Ngày mua bắt buộc',
            'serviceRequest.required' => 'Triệu chứng yêu cầu kiểm tra băt buộc nhập',
            'serviceType.required' => 'Loại sửa chữa bắt buộc',
            'serviceRequestCode.required' => 'Mã Service Request bắt buộc nhập',
            'serviceUserCheckId.required' => 'Người kiểm tra là bắt buộc',
            'serviceUserId.required' => 'Người tiếp nhận là bắt buộc',
            'customerName.required' => 'Họ tên khách hàng bắt buộc nhập',
            'customerPhone.required' => 'Số điện thoại bắt buộc nhập',
            'km.required' => 'Số km bắt buộc nhập',
            'km.numeric' => 'Số km phải là số',
            'km.min' => 'Số km phải lớn hơn 0',
            'serviceFixerId.required' => 'Người sửa chữa chính là bắt buộc'
        ]);

        if ($this->isCreate) {
            $isMotobikeExisted = Motorbike::where('chassic_no', $this->chassicNo)
                ->where('engine_no', $this->engineNo)->first();
            if ($isMotobikeExisted) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Số khung số máy đã tồn tại trong hệ thống']);
                return;
            }
            $now = DateTime::createFromFormat('U.u', microtime(true))->modify('+ 7 hour');
            $customer = Customer::where('phone', $this->customerPhone)->first();
            if ($customer) {    
                $customer->name = $this->customerName;
                $customer->phone = $this->customerPhone;
                $customer->address = $this->customerAddress;
                $customer->city = $this->customerCity;
                $customer->district = $this->customerDistrict;
                $customer->sex = empty($this->customerSex) ? null : $this->customerSex;
                $customer->save();
            } else {
                $customer = new Customer();
                $customer->code = 'CO_' . substr($now->format("ymdhisu"), 0, -3);
                $customer->name = $this->customerName;
                $customer->phone = $this->customerPhone;
                $customer->address = $this->customerAddress;
                $customer->city = $this->customerCity;
                $customer->district = $this->customerDistrict;
                $customer->sex = empty($this->customerSex) ? null : $this->customerSex;
                $customer->save();
            }

            $motobikeNew = new Motorbike();
            $motobikeNew->chassic_no = $this->chassicNo;
            $motobikeNew->engine_no = $this->engineNo;
            $motobikeNew->model_code = $this->modelName;
            $motobikeNew->sell_date =  empty($this->buyDate) ? null : $this->buyDate;
            $motobikeNew->is_out = EMotorbike::OUT;
            $motobikeNew->status = EMotorbike::SOLD;
            $motobikeNew->customer_id = $customer->id;
            $motobikeNew->motor_numbers = $this->numberMotor;
            $motobikeNew->save();

            $this->customerId = $customer->id;
            $this->motorbikeId = $motobikeNew->id;
        } else {
            $customerPhoneExisted = Customer::where('phone', $this->customerPhone)->where('id', '<>', $this->customerId)->first();
            if ($customerPhoneExisted) {
                return $this->addError('customerPhone', 'Số điện thoại này đã được sử dụng bởi người khác');
            }
        }
        $orderDetail = OrderDetail::whereNull('order_id')
            ->where('status', EOrderDetail::STATUS_SAVE_DRAFT)
            ->where('admin_id', auth()->id())
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->where('category', EOrder::CATE_REPAIR)->get();
        $isHaveItemExport = $orderDetail->contains(function ($item, $key) {
            return $item->is_atrophy == EOrderDetail::NOT_ATROPHY_ACCESSORY;
        });
        if ($isHaveItemExport && !isset($this->exportWarehouseId)) {
            return $this->addError('exportWarehouseId', 'Có phụ tùng cần xuất kho nên người xuất kho là bắt buộc');
        }

        if ($isHaveItemExport && empty($this->serviceFixerId)) {
            $this->work_status_update = 2;
        } else if (!empty($this->serviceFixerId))
        {
            $this->work_status_update = 3;
        } else {
            $this->work_status_update = 1;
        }
        try {
            DB::beginTransaction();
            // Tạo order cho phiếu sửa chữa
            $orders = new Order;
            $orders->customer_id = $this->customerId;
            $orders->category = EOrder::CATE_REPAIR;
            $orders->status = EOrder::STATUS_UNPAID;
            $orders->type = EOrder::TYPE_BANLE;
            $orders->order_type = EOrder::ORDER_TYPE_SELL;
            $orders->motorbikes_id = $this->motorbikeId;
             $orders->created_by = Auth::user()->id;
            $orders->work_status = $this->work_status_update;
            $orders->save();
            // Cập nhật lại các bản ghi lưu tạm của phụ tùng thành lưu thật

            foreach ($orderDetail as $key => $item) {
                if ($item->is_atrophy == EOrderDetail::NOT_ATROPHY_ACCESSORY) {
                    $accessory = Accessory::where('id', $item->product_id)
                        ->where('position_in_warehouse_id', $item->position_in_warehouse_id)
                        ->first();
                    if ($accessory) {
                        $accessory->quantity -= $item->quantity;
                        $accessory->save();
                    }
                }
                $item->order_id = $orders->id;
                $item->status = EOrderDetail::STATUS_SAVED;
                $item->admin_id = null;
                $item->save();
            }
            $listContent = WorkContent::select('id', 'name', 'type')->get();
            $repairTaskList = RepairTask::where('status', ERepairTask::DRAFT)
                ->where('admin_id', auth()->id())->get();
            foreach ($repairTaskList as $key => $itemRepairTask) {
                $workSelected = $listContent->first(function ($item, $key) use ($itemRepairTask) {
                    return $item->id == $itemRepairTask->work_content_id;
                });
                $isOutWorkRepair = empty($workSelected) ? false : $workSelected->type == EWorkContent::OUT;
                if ($isOutWorkRepair) {
                    $supply = Supplier::updateOrCreate([
                        'name' => $itemRepairTask->process_company,
                        'code' => $itemRepairTask->process_company,
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
                    $orderPayment->total_money = $itemRepairTask->payment;
                    $orderPayment->total_items = 0;
                    $orderPayment->category = EOrder::CATE_REPAIR;
                    $orderPayment->status = EOrder::STATUS_UNPAID;
                    $orderPayment->type = EOrder::TYPE_NHAP;
                    $orderPayment->order_type = EOrder::ORDER_TYPE_BUY;
                    $orderPayment->motorbikes_id = $this->motorbikeId;
                    $orderPayment->created_by = Auth::user()->id;
                    $orderPayment->supplier_id = $supply->id;
                    $orderPayment->save();
                    $itemRepairTask->supply_id = $supply->id;
                    $itemRepairTask->order_payment_id = $orderPayment->id;
                }
                $itemRepairTask->status = ERepairTask::SAVED;
                $itemRepairTask->orders_id = $orders->id;
                $itemRepairTask->save();
            }

            // Tính toán tổng tiền
            $orders->total_items = $orders->totalItem();
            $orders->total_money = $orders->totalPriceForGeneralRepair();
            $orders->save();

            // Lưu phiếu sửa chữa
            $repairBill = new RepairBill;
            $repairBill->service_user_id = $this->serviceUserId;
            $repairBill->service_user_check_id = empty($this->serviceUserCheckId) ? null : $this->serviceUserCheckId;
            $repairBill->content_request = $this->serviceRequest;
            $repairBill->code_request = $this->serviceRequestCode;
            $repairBill->service_type = $this->serviceType;
            $repairBill->km = $this->km;
            $repairBill->orders_id = $orders->id;
            $repairBill->motorbikes_id = $this->motorbikeId;
            $repairBill->id_fixer_main = empty($this->serviceFixerId) ? null : $this->serviceFixerId;
            $repairBill->id_export_warehouse = empty($this->exportWarehouseId) ? null : $this->exportWarehouseId;
            $repairBill->check_service = implode(",", $this->checkService);
            $repairBill->result_repair = $this->resultRepair;

            $repairBill->content_suggest = $this->contentSuggest;
            $repairBill->before_repair = $this->beforeRepair;
            $repairBill->after_repair = $this->afterRepair;
            $repairBill->not_need_wash = $this->notNeedWash;
            
            $repairBill->save();

            Customer::find($this->customerId)->update([
                'name' => $this->customerName,
                'phone' => $this->customerPhone,
                'sex' => empty($this->customerSex) ? null : $this->customerSex,
                'address' => $this->customerAddress,
                'city' => $this->customerCity,
                'district' => $this->customerDistrict,
                // 'updated_at' => Carbon::now()
            ]);
            Motorbike::find($this->motorbikeId)->update([
                'motor_numbers' => $this->numberMotor,
                'model_code' => $this->modelName
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Tạo phiếu sửa chữa thông thường thất bại']);
            return;
        }
        $this->emit('loadListInput');
        $this->resetInputFields();
        $this->emit('loadMotobikeInfo', ['motorbikeId' => null]);
        $this->dispatchBrowserEvent('confirmPrintPdf', ['urlPrintf' => route('dichvu.dsdonhang.print', ['id' => $orders->id])]);
    }
    public function disableButtonParentAccesory()
    {
        $this->isDisableAccesory = true;
    }
    public function enableButtonParentAccesory()
    {
        $this->isDisableAccesory = false;
    }
    public function disableButtonParentTask()
    {
        $this->isDisableTask = true;
    }
    public function enableButtonParentTask()
    {
        $this->isDisableTask = false;
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
}
