<?php

namespace App\Http\Livewire\Motorbike;

use App\Http\Livewire\Base\BaseLive;
use Livewire\Component;
use App\Models\Motorbike;
use App\Models\Warehouse;
use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Customer;
use DateTime;
use App\Models\InstallmentCompany;
use App\Models\Installment;
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Enum\EMotorbike;
use App\Enum\EWarehouse;
use App\Enum\EPaymentMethod;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\API\SmsGatewayController;

class BanLeMotorbike extends BaseLive
{

    public $name;
    public $code;
    public $phone;
    public $email;
    public $address;
    public $birthday;
    public $transactionDate;
    public $job;
    public $sex = 1;
    public $district_id;
    public $province_id;
    public $ward_id;
    public $order_id;
    public $status = false;
    public $autoFill = false;
    public $addBtn = true;
    public $statusOrder;
    public $barCode;
    public $isVirtual = false;
    public $paymentMethod = 1;
    public $installmentMoney;
    public $contractCode;
    public $installmentCompanyList = [];
    public $installmentCompany;
    public $users = [];
    public $sellerId, $technicalId;
    protected $listeners = [
        'setBtnAddStatus',
        'addBarCode',
        'settransactionDate',
        'setbirthdayDate'
    ];
    public function settransactionDate($time)
    {
        $this->transactionDate = date('Y-m-d', strtotime($time['transactionDate']));
    }

    public function setbirthdayDate($time)
    {
        $this->birthday = date('Y-m-d', strtotime($time['birthdayDate']));
    }
    public function mount()
    {
        $this->users = User::select(['id', 'name'])->get();
        if (isset($_GET['show'])) {
            $this->status = true;
        }

        if (isset($_GET['id'])) {
            $this->order_id = $_GET['id'];
            $order = Order::find($_GET['id']);
            $customer_id = $order->customer_id;
            $this->isVirtual = $order->isvirtual == 1;
            $this->statusOrder = $order->status;
            $this->sellerId = $order->seller;
            $this->technicalId = $order->assembler;
            if ($customer_id) {
                $customer = Customer::find($customer_id);
                if ($customer) {
                    $this->email = $customer->email;
                    $this->phone = $customer->phone;
                    $this->code = $customer->code;
                    $this->name = $customer->name;
                    $this->birthday = $customer->birthday;
                    $this->job = $customer->job;
                    $this->sex = $customer->sex;
                    $this->address = $customer->address;
                    $this->district_id = $customer->district;
                    $this->province_id = $customer->city;
                    $this->ward_id = $customer->ward;
                }
            }
            //$this->paymentMethod = $order->payment_method;
            // if ($order->installment && $order->payment_method == EPaymentMethod::INSTALLMENT) {
            //     $this->contractCode = $order->installment->contract_number;
            //     $this->installmentCompany = $order->installment->installment_company_id;
            //     $this->installmentMoney = $order->installment->money;
            // }
            //$this->warehouse = $order->warehouse_id;
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


        $this->installmentCompanyList = InstallmentCompany::select('id', 'company_name')->get();
        $this->updateUI();
        return view('livewire.motorbike.ban-le-motorbike', compact('province', 'ward', 'district'));
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setDateForDatePicker');
        $this->dispatchBrowserEvent('setSelect2');
    }
    public function updatedPhone()
    {
        $customer = Customer::where('phone', $this->phone)->get()->first();
        if ($customer) {
            $this->autoFill = true;
            $this->email = $customer->email;
            $this->code = $customer->code;
            $this->name = $customer->name;
            $this->birthday = $customer->birthday;
            $this->job = $customer->job;
            $this->sex = $customer->sex;
            $this->address = $customer->address;
            $this->district_id = $customer->district;
            $this->province_id = $customer->city;
            $this->ward_id = $customer->ward;
        } else {
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

    public function store()
    {
        $detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->where('category', EOrderDetail::CATE_MOTORBIKE)
            ->where('admin_id', auth()->id())->get()->toArray();
        $this->validate([
            'phone' => 'required',
            'name' => 'required',
            'birthday' => 'required',
            'job' => 'required',
            'address' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'ward_id' => 'required',
            'contractCode' => $this->paymentMethod == EPaymentMethod::INSTALLMENT ? 'required|unique:installment,contract_number,NULL,id,deleted_at,NULL' : '',
            'installmentCompany' => $this->paymentMethod == EPaymentMethod::INSTALLMENT ? 'required' : '',
            'installmentMoney' => $this->paymentMethod == EPaymentMethod::INSTALLMENT ? 'required|numeric|min:1' : '',

        ], [
            'phone.required' => 'Số điện thoại bắt buộc',
            'name.required' => 'Tên khách hàng bắt buộc',
            'birthday.required' => 'Ngày sinh bắt buộc',
            'job.required' => 'Nghề nghiệp bắt buộc',
            'address.required' => 'Địa chỉ bắt buộc',
            'province_id.required' => 'Thành phố/ Tỉnh bắt buộc',
            'district_id.required' => 'Quận/ Huyện bắt buộc',
            'ward_id.required' => 'Phường/ Xã bắt buộc',
            'contractCode.required' => 'Số hợp đồng bắt buộc',
            'contractCode.unique' => 'Số hợp đồng đã tồn tại',
            'installmentCompany.required' => 'Công ty tài chính bắt buộc',
            'installmentMoney.required' => 'Số tiền trả góp bắt buộc',
            'installmentMoney.numeric' => 'Số tiền trả góp phải là số',
            'installmentMoney.min' => 'Số tiền trả góp phải lớn hơn 0',
        ]);

        if (count($detail) == 0 && !$this->order_id) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Chưa có xe nào được nhập']);
            return;
        } elseif (!$this->addBtn) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Có bản nháp chưa hoàn thành']);
            return;
        }

        // tạo kho GS
        $warehouseGS = Warehouse::where('name', EWarehouse::GS)->first();
        if (!$warehouseGS) {
            $warehouseGS = new Warehouse();
            $warehouseGS->name = EWarehouse::GS;
            $warehouseGS->address = '';
            $warehouseGS->established_date = Carbon::now();
            $warehouseGS->province_id = null;
            $warehouseGS->district_id = null;
            $warehouseGS->created_at = Carbon::now();
            $warehouseGS->updated_at = Carbon::now();
            $warehouseGS->save();
        }
        $now = DateTime::createFromFormat('U.u', microtime(true))->modify('+ 7 hour');
        $order_detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->where('category', EOrderDetail::CATE_MOTORBIKE)
            ->where('admin_id', auth()->id())->get();
        if ($this->paymentMethod == EPaymentMethod::INSTALLMENT) {
            $totalMoneySale = $order_detail->sum('actual_price');
            if ($this->order_id) {
                $totalMoneySale += OrderDetail::where('order_id', $this->order_id)
                    ->where('status', EOrderDetail::STATUS_SAVED)
                    ->sum('actual_price');
            }
            if ($totalMoneySale < $this->installmentMoney) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Số tiền trả góp phải nhỏ hơn tổng giá trị đơn hàng']);
                return;
            }
        }

        foreach ($order_detail as $item) {
            $motorbike =  Motorbike::where('chassic_no', $item->chassic_no)->where('engine_no', $item->engine_no)->where('is_out', EMotorbike::NOT_OUT)->get()->first();
            if ($motorbike->warehouse_id == $warehouseGS->id) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Xe máy (' . $item->chassic_no . '|' . $item->engine_no . ') đang ở trong kho GS nên không thể bán. Hãy chuyển từ kho GS về']);
                return;
            }
        }
        $customer = Customer::updateOrCreate([
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

        if ($this->order_id) {
            $order = Order::findOrFail($this->order_id);
        } else {
            $order = new Order();
        }
        $order->created_by = auth()->id();
        $order->category = EOrder::CATE_MOTORBIKE;
        $order->order_type = EOrder::ORDER_TYPE_SELL;
        $order->type = EOrder::TYPE_BANLE;
        $order->status = $this->statusOrder ?? EOrder::STATUS_UNPAID;
        $order->customer_id = $customer->id;
        $order->isvirtual = $this->isVirtual;
        $order->payment_method = EPaymentMethod::DIRECT;
        $order->seller = $this->sellerId ?? null;
        $order->assembler = $this->technicalId ?? null;
        $order->save();

        if ($this->paymentMethod == EPaymentMethod::INSTALLMENT) {
            $orderInstallment = new Order();
            $orderInstallment->created_by = auth()->id();
            $orderInstallment->category = EOrder::CATE_MOTORBIKE;
            $orderInstallment->order_type = EOrder::ORDER_TYPE_SELL;
            $orderInstallment->type = EOrder::TYPE_BANLE;
            $orderInstallment->status = EOrder::STATUS_UNPAID;
            $orderInstallment->customer_id = $customer->id;
            $orderInstallment->isvirtual = $this->isVirtual;
            $orderInstallment->payment_method = EPaymentMethod::INSTALLMENT;
            $orderInstallment->created_at = Carbon::now();
            $orderInstallment->updated_at = Carbon::now();
            $orderInstallment->total_items = 1;
            $orderInstallment->total_money =  $this->installmentMoney;
            $orderInstallment->save();
            $installment = new Installment();
            $installment->order_id = $orderInstallment->id;
            $installment->order_relation_id = $order->id;
            $installment->contract_number = $this->contractCode;
            $installment->installment_company_id = $this->installmentCompany;
            $installment->money = $this->installmentMoney;
            $installment->created_at = Carbon::now();
            $installment->updated_at = Carbon::now();
            $installment->save();
        }

        OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->where('category', EOrderDetail::CATE_MOTORBIKE)
            ->where('admin_id', auth()->id())->update([
                'status' => EOrderDetail::STATUS_SAVED,
                'order_id' => $order->id,
            ]);
        foreach ($order_detail as $item) {
            $motorbike =  Motorbike::where('chassic_no', $item->chassic_no)
                ->where('engine_no', $item->engine_no)
                ->where('is_out', EMotorbike::NOT_OUT)->get()->first();
            if (!$this->isVirtual) {
                // Nếu không phải ảo sẽ bán bình thường
                $motorbike->customer_id = $customer->id;
                $motorbike->customer_phone = $customer->phone;
                $motorbike->status = EMotorbike::SOLD;
                $motorbike->sell_date = empty($this->transactionDate) ? date('Y-m-d') : $this->transactionDate;
                $motorbike->updated_at = Carbon::now();
            } else {
                // Chuyển xe máy sang kho GS nếu là ảo
                $motorbike->status = EMotorbike::VITUAL;
                $motorbike->warehouse_id = $warehouseGS->id;
                $motorbike->updated_at = Carbon::now();
            }
            $motorbike->save();
            OrderDetail::where('chassic_no', $item->chassic_no)
                ->where('engine_no', $item->engine_no)
                ->update(['product_id' => $motorbike->id]);
        }
        $totalMoneyOrder = OrderDetail::where('order_id', $order->id)->sum('actual_price') - ($this->paymentMethod == EPaymentMethod::INSTALLMENT ? $this->installmentMoney : 0);
        $order->update([
            'total_items' => 1,
            'total_money' => $totalMoneyOrder,
            'order_no' => 'ORDER_' . $order->id,
        ]);

        if ($this->order_id) {
            $this->emit('loadListInput');
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhật thành công']);
        } else {
            $this->emit('loadListInput');
            $this->resetData();

            //$this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Tạo mới thành công']);

            if (!$this->isVirtual) {
                // Gửi tin nhắn cảm ơn mua xe
                $request = new Request();
                $request->smsid = env('SMS_ID_BAN_LE_MOTORBIKE', 205085);
                $request->customerPhone = $customer->phone;
                $api = new SmsGatewayController();

                $request->param = env('HEAD_NAME');
                $result = $api->sendToCustomer($request);

                if ($result == 1) {
                    $customer->is_sent_thank_you = 1;
                    $customer->last_datetime_sent_thank_you = Carbon::now();
                    $customer->save();
                }
                // Gửi tin nhắn cho KTĐK lần 1
                $request->smsid = env('SMS_ID_BAO_KIEM_TRA_DINH_KY', 205083);
                $request->param = $this->name . "__" . 1 . "__" . Carbon::today()->addDays(7)->month . "__" . env("HEAD_PHONE_SUPPORT");
                $resultKTDK = $api->sendToCustomer($request);
                if ($resultKTDK == 1) {
                    $customer->is_sent_ktdk = 1;
                    $customer->last_datetime_sent_ktdk = Carbon::now();
                    $customer->save();
                }
            }
            $url = route('xemay.dichvukhac.create.index') . '?customerId=' . $customer->id;
            $this->dispatchBrowserEvent('redirectToOtherService', ['url' => $url, 'customer' => $customer->name]);
        }
    }

    public function resetData()
    {
        $this->phone = null;
        $this->name = null;
        $this->email = null;
        $this->code = null;
        $this->address = null;
        $this->district_id = null;
        $this->province_id = null;
        $this->ward_id = null;
        $this->installmentCompany = '';
        $this->paymentMethod = 1;
        $this->contractCode = '';
        $this->installmentMoney = '';
        $this->job = '';
        $this->sellerId = '';
        $this->technicalId = '';
        $this->emit('resetAddress');
        $this->emit('resetDateRangerKendo');
    }

    public function add()
    {
        if (OrderDetail::where('status', 0)->where('category', 2)->where('type', 2)->where('admin_id', auth()->id())->first()) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Chỉ được bán 1 xe máy']);
            return;
        }
        $this->addBtn = false;
        $this->emit('addNew');
    }

    public function setBtnAddStatus()
    {
        $this->addBtn = true;
    }
    public function addBarCode($code)
    {
        $this->validate([
            'barCode' => 'required'
        ], [
            'barCode.required' => 'Barcode bắt buộc phải nhập'
        ], []);
        $this->emit('addInputRow', $code);
    }
}
