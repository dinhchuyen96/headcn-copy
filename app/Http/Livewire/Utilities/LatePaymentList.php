<?php

namespace App\Http\Livewire\Utilities;

use App\Http\Livewire\Base\BaseLive;
use App\Models\HMSPayment;
use App\Models\HMSReceivePlan;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
class LatePaymentList extends BaseLive
{
    public $searchDelivery;
    public $fromDate;
    public $toDate;

    protected $listeners = ['setfromDate', 'settoDate'];
    public function render()
    {

        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        $deliveryNo = HMSReceivePlan::query()
            ->where('stock_out_date_time', '>=', $get_first_day)
            ->where('stock_out_date_time', '<=', $today)
            ->get();  //6bg
        $countLatePayment = 0;
        $result = [];
        foreach ($deliveryNo as $value) {
            $stock_out_date_time = $value->stock_out_date_time;
            $diffDay =  strtotime('-3 day', strtotime($stock_out_date_time));
            $lateDay = date('Y-m-d', $diffDay);
            $hvn_lot_number = $value->hvn_lot_number;
            $deliveryNos = HMSPayment::query()
                ->where('delivery_nos', 'like', '%' . $hvn_lot_number . '%')
                ->where('credit_date', '>', $lateDay)
                ->first();
            if (!empty($deliveryNos)) {
                $foundPlan = $deliveryNo->firstWhere('hvn_lot_number', $deliveryNos->delivery_nos);
                $deliveryNos->stock_out_date_time = $foundPlan->stock_out_date_time;
                $deliveryNos->hvn_lot_number = $foundPlan->hvn_lot_number;
                array_push($result, $deliveryNos);
            }
        }
        if ($this->reset) {
            $this->searchDelivery = null;
            $this->fromDate = null;
            $this->toDate = null;
            $this->emit('resetDateKendo');
        }
        $this->searchDelivery = trim($this->searchDelivery);
        if ($this->searchDelivery) {
            $deliveryNo->where('hvn_lot_number', 'LIKE', '%' . $this->searchDelivery . '%');
        }
        if ($this->fromDate) {
            $deliveryNo->where(DB::raw("STR_TO_DATE(invoice_date,'%d/%m/%y')"), '>=', $this->searchDelivery);
        }
        if ($this->toDate) {
            $deliveryNo->where(DB::raw("STR_TO_DATE(invoice_date,'%d/%m/%y')"), '<=', $this->searchDelivery . ' 23:59:59');
        }

        return view('livewire.utilities.late-payment-list', ['result' => $result]);
    }
    public function setfromDate($time)
    {
        $this->fromDate = date('Y-m-d', strtotime($time['fromDate']));
    }
    public function settoDate($time)
    {
        $this->toDate = date('Y-m-d', strtotime($time['toDate']));
    }
}
