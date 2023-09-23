<?php

namespace App\Http\Livewire\Motorbike;

use App\Http\Livewire\Base\BaseLive;
use Livewire\Component;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use App\Component\Recursive;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ListOrderBuyMotorbikeExport;
use App\Models\Motorbike;
use App\Exports\MotorbikeExport;
use App\Models\Supplier;
use App\Models\OrderDetail;
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Writer as Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrintInfoMotorbike extends BaseLive
{
    public $headCompany;
    public $headName ;
    public $headAddress;
    public $headPhoneNumber;
    public $customer;
    public $motorbike;
    public $day ;
    public $month ;
    public $year ;
    public $address ;

    public function mount($id)
    {
        $orderDetail = OrderDetail::where('id', $id)->firstOrFail();
        $this->customer = $orderDetail->order->customer;
        $this->motorbike = $orderDetail->motorbike;
        $this->headCompany = env('HEAD_COMPANY');
        $this->headName = env('HEAD_NAME');
        $this->headAddress = env('HEAD_ADDRESS');
        $this->headPhoneNumber = env('HEAD_PHONE_NUMBER');
        $this->day = Carbon::now()->day;
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        $this->address = $this->customer->address
        . (isset($this->customer->wardCustomer) ? ', ' . $this->customer->wardCustomer->name : '')
        . (isset($this->customer->districtCustomer) ? ', ' . $this->customer->districtCustomer->name : '')
        . (isset($this->customer->provinceCustomer) ? ', ' . $this->customer->provinceCustomer->name : '');


    }
    public function render()
    {
        return view('livewire.motorbike.in-thong-tin-motorbike');
    }
}
