<?php

namespace App\Http\Livewire\Motorbike;

use App\Http\Livewire\Base\BaseLive;
use Livewire\Component;
use App\Models\Motorbike;
use App\Models\Warehouse;
use App\Models\User;
use App\Enum\EWarehouse;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BanBuonMotorbikeImport;
use DateTime;
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Enum\EMotorbike;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\API\SmsGatewayController;

class BanBuonMotorbike extends BaseLive
{
    public $name;
    public $code;
    public $phone;
    public $email;
    public $address;
    public $district_id;
    public $province_id;
    public $ward_id;
    public $order_id;
    public $status = false;
    public $statusOrder;
    public $autoFill = false;
    public $addBtn = true;
    public $file;
    public $error = null;
    public $barCode;
    public $isVirtual = false;
    public $users = [];
    public $sellerId, $technicalId;
    protected $listeners = [
        'setBtnAddStatus',
        'setAddress',
        'addBarCode',
        'updatedCustomeName'
    ];


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
            $this->statusOrder = $order->status;
            $this->isVirtual = $order->isvirtual == 1;
            $this->sellerId = $order->seller;
            $this->technicalId = $order->assembler;
            if ($customer_id) {
                $customer = Customer::find($customer_id);
                $this->email = $customer->email;
                $this->phone = $customer->phone;
                $this->code = $customer->code;
                $this->name = $customer->name;
                $this->address = $customer->address;
                $this->district_id = $customer->district;
                $this->province_id = $customer->city;
                $this->ward_id = $customer->ward;
                $this->warehouse = Order::find($_GET['id'])->warehouse_id;
            }
        }

    }

    public function render()
    {
        $this->updateUI();
        return view('livewire.motorbike.ban-buon-motorbike');
    }
    public function updateUI()
    {

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
            $this->address = $customer->address;
            $this->district_id = $customer->district;
            $this->province_id = $customer->city;
            $this->ward_id = $customer->ward;
            if ($this->province_id || $this->district_id || $this->ward_id || $this->address) {
                $this->emit('fillAddress', $this->province_id, $this->district_id, $this->ward_id, $this->address);
                // $this->statusInputCode = false;
            }
        } else {
            $this->autoFill = false;
        }
    }

    public function updatedCustomeName()
    {
        $customer = Customer::where('name', $this->name)->get()->first();
        if ($customer) {
            $this->autoFill = true;
            $this->email = $customer->email;
            $this->code = $customer->code;
            $this->phone = $customer->phone;
            $this->address = $customer->address;
            $this->district_id = $customer->district;
            $this->province_id = $customer->city;
            $this->ward_id = $customer->ward;
            if ($this->province_id || $this->district_id || $this->ward_id || $this->address) {
                $this->emit('abc', $this->province_id, $this->district_id, $this->ward_id, $this->address);
                // $this->statusInputCode = false;
            }
        } else {
            $this->autoFill = false;
        }
    }

    public function updatedCode()
    {
        $customer = Customer::where('code', $this->code)->get()->first();
        if ($customer) {
            $this->autoFill = true;
            $this->email = $customer->email;
            $this->phone = $customer->phone;
            $this->name = $customer->name;
            $this->address = $customer->address;
            $this->district_id = $customer->district;
            $this->province_id = $customer->city;
            $this->ward_id = $customer->ward;
            if ($this->province_id || $this->district_id || $this->ward_id || $this->address) {
                $this->emit('fillAddress', $this->province_id, $this->district_id, $this->ward_id, $this->address);
                // $this->statusInputCode = false;
            }
        } else {
            $this->autoFill = false;
        }
    }

    public function store()
    {
        $detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('type', EOrderDetail::TYPE_BANBUON)->where('category', EOrderDetail::CATE_MOTORBIKE)->where('admin_id', auth()->id())->get()->toArray();

        $this->validate([
            'phone' => 'required',
            'name' => 'required',
        ], [], [
            'phone' => 'Số điện thoại',
            'name' => 'Tên khách hàng',
            'email' => 'Email',
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
            ->where('type', EOrderDetail::TYPE_BANBUON)
            ->where('category', EOrderDetail::CATE_MOTORBIKE)
            ->where('admin_id', auth()->id())->get();
        foreach ($order_detail as $item) {
            $motorbike =  Motorbike::where('chassic_no', $item->chassic_no)
                ->where('engine_no', $item->engine_no)
                ->where('is_out', EMotorbike::NOT_OUT)
                ->get()->first();
            if ($motorbike->warehouse_id == $warehouseGS->id) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Xe máy (' . $item->chassic_no . '|' . $item->engine_no . ') đang ở trong kho GS nên không thể bán. Hãy chuyển từ kho GS về']);
                return;
            }
        }
        $customer = Customer::updateOrCreate([
            'phone' => $this->phone,
        ], [
            'code' => $this->code ?: 'CO_' . substr($now->format("ymdhisu"), 0, -3),
            'name' => $this->name,
            'email' => $this->email,
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
        $order->type = EOrder::TYPE_BANBUON;
        $order->status = $this->statusOrder ?: EOrder::STATUS_UNPAID;
        $order->customer_id = $customer->id;
        $order->isvirtual = $this->isVirtual;
        $order->seller = $this->sellerId ?: null;
        $order->assembler = $this->technicalId ?: null;
        $order->save();

        $order_detail = OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)
            ->where('type', EOrderDetail::TYPE_BANBUON)
            ->where('category', EOrderDetail::CATE_MOTORBIKE)
            ->where('admin_id', auth()->id())->get();
        OrderDetail::where('status', EOrderDetail::STATUS_SAVE_DRAFT)->where('type', EOrderDetail::TYPE_BANBUON)->where('category', EOrderDetail::CATE_MOTORBIKE)->where('admin_id', auth()->id())->update([
            'status' => EOrderDetail::STATUS_SAVED,
            'order_id' => $order->id,
        ]);
        foreach ($order_detail as $item) {
            $motorbike = Motorbike::where('chassic_no', $item->chassic_no)->where('engine_no', $item->engine_no)->where('is_out', EMotorbike::NOT_OUT)->get()->first();
            if (!$this->isVirtual) {
                // Nếu không phải ảo sẽ bán bình thường
                $motorbike->customer_id = $customer->id;
                $motorbike->customer_phone = $customer->phone;
                $motorbike->sell_date = date('Y-m-d');
                $motorbike->status = EMotorbike::SOLD;
                $motorbike->updated_at = Carbon::now();
            } else {
                // Chuyển xe máy sang kho GS nếu là ảo
                $motorbike->status = EMotorbike::VITUAL;
                $motorbike->warehouse_id = $warehouseGS->id;
                $motorbike->updated_at = Carbon::now();
            }
            $motorbike->save();
            OrderDetail::where('chassic_no', $item->chassic_no)->where('engine_no', $item->engine_no)->update(['product_id' => $motorbike->id]);
        }
        $order->update([
            'total_items' => $order->details->count(),
            'total_money' => OrderDetail::where('order_id', $order->id)->sum('actual_price'),
            'order_no' => 'ORDER_' . $order->id,
        ]);

        if ($this->order_id) {
            $this->emit('loadListInput');
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhật thành công']);
        } else {
            $this->emit('loadListInput');
            $this->resetData();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Tạo mới thành công']);

            if (!$this->isVirtual) {
                // Gửi tin nhắn cảm ơn mua xe
                $request = new Request();
                $request->smsid = env('SMS_ID_BAN_BUON_MOTORBIKE', 205085);
                $request->customerPhone = $customer->phone;
                $api = new SmsGatewayController();
                $request->param = env('HEAD_NAME');
                $result = $api->sendToCustomer($request);
                if ($result == 1) {
                    $customer->is_sent_thank_you = 1;
                    $customer->last_datetime_sent_thank_you = Carbon::now();
                    $customer->save();
                }
            }
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
        $this->sellerId = '';
        $this->technicalId = '';
        $this->emit('resetAddress');
    }

    public function add()
    {
        $this->addBtn = false;
        $this->emit('addNew');
    }

    public function setBtnAddStatus()
    {
        $this->addBtn = true;
    }

    public function import()
    {

        $this->validate([
            'file' => 'required'
        ], [
            'file.required' => 'Hãy chọn file để import',
        ]);
        try {
            Excel::import(new BanBuonMotorbikeImport, $this->file);
            if (session()->has('error')) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => session()->get('error')]);
                session()->pull('error');
                return;
            }
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Import thành công']);
            $this->emit('close-modal-import');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $ar = [];
            foreach ($failures as $failure) {
                $ar[] = $failure->errors()[0];
            }
            $ar = array_unique($ar);
            foreach ($ar as $item) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => $item]);
            }
        }
    }

    public function downloadExample()
    {
        return Storage::disk('public')->download('mau_file_ban_buon_xe_may.xlsx');
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
