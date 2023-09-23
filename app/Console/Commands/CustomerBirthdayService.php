<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\SmsGatewayController;

class CustomerBirthdayService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CustomerBirthdayService';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chúc mừng sinh nhật khách hàng';

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
        Log::info('Chúc mừng sinh nhật khách hàng');
        $customerList = Customer::whereRaw('DATE_FORMAT(birthday,\'%m-%d\') = DATE_FORMAT(NOW(),\'%m-%d\')')->get();
        $request = new Request();
        $request->smsid = env('SMS_ID_CUSTOMER_BIRTHDAY', 205079);
        $api = new SmsGatewayController();
        foreach ($customerList as $key => $item) {
            Log::info('Gửi tin nhắn đến số điện thoại : ' . $item->phone);
            Log::info('Họ tên người nhận : ' . $item->name);
            $request->customerPhone = $item->phone;
            $request->param = env('HEAD_NAME') . "__" . $item->name;
            $result = $api->sendToCustomer($request);
            Log::info('Gửi chúc mừng sinh nhật: OK');
            if ($result == "1") {
                $item->is_sent_birtday = 1;
                $item->last_datetime_sent_birtday = Carbon::now();
                $item->save();
            }
        }
        Log::info('Chúc mừng sinh nhật khách hàng: Done');
    }
}
