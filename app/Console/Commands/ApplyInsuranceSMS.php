<?php

namespace App\Console\Commands;

use App\Http\Controllers\API\SmsGatewayController;
use App\Models\HMSServiceResults;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApplyInsuranceSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ApplyInsuranceSMS';

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
        Log::info('ApplyInsuranceSMS  handle() bắt đầu ApplyInsuranceSMS');
        $headCodeSMS = env('APP_HEADCODE');
        $monthSMS = date("m", strtotime(date('Y-m-d')));
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        $applyService = HMSServiceResults::query()
            ->where('sr_created_date_time', '>=', $get_first_day)
            ->where('sr_closed_date_time', '<=', $today)
            ->whereNotNull('sr_closed_date_time')
            ->where('reason_for_cancellation', '!=', '')
            ->count();

        $serviceInMonth = HMSServiceResults::query()
            ->where('sr_created_date_time', '>=', $get_first_day)
            ->where('sr_created_date_time', '<=', $today)
            ->count();

        if ($serviceInMonth != 0) {

            $applyInsurance = round(($applyService / $serviceInMonth) * 100);
        } else {
            $applyInsurance = 0;
        }

        $paramSMS = $applyInsurance;
        $request = new Request();
        $request->smsid = env('SMS_ID_APPLY_INSURANCE', 205001);
        $request->param = $headCodeSMS . "__" . $monthSMS . "__" . $paramSMS;
        $api = new SmsGatewayController();
        $api->send($request);
        Log::info('ApplyInsuranceSMS handle() kết thúc ApplyInsuranceSMS');
    }
}
