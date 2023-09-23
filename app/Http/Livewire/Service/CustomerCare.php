<?php

namespace App\Http\Livewire\Service;

use App\Http\Livewire\Base\BaseLive;
use Illuminate\Support\Facades\DB;
use App\Models\Motorbike;
use App\Models\Warehouse;
use App\Models\Supplier;
use App\Models\Customer;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MotorbikeListExport;
use App\Enum\ECareCustomer;
use App\Http\Controllers\API\SmsGatewayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Enum\EMotorbike;
use App\Events\SendSms;

class CustomerCare extends BaseLive
{
    // danh sách customer
    public $listCustomer;
    // item search
    public $searchInfoCustomer;
    public $nameCustomer;
    public $key_name = "created_at";
    public $sortingName = "desc";
    public $listUserReceiveMgS;
    //listReceiveMgs
    public $listSend = [];
    public $idUser;
    public $param1;
    public $param2;
    public $param3;
    public $selectOption;
    public $contentSent4S;
    public $contentSentRepair;
    public $content;
    public $isCheckAll;
    protected $listeners = ['SentMgs'];

    public function mount()
    {
        $this->selectOption = 1;
        if (empty($this->param1)) {
            $this->param1 = now()->format('Y-m-d');
        }
        if (empty($this->param2)) {
            $this->param2 = vn_to_str(env("HEAD_NAME"));
        }
        if (empty($this->param3)) {
            $this->param3 = env("HEAD_PHONE_SUPPORT");
        }
    }
    public function render()
    {
        $contentSent4S = "Moi KH den thay dau mien phi va tham gia thi lai xe tiet kiem nhien lieu vao ngay <span style='color:red'>" . $this->param1 . "</span> danh cho xe may Honda VN tai  <span style='color:red'>" . $this->param2 . "</span>. DT : <span style='color:red'>" . $this->param3 . "</span>";
        //$contentSentRepair = "Moi KH den thay dau mien phi cho Quy Khach den lam bao bao duong toan bo tai cua hang xe may Phu Lien trong thang <span style='color:red'>" . $this->param1 . "</span>. ĐTLH : <span style='color:red'>" . $this->param2 . "</span> ";
        if ($this->selectOption == "1") {
            $this->content =  $contentSent4S;
        }
        $listCustomer = Customer::withCount('motorbikes', 'orders');

        if ($this->searchInfoCustomer) {
            $listCustomer->where(function ($q) {
                $q->where('customers.phone', $this->searchInfoCustomer);
            });
        }

        $data = $listCustomer->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage)->setPath(route('cskh.cham-soc-khach-hang.index'));
        $this->dispatchBrowserEvent('setSelect2');
        $this->dispatchBrowserEvent('select2Customer');
        $this->dispatchBrowserEvent('setDatePicker');
        return view('livewire.service.customer-care-service', ['data' => $data]);
    }
    public function updatedisCheckAll()
    {
        if ($this->isCheckAll == true) {
            $query = Customer::query();
            if ($this->searchInfoCustomer) {
                $query = $query->where(function ($q) {
                    $q->where('customers.phone', $this->searchInfoCustomer);
                });
            }
            $listCus =  $query->get()->pluck("id")->toArray();
            foreach ($listCus as $value) {
                array_push($this->listSend, (string)$value);
            }
        } else {
            $this->listSend =  [];
        }
    }
    public function updatedsearchInfoCustomer()
    {
        $this->listSend =  [];
    }
    public function updatedparam2()
    {
        $this->param2 = vn_to_str(trim($this->param2));
    }
    public function SentMgs()
    {

        $this->validate([
            'selectOption' => 'required',
        ], [
            'selectOption.required' => 'chọn trạng thái bắt buộc',
        ], []);
        $this->listSend = array_unique($this->listSend);
        if (count($this->listSend) <= 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Vui lòng chọn danh sách khách hàng để gửi tin nhắn']);
            return;
        }
        if ($this->selectOption == "1") {
            $this->validate([
                'selectOption' => 'required',
                'param1' => 'required',
                'param2' => 'required',
                'param3' => 'required',
            ], [
                'param1.required' => 'param 1 bắt buộc',
                'param2.required' => 'param 2 bắt buộc',
                'param3.required' => 'param 3 bắt buộc',
                'selectOption.required' => 'chọn trạng thái bắt buộc'
            ], []);
            Log::info('Lấy danh sách khách gửi tin nhắn 4S');
            foreach ($this->listSend as $id) {
                $customer = Customer::where('id', $id)->first();
                $contentSms = $this->param1 . "__" . $this->param2 . "__" . $this->param3;
                event(new SendSms($customer, $contentSms));
            }
            $this->dispatchBrowserEvent('show-toast', ['type' => 'info', 'message' => 'Đã tiến hành gửi tin nhắn 4S']);
            $this->dispatchBrowserEvent('setSelect2');
        }
    }
}
