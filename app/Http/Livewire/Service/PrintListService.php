<?php

namespace App\Http\Livewire\Service;

use App\Exports\OdersExport;
use App\Http\Livewire\Base\BaseLive;
use App\Models\Order;
use App\Models\Accessory;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Bus;
use Maatwebsite\Excel\Facades\Excel;
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Writer as Writer;

class PrintListService extends BaseLive
{
    public $headCompany;
    public $headName;
    public $headAddress;
    public $repairPreodic;
    public $customer;
    public $repairBill;
    public $in_factory_date;
    public $repairTask;
    public $motobike;
    public $modelMotorbikeName;
    public $km;
    public $total;
    public $address;
    public $customerServiceRequest;
    public $checkService = [];
    public $contentSuggest; // Tư vấn sửa chữa
    public $beforeRepair = false; // Trước sửa chữa
    public $afterRepair = false; // Sau sửa chữa
    public $notNeedWash = false; // Không cần rửa xe

    public function mount($id)
    {
        $order = Order::where('id', $id)->with('details')->firstOrFail();
        $this->customer = $order->customer;
        $this->repairBill = null;
        $this->repairPreodic = null;
        $this->modelMotorbikeName = $order->model_motorbike_name;
        if ($order->category == EOrderDetail::CATE_REPAIR) {
            $this->repairBill = $order->repairBill;
        }
        if ($order->category == EOrderDetail::CATE_MAINTAIN) {
            $this->repairPreodic = $order->periodic;
        }
        $this->repairTask = $order->repairTask;
        $this->orderDetail = $order->details->where('status', EOrderDetail::STATUS_SAVED)
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->where('is_atrophy', EOrderDetail::NOT_ATROPHY_ACCESSORY)
            ->filter(function ($item, $key) {
                return $item->category == EOrderDetail::CATE_REPAIR || $item->category == EOrderDetail::CATE_MAINTAIN;
            });
        $this->address = $this->customer->address
            . (isset($this->customer->wardCustomer) ? ', ' . $this->customer->wardCustomer->name : '')
            . (isset($this->customer->districtCustomer) ? ', ' . $this->customer->districtCustomer->name : '')
            . (isset($this->customer->provinceCustomer) ? ', ' . $this->customer->provinceCustomer->name : '');

        $this->headName = env('HEAD_NAME');
        $this->headAddress = env('HEAD_ADDRESS');
        $this->headPhoneNumber = env('HEAD_PHONE_NUMBER');
        $this->headHotline = env('HEAD_HOTLINE');
        $this->headEmail = env('HEAD_EMAIL');
        if ($this->repairBill) {
            $this->checkService = explode(",", $this->repairBill->check_service);
            $this->customerServiceRequest = $this->repairBill->content_request;

            $this->contentSuggest = $this->repairBill->content_suggest ?? '';
            $this->beforeRepair = $this->repairBill->before_repair == 1;
            $this->afterRepair = $this->repairBill->after_repair == 1;
            $this->notNeedWash = $this->repairBill->not_need_wash == 1;

            $this->motorbike = $this->repairBill->motorbike;
            $this->in_factory_date = empty($this->repairBill->in_factory_date) ? '' : Carbon::createFromFormat('Y-m-d H:s:i', $this->repairBill->in_factory_date)->format('d/m/Y H:s:i');
        }

        if ($this->repairPreodic) {
            $this->customerServiceRequest = $this->repairPreodic->content_request ?? '';
            $this->contentSuggest = $this->repairPreodic->content_suggest ?? '';
            $this->beforeRepair = $this->repairPreodic->before_repair == 1;
            $this->afterRepair = $this->repairPreodic->after_repair == 1;
            $this->notNeedWash = $this->repairPreodic->not_need_wash == 1;
            $this->checkService = explode(",", $this->repairPreodic->check_service);
            $this->motorbike = $this->repairPreodic->motorbike;
            $this->in_factory_date = empty($this->repairPreodic->created_at) ? '' : Carbon::createFromFormat('Y-m-d H:s:i', $this->repairPreodic->created_at)->format('d/m/Y H:s:i');
        }
        $this->total = $order->total_money;
    }
    public function render()
    {
        return view('livewire.service.list-service-print');
    }
}
