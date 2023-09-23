<?php

namespace App\Console\Commands;

use App\Http\Controllers\API\SmsGatewayController;
use App\Models\HMSReceivePlan;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WrongTimeSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:WrongTimeSMS';

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
        Log::info("WrongTimeSMS handle() bắt đầu WrongTimeSMS");
        $headCodeSMS = env('APP_HEADCODE');
        $daySMS = date("d", strtotime(date('Y-m-d')));
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        $paramSMS = HMSReceivePlan::query()->where('eta', '>=', $get_first_day)->where('eta', '<=', $today)->where('arrival_date', '>', 'eta')->count();
        $request = new Request();
        $request->smsid = env('SMS_ID_WRONG_TIME', 204997);
        $request->param = $headCodeSMS . "__" . $daySMS . "__" . $paramSMS;
        $api = new SmsGatewayController();
        $api->send($request);
        Log::info("WrongTimeSMS handle() kết thúc WrongTimeSMS");
    }
}
