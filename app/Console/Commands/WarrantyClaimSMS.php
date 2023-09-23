<?php

namespace App\Console\Commands;

use App\Http\Controllers\API\SmsGatewayController;
use App\Models\HMSServiceResults;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class WarrantyClaimSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:WarrantyClaimSMS';

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
        Log::info("WarrantyClaimSMS handle() bắt đầu WarrantyClaimSMS");
        $headCodeSMS = env('APP_HEADCODE');
        $monthSMS = date("m", strtotime(date('Y-m-d')));
        $paramSMS = HMSServiceResults::query()->whereRaw('DATEDIFF(sr_closed_date_time,sr_created_date_time) > 5')->count();
        $request = new Request();
        $request->smsid = env('SMS_ID_WARRANTY_CLAIM', 204999);
        $request->param = $headCodeSMS . "__" . $monthSMS . "__" . $paramSMS;
        $api = new SmsGatewayController();
        $api->send($request);
        Log::info('WarrantyClaimSMS handle() kết thúc WarrantyClaimSMS');
    }
}
