<?php

namespace App\Listeners;

use App\Events\SendSms;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\API\SmsGatewayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendSmsToCustomer
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SendSms  $event
     * @return void
     */
    public function handle(SendSms $event)
    {
        $request = new Request();
        $request->smsid = env('SMS_ID_TO_CUSTOMER', 205084);
        $api = new SmsGatewayController();
        $customer = $event->customer;
        $request->customerPhone = $customer->phone;
        $request->param = $event->contentSms;
        Log::info('Gửi tin nhắn 4S tới Khách hàng : ' . $customer->name . 'SĐT' . $customer->phone);
        $result = $api->sendToCustomer($request);
        if ($result == "1") {
            $customer->is_sent_4s = 1;
            $customer->last_datetime_sent_4s = Carbon::now();
            $customer->save();
            Log::info('Gửi tin nhắn 4S tới Khách hàng : ' . $customer->name . 'SĐT' . $customer->phone . " : OK");
        } else {
            Log::info('Gửi tin nhắn 4S tới Khách hàng : ' . $customer->name . 'SĐT' . $customer->phone . " : Fail with code " . $result);
        }
    }
}
