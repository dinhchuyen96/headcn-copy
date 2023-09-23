<?php

namespace App\Http\Livewire\Ketoan;

use App\Http\Livewire\Base\BaseLive;
use Livewire\Component;
use App\Enum\EOrder;
use App\Enum\ListServiceType;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\User;
use App\Models\Order;
use App\Models\ReturnItem;

use App\Models\ListService;
use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountMoney;
use Illuminate\Support\Facades\DB;

class ChiList extends BaseLive
{
    public $receiptIDPdf;
    public $receptionName;
    public $supplyID;
    public $customerMoney;
    public $customerMoneyCheck;
    public $customerNote;
    public $customerDatePay;
    public $customerName;
    public $customerAddress;
    public $customerOrders;
    public $checkOrders = [];
    protected $listeners = ['checkAllOrder'];

    public $showServiceType = false;
    public $serviceType;

    public $paymentType;
    public $nccCode;
    public $codeSupplyList;
    public $checkAll = true;

    public $accountMoney;
    public $accountMoneyList;
    public $accountMoneyListTmp;

    protected $listeners1 = ['settransactionDate', 'printfPDFChi' => 'printfPDFChi', 'createPhieuChi' => 'createPhieuChi'];
    public function mount()
    {
        $this->receptionName = Auth::user()->name;
        $this->customerDatePay = now()->format('Y-m-d');
        $this->accountMoneyListTmp = AccountMoney::get();
    }
    public function render()
    {


        //neu la tra lai hang ban thi lay theo customer
        if ($this->paymentType==12) {
            # code...
            if(!isset( $this->nccCode) || empty($this->nccCode) ){
                $this->nccCode = '';
            }
            $orders = $this->getReturnOrders();
            $supplies = Customer::get();
        }else{
            $supplies = Supplier::get();
            $orders = $this->getOrder();
        }
        $this->codeSupplyList = $supplies->map(function ($item) {
            return (object)[
                'id' => $item->id,
                'code' => $item->code,
                'codeAndName' => $item->code . ' - ' . $item->name
            ];
        });
        $this->accountMoneyList = $this->accountMoneyListTmp->map(function ($item) {
            return (object)[
                'id' => $item->id,
                'account_name' => $item->account_code . ' - ' . $item->bank_name . ' (' . $item->balance . ')'
            ];
        });
        $listService = ListService::select('id', 'title')->where('type', ListServiceType::OUT)->get();
        $this->dispatchBrowserEvent('setPayDatePicker');
        $this->dispatchBrowserEvent('setSelect2');
        return view('livewire.ketoan.list-chi', ['data' => $orders, 'listService' => $listService]);
    }
    public function updatedcheckAll()
    {
        if ($this->checkAll == true) {
            $listOrder =  $this->customerOrders->pluck('id')->toArray();

            foreach ($listOrder as $value) {
                array_push($this->checkOrders, (string)$value);
            }
        } else {
            $this->checkOrders =  [];
        }
        $this->updatedcheckOrders();
    }

    /**
     * get cac don hang tra lai
     */
    public function getReturnOrders(){
        try {
            //code...

            $customerid = Customer::where('code','=', $this->nccCode )->pluck('id')->first();

            $orders = DB::table('return_items')
            ->where('return_items.paid_status','=',0)
            ->where('return_items.customer_id','=',$customerid)
            ->join('accessories', function($join){
                $join->on('return_items.item_id','=','accessories.id');
                $join->whereNull('accessories.deleted_at');
            })
            ->select('return_items.id','return_items.item_id',
            'accessories.code','accessories.name',
            'return_items.item_price',
            'return_items.item_qty',
            DB::raw('return_items.item_qty * return_items.item_price as amount')
            )->get();
            return $orders;
        } catch (Exception $e) {
            //throw $th;
            Log::info($e);
            return null;
        }

    }

    public function getOrder()
    {
        $orders = Order::where('supplier_id', $this->supplyID)
            ->where('order_type', EOrder::ORDER_TYPE_BUY)
            ->where('status', EOrder::STATUS_UNPAID);
        if ($this->paymentType) {
            $orders = $this->getOrderReceipts($orders, $this->paymentType);
        }
        if ($this->serviceType) {
            $orders = $orders->with(["feeOut", "feeOut.listService"]);
            $orders = $orders->whereHas('feeOut.listService', function ($query) {
                return $query->where('list_service_id', $this->serviceType);
            });
        }
        //$this->customerOrders = $orders->get();
        //$this->customerMoney = $this->customerOrders->sum('total_money');
        $orders = $orders->paginate($this->perPage);
        return $orders;
    }
    public function getFirstArray($data)
    {
        if ($data != []) {
            foreach ($data as $key => $valye) {
                return $key;
            }
        } else {
            return null;
        }
    }

    public function updatedcheckOrders()
    {
        $this->customerMoney = $this->customerOrders->whereIn('id', $this->checkOrders)->sum('total_money');

        if (isset($this->paymentType)  && $this->paymentType!=12) {
            # code...
            $this->customerMoney = $this->customerOrders->whereIn('id', $this->checkOrders)->sum('total_money');
        }
    }
    public function updatedNccCode()
    {
        $this->checkOrders = [];
        $this->nccCode = trim($this->nccCode);
        $supply = Supplier::where('code', $this->nccCode)->get()->first();
        if ($supply) {
            $this->supplyID = $supply->id;
            $this->customerName = $supply->name;
            $this->customerAddress = $supply->address
                . (isset($supply->wardSupply) ? ', ' . $supply->wardSupply->name : '')
                . (isset($supply->districtSupply) ? ', ' . $supply->districtSupply->name : '')
                . (isset($supply->provinceSupply) ? ', ' . $supply->provinceSupply->name : '');
            $orders = Order::where('supplier_id', $this->supplyID)->where('order_type', EOrder::ORDER_TYPE_BUY)->where('status', EOrder::STATUS_UNPAID);
            if ($this->paymentType) {
                $orders = $this->getOrderReceipts($orders, $this->paymentType);
            }

            $this->customerOrders = $orders->get();
            $this->customerMoney = $this->customerOrders->sum('total_money');
            $checkOrdersList = $this->customerOrders->pluck('id')->toArray();
            foreach ($checkOrdersList as $key => $value) {
                $this->checkOrders[] = (string)$value;
            }
        } else {
            $this->supplyID = null;
            $this->customerMoney = 0;
            $this->checkOrders = [];
            $this->customerName = '';
            $this->customerAddress = '';
        }
    }
    public function updatedpaymentType()
    {
        $this->checkOrders = [];
        if ($this->supplyID) {
            $orders = Order::where('supplier_id', $this->supplyID)->where('order_type', EOrder::ORDER_TYPE_BUY)->where('status', EOrder::STATUS_UNPAID);
            if ($this->paymentType) {
                $orders = $this->getOrderReceipts($orders, $this->paymentType);
            }

            $this->customerOrders = $orders->get();
            $this->customerMoney = $this->customerOrders->sum('total_money');
            $checkOrdersList = $this->customerOrders->pluck('id')->toArray();
            foreach ($checkOrdersList as $key => $value) {
                $this->checkOrders[] = (string)$value;
            }
        }
    }

    public function createPhieuChi()
    {
        $supplierid = 0;
        $customerid = 0;
        if($this->paymentType!=12){
            if (!$this->supplyID) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Không có thông tin nhà cung cấp. Hãy chọn nhà cung cấp cần trả tiền']);
                return;
            }
            if ($this->supplyID && ($this->customerOrders->isEmpty() && $this->paymentType != 10)) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Nhà cung cấp không có đơn hàng nào chưa thanh toán']);
                return;
            }
            $supply = Supplier::find($this->supplyID);
            $supplierid =$supply->id;
        }else{
            $customerid = Customer::where('code','=', $this->nccCode )->pluck('id')->first();
            if (!$customerid) {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Không có thông tin khách hàng. Hãy chọn khách hàng cần trả tiền']);
                return;
            }
            $this->serviceType =0;
        }
        if(!empty($this->checkOrders))
        {
            if($this->customerMoney > $this->customerOrders->whereIn('id', $this->checkOrders)->sum('total_money'))
            {
                $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Số tiền chi phải nhỏ hơn hoặc bằng tổng số tiền của hóa đơn']);
                return;
            }
        }
        

        $this->validate([
            'customerNote' => 'required',
            'customerDatePay' => 'required',
            'customerMoney' => 'required|numeric|min:1',
            'accountMoney' => 'required'
        ], [
            'customerNote.required' => 'Ghi chú bắt buộc',
            'customerDatePay.required' => 'Ngày chi bắt buộc',
            'customerMoney.required' => 'Số tiền chi bắt buộc',
            'customerMoney.numeric' => 'Số tiền chi phải là số',
            'customerMoney.min' => 'Số tiền chi phải lớn hơn 0',
            'accountMoney.required' => 'Tài khoản thanh toán bắt buộc'
        ], []);
        DB::beginTransaction();
        try {
            //code...
            //validate trong truong hop ko fai la tra lai hang ban
            



            // lưu vào phiếu thu
            $receipt = Payment::create([
                'supplier_id' => $supplierid, //tudn change supply->id = $supplierid
                'customer_id' => $customerid, //tudn change supply->id = $supplierid
                'money' => (int)$this->customerMoney,
                'note' => $this->customerNote,
                'payment_date' => $this->customerDatePay,
                'type' =>  !empty($this->paymentType) ? $this->paymentType : null,
                'user_id' => auth()->id(),
                'account_money_id' => $this->accountMoney,
                'service_id' => $this->serviceType
            ]);
            $this->receiptIDPdf = $receipt->id;
            $accountMoneyUse = AccountMoney::where('id', $this->accountMoney)->first();
            if ($accountMoneyUse) {
                $accountMoneyUse->balance -= (int)$this->customerMoney;
                $accountMoneyUse->save();
            }
            // chuyển trạng thái order
            if ($this->paymentType!=12) {
                # code...
                Order::whereIn('id', $this->checkOrders)->update([
                    'bill_id' => $receipt->id,
                    'status' => EOrder::STATUS_PAID,
                ]);
            }else{
                # code...
                ReturnItem::whereIn('id', $this->checkOrders)->update([
                    'bill_id' => $receipt->id,
                    'paid_status' => EOrder::STATUS_PAID
                ]);
            }
            DB::commit();
            $this->resetData();
            $this->dispatchBrowserEvent('confirmPrintPdfChi', ['url' => route('ketoan.inphieuchi.index', ['id' => $this->receiptIDPdf])]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Tạo phiếu thu thất bại']);
            return;
        }
    }
    public function resetData()
    {
        $this->customerName  = '';
        $this->customerAddress = '';
        $this->customerMoney = 0;
        $this->customerNote = '';
        $this->customerDatePay = now()->format('Y-m-d');
        $this->paymentType = '';
        $this->supplyID = '';
        $this->checkOrders = [];
        $this->nccCode = '';
        $this->accountMoney = '';
    }

    public function getOrderReceipts($orders, $receipts)
    {
        if ($receipts == 8) { // nhập phụ tùng
            $orders = $orders->where('category', 1)->where('type', 3);
        } else if ($receipts == 9) { // nhập xe
            $orders = $orders->where('category', 2)->where('type', 3);
        } else if ($receipts == 10) { // dịch vụ khác
            $orders = $orders->where('category', 6);
        } else if ($receipts == 11) { // công việc khác
            $orders = $orders->where('type', 3)->where(function ($q) {
                $q->orWhere('category', 3);
                $q->orWhere('category', 4);
            });
        }
        return $orders;
    }
    public function settransactionDate($time)
    {
        $this->customerDatePay = date('Y-m-d', strtotime($time['transactionDate']));
    }
}
