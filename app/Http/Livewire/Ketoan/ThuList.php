<?php

namespace App\Http\Livewire\Ketoan;

use App\Http\Livewire\Base\BaseLive;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Order;
use App\Models\AccountMoney;
use App\Models\Receipt;
use App\Models\ListService;
use Carbon\Carbon;
use App\Enum\EOrder;
use App\Enum\ListServiceType;
use App\Models\Motorbike;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Enum\EPaymentMethod;
use Illuminate\Support\Facades\Cache;
use App\Models\GiftSetting;

class ThuList extends BaseLive
{
    public $customerPhone;
    public $receiptIDPdf;
    public $orderIDPdf;
    public $customerID;
    public $customerCode;
    public $customerNote;
    public $customerNotePdf;
    public $customerDatePay;
    public $customerDatePayPdf;
    public $receptionName;
    public $customerOrders;
    public $receiptType;
    public $serviceType;
    public $orderRender;
    public $orderNotPaid;
    public $loanBefore;
    public $needPaid;
    public $actualPaid;
    public $actualPaidPdf;
    public $remainPaid;
    public $customerDueDatePay;
    public $promotionMoney;
    public $promotionMoneyPdf;
    // public $customerPhoneList;
    public $orderId;
    public $ignoreId = [];
    public $customer;
    public $customerName;
    public $customerAddress;
    public $checkOrders = [];
    public $showServiceType = false;
    public $checkAll = true;
    public $accountMoney;
    public $accountMoneyList = [];
    public $CUSTOMER_LIST = 'CUSTOMER_LIST';
    public $ACCOUNT_MONEY_LIST = 'ACCOUNT_MONEY_LIST';
    public $SERVICE_LIST = 'SERVICE_LIST';


    protected $listeners = ['settransactionDate', 'printfPDF' => 'printfPDF', 'createPhieuThu' => 'createPhieuThu'];
    public function mount()
    {

        $this->customerOrders = collect([]);
        if (isset($_GET['customerId'])) {
            $this->customerID = $_GET['customerId'];
            $customer = Customer::find($this->customerID);
            $this->customerPhone = $customer->phone;
            $this->updatedcustomerPhone();
        }
        if (isset($_GET['orderId'])) {
            $this->orderId = $_GET['orderId'];
        }
        $this->receptionName = Auth::user()->name;
        $this->customerDatePay = now()->format('Y-m-d');
        // $this->customerPhoneList = Customer::whereNotNull('phone')->whereNotNull('name')->select('id', 'phone', 'name')->get();
        $this->accountMoneyList = AccountMoney::select('id', 'account_code', 'account_number', 'bank_name', 'balance')->get();
        $this->listService = ListService::select('id', 'title')->where('type', ListServiceType::IN)->get();
    }
    public function render()
    {
        if ($this->customerID) {
            $remainPaid = $this->needPaid - (empty($this->actualPaid) ? 0 : (int)$this->actualPaid) - (empty($this->promotionMoney) ? 0 : (int)$this->promotionMoney);
            $this->remainPaid = $remainPaid > 0 ? $remainPaid : 0;
        }
        $data = [];
        if (isset($this->customerID)) {
            $data = $this->getQuerrySearchOrder()->get();
        };
//        $sumMoney = 0;
//        if (isset($this->customerID)) {
//            $sumMoney = DB::table('orders')->where('customer_id', $this->customerID)->where('status', EOrder::STATUS_UNPAID)->sum('total_money');
//        };
        $sumMoney = $this->getQuerrySearchOrder()->sum('total_money');
        $this->updateUI();
        return view('livewire.ketoan.list-thu', compact('data','sumMoney'));
    }

    public function updateUI()
    {
        $this->dispatchBrowserEvent('select2Customer');
        $this->dispatchBrowserEvent('setSelect2');
        $this->dispatchBrowserEvent('setPayDatePicker');
    }
    public function getOrder()
    {
        return collect($this->getQuerrySearchOrder()->select('id', 'category', 'total_money')->get()->toArray());
    }

    /***
     * TUDN change isVirtual =  0 not false
     */
    public function getQuerrySearchOrder()
    {
        $orders = Order::where('customer_id', $this->customerID)
            ->where('order_type', EOrder::ORDER_TYPE_SELL)
            ->where('status', EOrder::STATUS_UNPAID)
            ->where('isvirtual', 0)
            ->whereNotNull('order_no')
            ->whereNotIn('id', $this->ignoreId)
            ->with(["installment", "installment.installmentCompany"]);
        if ($this->orderId) {
            $orders = $orders->where('id', $this->orderId);
        }
        if ($this->receiptType) {
            $orders = $this->getOrderReceiptType($orders, $this->receiptType);
        }
        if ($this->serviceType) {
            $orders = $orders->with(["otherService", "otherService.listService"]);
            $orders = $orders->whereHas('otherService.listService', function ($query) {
                return $query->where('list_service_id', $this->serviceType);
            });
        }
        return $orders;
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

    public function updatedcustomerPhone()
    {
        if ($this->customerPhone) {
            # code...
            //get customer id by customer phone
            $customer = Customer::where('phone', $this->customerPhone)->first();
            if(isset($customer)){
                $this->customerID = $customer->id;
            }
        }

        if ($this->customerID) {
            //$this->customerPhone = trim($this->customerPhone);
            $customer = Customer::find($this->customerID);
            if ($customer) {
                //$this->customerID = $customer->id;
                $this->customerCode = $customer->code;
                $this->customerPhone = $customer->phone;
                $this->customerName = $customer->name;
                $this->customerAddress = $customer->address
                    . (isset($customer->wardCustomer) ? ', ' . $customer->wardCustomer->name : '')
                    . (isset($customer->districtCustomer) ? ', ' . $customer->districtCustomer->name : '')
                    . (isset($customer->provinceCustomer) ? ', ' . $customer->provinceCustomer->name : '');
                $this->customerOrders = $this->getOrder();
                $this->getMoneyInfo();
            } else {
                $this->ignoreId = [];
                $this->customerCode = null;
                $this->customerID = null;
            }
        } else {
            $this->customerPhone = trim($this->customerPhone);
            $customer = Customer::where('phone', $this->customerPhone)->first();
            if ($customer) {
                $this->customerID = $customer->id;
                $this->customerCode = $customer->code;
                $this->customerName = $customer->name;
                $this->customerAddress = $customer->address
                    . (isset($customer->wardCustomer) ? ', ' . $customer->wardCustomer->name : '')
                    . (isset($customer->districtCustomer) ? ', ' . $customer->districtCustomer->name : '')
                    . (isset($customer->provinceCustomer) ? ', ' . $customer->provinceCustomer->name : '');
                $this->customerOrders = $this->getOrder();
                $this->getMoneyInfo();
            } else {
                $this->customerID = null;
            }
        }
    }
    public function getMoneyInfo()
    {
        $this->checkOrders = array();
        $checkOrdersList = $this->customerOrders->pluck('id')->toArray();
        foreach ($checkOrdersList as $key => $value) {
            array_push($this->checkOrders, (string)$value);
        }
        $this->orderNotPaid = $this->customerOrders->where('category', '!=', EOrder::OTHER)->sum('total_money');
        $this->loanBefore = $this->getTotalLoanMoney();
        $this->needPaid = (empty($this->receiptType) || $this->receiptType == 7) ? $this->loanBefore + $this->orderNotPaid : $this->orderNotPaid;
        $actualPaid = $this->needPaid - (!empty($this->promotionMoney) ? $this->promotionMoney : 0);
        $this->actualPaid = $actualPaid > 0 ? $actualPaid : 0;
    }
    public function updatedcheckOrders()
    {
        $this->orderNotPaid = $this->customerOrders->whereIn('id', $this->checkOrders)->where('category', '!=', EOrder::OTHER)->sum('total_money');
        $this->loanBefore = $this->getTotalLoanMoney();
        $this->needPaid =  (empty($this->receiptType) || $this->receiptType == 7) ? $this->loanBefore + $this->orderNotPaid : $this->orderNotPaid;;
        $actualPaid = $this->needPaid - (!empty($this->promotionMoney) ? $this->promotionMoney : 0);
        $this->actualPaid = $actualPaid > 0 ? $actualPaid : 0;
    }
    public function getTotalLoanMoney()
    {
        // Tổng hóa đơn đã thanh toán và chưa thanh toán từ quá khứ đến nay
        if ($this->customerID) {
            $orders = Customer::where('id', $this->customerID)->first()->orders;
            $countMoneyOrder = $orders
                ->where('created_at', '<=', Carbon::now()->toDateString() . ' 23:59:59')
                ->where('order_type', EOrder::ORDER_TYPE_SELL)
                ->where('category', EOrder::OTHER)
                ->where('status', EOrder::STATUS_UNPAID)
                ->whereIn('id', $this->checkOrders)
                ->sum('total_money');
            return $countMoneyOrder;
        }
        return 0;
    }

    public function updatedCustomerCode()
    {
        if ($this->customerID) {
            //$this->customerCode = trim($this->customerCode);
            $customer = Customer::find($this->customerID);
            if ($customer) {
                $this->customerID = $customer->id;
                $this->customerCode = $customer->code;
                $this->customerPhone = $customer->phone;
                $this->customerName = $customer->name;
                $this->customerAddress = $customer->address
                    . (isset($customer->wardCustomer) ? ', ' . $customer->wardCustomer->name : '')
                    . (isset($customer->districtCustomer) ? ', ' . $customer->districtCustomer->name : '')
                    . (isset($customer->provinceCustomer) ? ', ' . $customer->provinceCustomer->name : '');
                $this->dispatchBrowserEvent('triggerChangeCustomer', ['customerId' => $customer->id, 'customerName' => $customer->name, 'customerPhone' => $customer->phone]);
                $this->customerOrders = $this->getOrder();
                $this->getMoneyInfo();
            } else {
                $this->ignoreId = [];
                $this->customerID = null;
                $this->customerPhone = '';
            }
        } else {
            $this->customerCode = trim($this->customerCode);
            $customer = Customer::where('code', $this->customerCode)->first();
            if ($customer) {
                $this->customerID = $customer->id;
                $this->customerPhone = $customer->phone;
                $this->customerName = $customer->name;
                $this->customerAddress = $customer->address
                    . (isset($customer->wardCustomer) ? ', ' . $customer->wardCustomer->name : '')
                    . (isset($customer->districtCustomer) ? ', ' . $customer->districtCustomer->name : '')
                    . (isset($customer->provinceCustomer) ? ', ' . $customer->provinceCustomer->name : '');
                $this->dispatchBrowserEvent('triggerChangeCustomer', ['customerId' => $customer->id, 'customerName' => $customer->name, 'customerPhone' => $customer->phone]);
                $this->customerOrders = $this->getOrder();
                $this->getMoneyInfo();
            } else {
                $this->customerID = null;
            }
        }
    }

    public function updatedReceiptType()
    {
        if ($this->customerID) {
            $this->customerOrders = $this->getOrder();
            $this->getMoneyInfo();
        }
    }

    public function createPhieuThu()
    {
        if (!$this->customerID) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Không có thông tin khách hàng. Hãy chọn khách hàng hoặc nhập mã khách hàng']);
            return;
        }
        if ($this->customerID && $this->customerOrders->isEmpty()) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Khách hàng không có đơn hàng nào chưa thanh toán']);
            return;
        }
        if ($this->promotionMoney > $this->needPaid) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Số tiền khuyến mãi phải nhỏ hơn số tiền phải thu']);
            return;
        }
        $this->actualPaidConvert = $this->actualPaid != '' ? (int) str_replace(',', '', $this->actualPaid) : '';

        $totalPaid = $this->actualPaidConvert + (empty($this->promotionMoney) ? (int)$this->promotionMoney : 0);
        if ($totalPaid > $this->needPaid) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Số tiền thực thu vượt quá số tiền phải thu']);
            return;
        }

        $this->validate([
            'customerNote' => 'required',
            'actualPaidConvert' => 'required|numeric|min:1',
            'customerDatePay' => 'required',
            'accountMoney' => 'required'
        ], [
            'customerNote.required' => 'Ghi chú bắt buộc',
            'actualPaidConvert.required' => 'Số tiền thu bắt buộc',
            'actualPaidConvert.numeric' => 'Số tiền thu phải là số',
            'actualPaidConvert.min' => 'Số tiền thu phải lớn hơn 0',
            'customerDatePay.required' => 'Ngày thu bắt buộc',
            'accountMoney.required' => 'Tài khoản thanh toán bắt buộc'
        ], []);


        if ($this->remainPaid > 0) {
            $order = new Order();
            $order->customer_id = $this->customerID;
            $order->created_by = auth()->id();
            $order->category = EOrder::OTHER;
            $order->order_type = EOrder::ORDER_TYPE_SELL;
            $order->status = EOrder::STATUS_UNPAID;
            $order->total_money = $this->remainPaid;
            $order->date_payment = $this->customerDueDatePay;
            $order->note = $this->customerNote;
            $order->save();
        }
        try {
            DB::beginTransaction();
            $customer = Customer::find($this->customerID);
            // lưu vào phiếu thu
            $receipt = Receipt::create([
                'customer_id' => $customer->id,
                'money' => $this->actualPaidConvert + (!empty($this->promotionMoney) ? (int)$this->promotionMoney : 0),
                'note' => $this->customerNote,
                'receipt_date' => $this->customerDatePay,
                'type' => empty($this->receiptType) ? null : $this->receiptType,
                'promotion' => !empty($this->promotionMoney) ? (int)$this->promotionMoney : 0,
                'user_id' => auth()->id(),
                'account_money_id' => $this->accountMoney
            ]);
            $this->receiptIDPdf = $receipt->id;
            $accountMoneyUse = AccountMoney::where('id', $this->accountMoney)->first();
            if ($accountMoneyUse) {
                $accountMoneyUse->balance += $this->actualPaid;
                $accountMoneyUse->save();
            }
            // chuyển trạng thái order cho những order được checked
            if (count($this->checkOrders) > 0) {
                Order::whereIn('id', $this->checkOrders)->update([
                    'bill_id' => $receipt->id,
                    'status' => EOrder::STATUS_PAID,
                ]);
                $orderChecked = Order::whereIn('id', $this->checkOrders)->get();
                $totalPriceTaskRepair = 0;
                $totalPriceAcessory = 0;
                foreach ($orderChecked as $key => $item) {
                    $totalPriceTaskRepair += $item->totalPriceForTaskRepair();
                    $totalPriceAcessory += $item->totalPriceForAccesoryRepair();
                }
                $motorbikePoint = 0;
                $motorbikeOfCustomer = $customer->motorbikes;
                foreach ($motorbikeOfCustomer as $key => $item) {
                    $motorbikeForRepair = Motorbike::where('id', $item->id)->withCount(['repairBills', 'periodics'])->first();
                    if (($motorbikeForRepair->repair_bills_count +  $motorbikeForRepair->periodics_count) > 1) {
                        $motorbikePoint++;
                    }
                }
                $giftSetting = GiftSetting::first();
                $giftTranfer = empty($giftSetting) ? 20000 : $giftSetting->gift_tranfer;
                $totalPoint = $motorbikePoint + round($totalPriceTaskRepair / $giftTranfer) + round($totalPriceAcessory / $giftTranfer);
                $customer->point = $customer->point + $totalPoint;
                $customer->save();
            }
            DB::commit();

            $this->resetData();
            $this->dispatchBrowserEvent('confirmPrintPdf', ['point' => $totalPoint, 'customer' => $customer->name]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Tạo phiếu thu thất bại']);
            return;
        }
    }
    public function resetData()
    {
        $this->customerIDPdf = $this->customerID;
        $this->actualPaidPdf = $this->actualPaid;
        $this->promotionMoneyPdf = $this->promotionMoney;
        $this->customerNotePdf = $this->customerNote;
        $this->customerDatePayPdf = $this->customerDatePay;

        $this->checkOrders = [];
        $this->customerPhone = '';
        $this->customerNote = '';
        $this->customerDatePay = now()->format('Y-m-d');
        $this->receiptType = '';
        $this->customerID = '';
        $this->customerCode = '';
        $this->orderNotPaid = 0;
        $this->loanBefore = 0;
        $this->needPaid = 0;
        $this->actualPaid = '';
        $this->remainPaid = 0;
        $this->promotionMoney = '';
        $this->customerDueDatePay = '';
        $this->accountMoney = '';
    }

    public function updatedPromotionMoney()
    {
        if ($this->customerID) {
            $actualPaid = $this->needPaid - (empty($this->promotionMoney) ? 0 : (int)$this->promotionMoney);

            $this->actualPaid = $actualPaid > 0 ? $actualPaid : 0;
        }
    }

    public function getOrderReceiptType($orders, $receiptType)
    {
        switch ($receiptType) {
            case 1: // bán lẻ xe máy
                $orders = $orders->where('type', EOrder::TYPE_BANLE)->where('category', EOrder::CATE_MOTORBIKE);
                break;
            case 2: // bán buôn xe máy
                $orders = $orders->where('type', EOrder::TYPE_BANBUON)->where('category', EOrder::CATE_MOTORBIKE);
                break;
            case 3: //Bán lẻ phụ tùng
                $orders = $orders->where('type', EOrder::TYPE_BANLE)->where('category', EOrder::CATE_ACCESSORY);
                break;
            case 4: //Bán buôn phụ tùng
                $orders = $orders->where('type', EOrder::TYPE_BANBUON)->where('category', EOrder::CATE_ACCESSORY);
                break;
            case 5: // bảo dưỡng xe máy
                $orders = $orders->where('category',  EOrder::CATE_MAINTAIN);
                break;
            case 6: //sửa chữa xe máy
                $orders = $orders->where('category',  EOrder::CATE_REPAIR);
                break;
            case 7: // Nợ tồn
                $orders = $orders->where('category',  EOrder::OTHER);
                break;
            case 8: // Dịch vụ khác
                $orders = $orders->where('category',  EOrder::SERVICE_OTHER);
                break;
            case 9: // Trả góp
                $orders = $orders->where('payment_method',  EPaymentMethod::INSTALLMENT);
                break;
            default:
                # code...
                break;
        }
        return $orders;
    }
    public function settransactionDate($time)
    {
        $this->customerDatePay = date('Y-m-d', strtotime($time['transactionDate']));
    }
    public function printfPDF()
    {
        $this->dispatchBrowserEvent('redirectToPrintfPdf', ['url' => route('ketoan.xuatpdf.index', ['id' => $this->receiptIDPdf])]);
    }
    function reformatNumber($number, $specials = ['.', ','])
    {
        foreach ($specials as $special) {
            $number = str_replace($special, '', $number);
        }
        return number_format($number);
    }
    function formatNumberMoney($number, $specials = ['.', ','])
    {
        if (empty($number))
            return 0;
        foreach ($specials as $special) {
            $number = str_replace($special, '', $number);
        }
        return $number;
    }
}
