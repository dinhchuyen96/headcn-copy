<?php

namespace App\Http\Livewire\Service\SuaChuaThongThuong;

use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Enum\EMotorbike;
use App\Enum\ERepairTask;
use App\Enum\EUserPosition;
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
use App\Models\HMSReceivePlan;
use App\Models\ServiceType;
use App\Models\OrderDetail;
use App\Models\Periodic;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use App\Service\Community;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Sua extends Component
{
    // Id
    public $orderId;

    // Xe ngoài
    public $isOut;
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
    public $serviceFixerId; // Người sửa chữa chính
    public $exportWarehouseId; // Người xuất kho
    public $resultRepair; // Ghi chú sau KT
    public $accessories = [];
    public $checkService = [];
    public $repairNoteHistory = [];
    //Thông tin xe
    public $motorbikeId;
    public $km;
    public $checkDate;
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
    // Event cập nhật từ componient

    public $isDisableAccesory, $isDisableTask = false;


    // Đối tượng update
    public $work_status_update = 1;
    public $customer;
    public $motobike;

    protected $listeners = [
        'totalPriceRepair',
        'totalPriceAccessory',
        'disableButtonParentAccesory', 'enableButtonParentAccesory', 'disableButtonParentTask', 'enableButtonParentTask',
        'setCheckDate'
    ];

    public function totalPriceRepair($totalPriceRepair)
    {
    }
    public function totalPriceAccessory($totalPriceAccessory)
    {
    }

   
    public function setCheckDate($time)
    {
        $this->checkDate = date('Y-m-d', strtotime($time['check_date']));
    }

    public function mount()
    {
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
        $this->loadInfoMotobikes();
    }


    public function render()
    {
        $districts = $engineNoList = collect([]);
        $provinces = Province::select('province_code', 'name')->orderBy('name')->get();
        if ($this->customerCity) {
            $districts = District::where('province_code', $this->customerCity)->select('district_code', 'name')->orderBy('name')->get();
        } else {
            $districts = District::select('district_code', 'name')->orderBy('name')->get();
        }

        $this->updateUI();
        return view('livewire.service.suachuathongthuong.sua', compact('provinces', 'districts'));
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
        $this->dispatchBrowserEvent('setTranferDatePicker');
    }

    public function deleteDraftRepairTask()
    {
        RepairTask::where('status', ERepairTask::DRAFT)->where('admin_id', auth()->id())->forceDelete();
    }
    public function deleteDraftRepairAccessory()
    {
        OrderDetail::where('order_id', $this->orderId)
            ->where('status', EOrderDetail::STATUS_SAVE_DRAFT)
            ->where('admin_id', auth()->id())->forceDelete();
    }
    public function loadInfoMotobikes()
    {
        if ($this->orderId) {
            $order = Order::where('id', $this->orderId)->where('category', EOrder::CATE_REPAIR)
                ->where('type', EOrder::TYPE_BANLE)
                ->where('order_type', EOrder::ORDER_TYPE_SELL)->first();
            if (!$order) {
                return;
            }
            $motorbikeInfo = Motorbike::whereNotNull('customer_id')
                ->where('status', EMotorbike::SOLD)->with(['periodics', 'customer'])
                ->where('id', $order->repairBill->motorbikes_id)
                ->get()->first();
            if (!$motorbikeInfo) {
                return;
            }
            $this->customerId = $motorbikeInfo->customer->id;
            $this->customerPoint = $motorbikeInfo->customer->point;
            $this->motorbikeId = $motorbikeInfo->id;

            $this->serviceRequest = $order->repairBill->content_request;

            $this->contentSuggest = $order->repairBill->content_suggest;
            $this->beforeRepair = $order->repairBill->before_repair == 1;
            $this->afterRepair = $order->repairBill->after_repair == 1;
            $this->notNeedWash = $order->repairBill->not_need_wash == 1;

            $this->resultRepair = $order->repairBill->result_repair;
            $this->serviceType = $order->repairBill->service_type;
            $this->serviceRequestCode = $order->repairBill->code_request;
            $this->serviceUserCheckId = $order->repairBill->service_user_check_id;
            $this->serviceUserId = $order->repairBill->service_user_id;
            $this->serviceFixerId = $order->repairBill->id_fixer_main;
            $this->exportWarehouseId = $order->repairBill->id_export_warehouse;
            $this->checkService = explode(",", $order->repairBill->check_service);
            $this->chassicNo = $motorbikeInfo->chassic_no;
            $this->engineNo = $motorbikeInfo->engine_no;
            $this->buyDate = date('Y-m-d', strtotime($motorbikeInfo->sell_date ?? $motorbikeInfo->buy_date));
            $this->numberMotor = $motorbikeInfo->motor_numbers ?? '';
            $this->km = $order->repairBill->km;
            $this->checkDate = date('Y-m-d', strtotime($order->repairBill->created_at));

            $this->customerName = $motorbikeInfo->customer->name ?? '';
            $this->customerAddress = $motorbikeInfo->customer->address ?? '';
            $this->customerPhone = $motorbikeInfo->customer->phone ?? '';
            $this->customerCity = $motorbikeInfo->customer->city ?? '';
            $this->customerDistrict = $motorbikeInfo->customer->district ?? '';
            $this->customerSex = $motorbikeInfo->customer->sex ?? '';

            $this->isOut = $motorbikeInfo->is_out == EMotorbike::OUT;

            $this->repairNoteHistory = RepairBill::where('motorbikes_id', $motorbikeInfo->id)->select(['id', 'in_factory_date', 'result_repair'])->orderBy('in_factory_date', 'desc')->get();
        }
    }
    public function resetInputFields()
    {
        $this->chassicNo = '';
        $this->engineNo = '';
        $this->serviceRequest = '';
        $this->serviceType = '';
        $this->serviceRequestCode = '';
        $this->serviceUserCheckId = '';
        $this->serviceUserId = '';
        $this->km = '';
        $this->customerName = '';
        $this->customerAddress = '';
        $this->customerPhone = '';
        $this->customerCity = '';
        $this->customerDistrict = '';
        $this->customerSex = '';
        $this->numberMotorSearch = '';
        $this->buyDate = '';
        $this->resultRepair = '';
    }

    public function update()
    {
 
        // Validate các trường bắt buộc
        $isRepair = isRepair($this->checkService);
        $this->validate([
            'chassicNo' => 'required',
            'engineNo' => 'required',
            //'buyDate' => 'required',
            'serviceRequest' => 'required',
            'serviceType' => 'required',
            'serviceRequestCode' => 'required',
            'serviceUserCheckId' => $isRepair ? 'required' : '',
            'serviceUserId' => 'required',
            'customerName' => 'required',
            'customerPhone' => 'required',
            'serviceFixerId' => $isRepair ? 'required' : '',
        ], [
            'chassicNo.required' => 'Số khung băt buộc',
            'engineNo.required' => 'Số máy bắt buộc',
            'buyDate.required' => 'Ngày mua bắt buộc',
            'serviceRequest.required' => 'Triệu chứng yêu cầu kiểm tra băt buộc nhập',
            'serviceType.required' => 'Loại sửa chữa bắt buộc',
            'serviceRequestCode.required' => 'Mã Service Request bắt buộc nhập',
            'serviceUserCheckId.required' => 'Người kiểm tra là bắt buộc',
            'serviceUserId.required' => 'Người tiếp nhận là bắt buộc',
            'customerName.required' => 'Họ tên khách hàng bắt buộc nhập',
            'customerPhone.required' => 'Số điện thoại bắt buộc nhập',
            'serviceFixerId.required' => 'Người sửa chữa chính là bắt buộc'
        ]);
        $customerPhone = Customer::where('phone', $this->customerPhone)->where('id', '<>', $this->customerId)->first();
        if ($customerPhone) {
            return $this->addError('customerPhone', 'Số điện thoại này đã được sử dụng bởi người khác');
        }

        $orderDetail = OrderDetail::where('id', $this->orderId)
            ->where('status', EOrderDetail::STATUS_SAVE_DRAFT)
            ->where('admin_id', auth()->id())
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->where('category', EOrder::CATE_REPAIR)->get();
        $isHaveItemExport = $orderDetail->contains(function ($item, $key) {
            return $item->is_atrophy == EOrderDetail::NOT_ATROPHY_ACCESSORY;
        });
        $orders = Order::where('id', $this->orderId)->first();
        if ($isHaveItemExport && !isset($this->exportWarehouseId)) {
            return $this->addError('exportWarehouseId', 'Có phụ tùng cần xuất kho nên người xuất kho là bắt buộc');
        }
        $accessoryList = OrderDetail::with(['accessorie', 'accessorie.supplier', 'order', 'positioninwarehouse'])
                ->where('order_id', $this->orderId)
                ->where('type', EOrderDetail::TYPE_BANLE)
                ->get();
        foreach ($accessoryList as $key => $accessory) {
            $this->accessories[$key + 1] = $key + 1;
        }
        if ($this->accessories && empty($this->serviceFixerId)) {
            $this->work_status_update = 2;
        } else if (!empty($this->serviceFixerId))
        {
            $this->work_status_update = 3;
        } else {
            $this->work_status_update = 1;
        }
        try {
            DB::beginTransaction();
            // Cập nhật lại các bản ghi lưu tạm của phụ tùng thành lưu thật
            foreach ($orderDetail as $key => $item) {
                $accessory = Accessory::where('id', $item->product_id)
                    ->where('position_in_warehouse_id', $item->position_in_warehouse_id)
                    ->first();
                if ($accessory) {
                    $accessory->quantity -= $item->quantity;
                    $accessory->save();
                }
                $item->order_id = $this->orderId;
                $item->status = EOrderDetail::STATUS_SAVED;
                $item->created_at = Carbon::createFromFormat('Y-m-d', $this->checkDate);
                $item->save();
            }
            RepairTask::where('status', ERepairTask::DRAFT)
                ->where('admin_id', auth()->id())
                ->update([
                    'status' => ERepairTask::SAVED,
                    'orders_id' =>  $this->orderId,
                    'created_at' => Carbon::createFromFormat('Y-m-d', $this->checkDate)
                ]);
            $orders->total_items = $orders->totalItem();
            $orders->total_money = $orders->totalPriceForGeneralRepair();
            $orders->work_status = $this->work_status_update;
            $orders->created_at = Carbon::createFromFormat('Y-m-d', $this->checkDate);
            $orders->save();

            // Lưu phiếu sửa chữa
            $repairBill = $orders->repairBill;
            $repairBill->service_user_id = $this->serviceUserId;
            $repairBill->service_user_check_id = empty($this->serviceUserCheckId) ? null : $this->serviceUserCheckId;
            $repairBill->content_request = $this->serviceRequest;
            $repairBill->code_request = $this->serviceRequestCode;
            $repairBill->service_type = $this->serviceType;
            $repairBill->result_repair = $this->resultRepair;
            $repairBill->km = $this->km;
            $repairBill->orders_id = $orders->id;
            $repairBill->motorbikes_id = $this->motorbikeId;
            $repairBill->id_fixer_main = empty($this->serviceFixerId) ? null : $this->serviceFixerId;
            $repairBill->id_export_warehouse = $this->exportWarehouseId;
            $this->checkService = array_filter($this->checkService, fn ($value) => !is_null($value) && $value !== '');
            $repairBill->check_service = implode(",", $this->checkService);
            $repairBill->content_suggest = $this->contentSuggest;
            $repairBill->before_repair = $this->beforeRepair;
            $repairBill->after_repair = $this->afterRepair;
            $repairBill->not_need_wash = $this->notNeedWash;
            $repairBill->created_at = Carbon::createFromFormat('Y-m-d', $this->checkDate);
            $repairBill->in_factory_date = Carbon::createFromFormat('Y-m-d', $this->checkDate);
            $repairBill->save();

            Customer::find($this->customerId)->update([
                'name' => $this->customerName,
                'phone' => $this->customerPhone,
                'sex' => empty($this->customerSex) ? null : $this->customerSex,
                'address' => $this->customerAddress,
                'city' => $this->customerCity,
                'district' => $this->customerDistrict
            ]);
            if ($this->isOut) {
                $isMotobikeExisted = Motorbike::where('chassic_no', $this->chassicNo)
                    ->where('engine_no', $this->engineNo)
                    ->where('id', '<>', $this->motorbikeId)
                    ->first();
                if ($isMotobikeExisted) {
                    $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Số khung số máy đã tồn tại trong hệ thống']);
                    return;
                }
                Motorbike::find($this->motorbikeId)->update([
                    'chassic_no' => $this->chassicNo,
                    'engine_no' => $this->engineNo,
                    'sell_date' => $this->buyDate
                ]);
            }
            Motorbike::find($this->motorbikeId)->update([
                'motor_numbers' => $this->numberMotor
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Cập nhật phiếu sửa chữa thông thường thất bại']);
            return;
        }


        $this->emit('loadListInput');
        $this->dispatchBrowserEvent('confirmPrintPdf', ['urlPrintf' => route('dichvu.dsdonhang.print', ['id' => $orders->id])]);
        //$this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhật phiếu sửa chữa thông thường thành công']);
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
}
