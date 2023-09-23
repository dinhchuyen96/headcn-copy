<?php

namespace App\Console\Commands;

use App\Http\Controllers\API\SmsGatewayController;
use App\Models\HMSPayment;
use App\Models\HMSReceivePlan;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LatePaymentSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:LatePaymentSMS';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('LatePaymentSMS handle() bắt đầu LatePaymentSMS');
        $headCodeSMS = env('APP_HEADCODE');
        $monthSMS = date("m", strtotime(date('Y-m-d')));
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
        $paramSMS = count($result);
        $request = new Request();
        $request->smsid = env('SMS_ID_LATE_PAYMENT', 204998);
        $request->param = $headCodeSMS . "__" . $monthSMS . "__" . $paramSMS;
        $api = new SmsGatewayController();
        $api->send($request);
        Log::info('LatePaymentSMS handle() kết thúc LatePaymentSMS');
    }
}
