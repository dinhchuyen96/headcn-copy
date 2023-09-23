<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Motorbike;
use App\Enum\EMotorbike;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\SmsGatewayController;

class CustomerCareService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CustomerCareService';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mời khách hàng đến kiểm tra định kỳ';

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
        Log::info('Mời khách hàng đến kiểm tra định kì');
        Log::info('Lấy danh sách các xe cần báo kiểm tra định kì');
        $motorbikeList = Motorbike::with('customer')
            ->whereNotNull('customer_id')
            ->where('status', EMotorbike::SOLD)
            ->whereNotNull('sell_date')
            ->get();
        Log::info('Lấy danh sách các xe cần báo kiểm tra định kì: ' . $motorbikeList->count() . ' xe');
        $now =  Carbon::today();
        $request = new Request();
        $request->smsid = env('SMS_ID_BAO_KIEM_TRA_DINH_KY', 205083);
        $api = new SmsGatewayController();
        foreach ($motorbikeList as $key => $item) {
            $request->customerPhone = $item->customer->phone; // gửi đến ai
            $sellDate = Carbon::createFromFormat('Y-m-d', $item->sell_date);

            // Lần 1 sẽ báo tin nhắn khi mua xe luôn
            $dateNo2 = $now->diffInDays($sellDate->addMonths(1)->addDays(1)->addDays(-7));
            $dateNo3 = $now->diffInDays($sellDate->addMonths(6)->addDays(1)->addDays(-7));
            $dateNo4 = $now->diffInDays($sellDate->addMonths(12)->addDays(1)->addDays(-7));
            $dateNo5 = $now->diffInDays($sellDate->addMonths(18)->addDays(1)->addDays(-7));
            $dateNo6 = $now->diffInDays($sellDate->addMonths(27)->addDays(1)->addDays(-7));
            $checkNo = 1;
            if ($dateNo2 == 0) {
                $checkNo = 2;
                $request->param = $item->customer->name . "__" . $checkNo . "__" . $sellDate->addMonths(1)->addDays(1)->month . "__" . env("HEAD_PHONE_SUPPORT");
            }
            if ($dateNo3 == 0) {
                $checkNo = 3;
                $request->param = $item->customer->name . "__" . $checkNo . "__" . $sellDate->addMonths(6)->addDays(1)->month . "__" . env("HEAD_PHONE_SUPPORT");
            }
            if ($dateNo4 == 0) {
                $checkNo = 4;
                $request->param = $item->customer->name . "__" . $checkNo . "__" . $sellDate->addMonths(12)->addDays(1)->month . "__" . env("HEAD_PHONE_SUPPORT");
            }
            if ($dateNo5 == 0) {
                $checkNo = 5;
                $request->param = $item->customer->name . "__" . $checkNo . "__" . $sellDate->addMonths(18)->addDays(1)->month . "__" . env("HEAD_PHONE_SUPPORT");
            }
            if ($dateNo6 == 0) {
                $checkNo = 6;
                $request->param = $item->customer->name . "__" . $checkNo . "__" . $sellDate->addMonths(27)->addDays(1)->month . "__" . env("HEAD_PHONE_SUPPORT");
            }
            if ($checkNo != 1) {
                Log::info('Gửi tin nhắn đến số điện thoại : ' . $item->customer->phone);
                Log::info('Họ tên người nhận : ' . $item->customer->name);
                $result = $api->sendToCustomer($request);
                Log::info('Gửi KTĐK lần ' . $checkNo . ': OK');
                if ($result == "1") {
                    $item->customer->is_sent_ktdk = 1;
                    $item->customer->last_datetime_sent_ktdk = Carbon::now();
                    $item->customer->save();
                }
            }
        }
        Log::info('Mời khách hàng đến kiểm tra định kì: Done');
    }
}
