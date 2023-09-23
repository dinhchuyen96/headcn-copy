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
use App\Models\Rep;
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


class Xem extends Component
{
    // Id
    public $orderId;

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
    //Thông tin xe
    public $motorbikeId;
    public $km;
    public $buyDate;
    public $chassicNo;
    public $engineNo;
    public $numberMotor;
    public $customerPoint;


    public $checkService = [];
    public $repairNoteHistory = [];

    // // Thông tin khách hàng
    public $customerId;
    public $customerName;
    public $customerPhone;
    public $customerAddress;
    public $customerCity;
    public $customerDistrict;
    public $customerSex;

    //Dữ liệu data select
    public $inspectionStaffs;
    public $technicalStaffs;
    public $serviceTypeList;
    public $sexList;
    public $listFixer;
    public $listExporter;

    public function mount()
    {
        $this->serviceTypeList = ServiceType::select('id', 'name')->get();
        $this->technicalStaffs = User::whereIn('positions', [EUserPosition::NV_KI_THUAT, EUserPosition::NV_KIEM_TRA, EUserPosition::NV_SUA_CHUA])->get();
        $this->inspectionStaffs = User::whereIn('positions', [EUserPosition::NV_KI_THUAT, EUserPosition::NV_KIEM_TRA, EUserPosition::NV_SUA_CHUA])->get();
        $this->listFixer = User::whereIn('positions', [EUserPosition::NV_KI_THUAT, EUserPosition::NV_KIEM_TRA, EUserPosition::NV_SUA_CHUA])->get();
        $this->listExporter = User::whereIn('positions', [EUserPosition::NV_KIEM_KHO])->get();
        $this->sexList = collect([['id' => 1, 'name' => 'Nam'], ['id' => 2, 'name' => 'Nữ']]);
    }

    public function render()
    {
        $provinces = Province::select('province_code', 'province_code as id', 'name')->orderBy('name')->get();
        $districts = District::select('district_code', 'district_code as id', 'name')->orderBy('name')->get();
        $this->loadInfoMotobikes();
        $this->updateUI();
        return view('livewire.service.suachuathongthuong.xem', compact('provinces', 'districts'));
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
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
            // Thông tin sửa chữa
            $this->serviceRequest = $order->repairBill->content_request ?? '';
            $this->serviceRequestCode = $order->repairBill->code_request ?? '';
            $this->serviceType = $order->repairBill->service_type;
            $this->serviceUserCheckId = $order->repairBill->service_user_check_id;
            $this->serviceUserId = $order->repairBill->service_user_id;
            $this->serviceFixerId = $order->repairBill->id_fixer_main;
            $this->exportWarehouseId = $order->repairBill->id_export_warehouse;
            $this->resultRepair = $order->repairBill->result_repair;
            $this->km = $order->repairBill->km ?? '';
            $this->chassicNo = $order->repairBill->motorbike->chassic_no;
            $this->engineNo = $order->repairBill->motorbike->engine_no;
            $this->buyDate = date('d-m-Y', strtotime($order->repairBill->motorbike->sell_date ?? $order->repairBill->motorbike->buy_date));
            $this->numberMotor = $order->repairBill->motorbike->motor_numbers ?? '';
            $this->checkService = explode(",", $order->repairBill->check_service);
            $this->customerName = $order->customer->name ?? '';
            $this->customerAddress = $order->customer->address ?? '';
            $this->customerPhone = $order->customer->phone ?? '';
            $this->customerCity = $order->customer->city ?? '';
            $this->customerDistrict = $order->customer->district ?? '';
            $this->customerSex = $order->customer->sex ?? '';
            $this->customerPoint = $order->customer->point;

            $this->contentSuggest = $order->repairBill->content_suggest ?? '';
            $this->beforeRepair = $order->repairBill->before_repair == 1;
            $this->afterRepair = $order->repairBill->after_repair == 1;
            $this->notNeedWash = $order->repairBill->not_need_wash == 1;

            $this->repairNoteHistory = RepairBill::where('motorbikes_id', $order->repairBill->motorbike->id)->select(['in_factory_date', 'result_repair'])->orderBy('in_factory_date', 'desc')->get();
        }
    }

    public function back()
    {
        return redirect()->to('/dichvu/ds-don-hang');
    }
    public function edit()
    {
        return redirect()->to('/dichvu/sua-chua-thong-thuong/sua/' . $this->orderId);
    }
}
