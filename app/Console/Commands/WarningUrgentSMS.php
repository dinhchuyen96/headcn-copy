<?php

namespace App\Console\Commands;

use App\Http\Controllers\API\SmsGatewayController;
use App\Models\HMSPartNotAllowUrgent;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WarningUrgentSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:WarningUrgentSMS';

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
        Log::info('WarningUrgentSMS handle() bắt đầu WarningUrgentSMS');
        $headCodeSMS = env('APP_HEADCODE');
        $get_first_day = date('Y-m-01');
        $monthSMS = date("m", strtotime(date('Y-m-d')));
        $today = date('Y-m-d');
        $paramSMS =  HMSPartNotAllowUrgent::query()
            ->with('orderPlanDetails.orderPlan')
            ->whereHas('orderPlanDetails', function ($query2) use ($get_first_day, $today) {
                return $query2->whereHas('orderPlan', function ($query3) use ($get_first_day, $today) {
                    return $query3->whereDate('po_date', '>=', $get_first_day)
                        ->whereDate('po_date', '<=', $today)
                        ->where('part_order_type', 'LIKE', '%Urgent Order%');
                });
            })
            ->count();
        $request = new Request();
        $request->smsid = env('SMS_ID_WARNING_URGENT', 205000);
        $request->param = $headCodeSMS . "__" . $monthSMS . "__" . $paramSMS;
        $api = new SmsGatewayController();
        $api->send($request);
        Log::info(' WarningUrgentSMS handle() kết thúc WarningUrgentSMS');
    }
}
