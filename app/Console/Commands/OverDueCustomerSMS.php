<?php

namespace App\Console\Commands;

use App\Enum\EOrderDetail;
use App\Http\Controllers\API\SmsGatewayController;
use App\Models\HMSPartNotAllowUrgent;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\RepairTask;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OverDueCustomerSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:OverDueCustomerSMS';

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
        Log::info('OverDueCustomerSMS handle() bắt đầu OverDueCustomerSMS');
        $headCodeSMS = env('APP_HEADCODE');
        $daySMS = date("d", strtotime(date('Y-m-d')));
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        $day =  strtotime('-7 day', strtotime($today));
        $diffDate  = date('Y-m-d', $day);
        $OverdueCustomer = DB::table('customers')
            ->whereNull('customers.deleted_at')
            ->leftJoin('orders', 'orders.customer_id', '=', 'customers.id')
            ->leftJoin('receipts', 'receipts.customer_id', '=', 'customers.id')
            ->whereNull('orders.deleted_at')
            ->whereDate('orders.created_at', '<=', $today)
            ->whereRaw("((receipt_date <= '{$today}' AND receipt_date > '{$diffDate}') OR receipt_date IS NULL)")
            ->selectRaw("SUM(orders.total_money) as sum_total_money, SUM(orders.total_money) - SUM(receipts.money) AS remainAmount")
            ->selectRaw('customers.id, customers.name,customers.code,customers.phone')
            ->groupByRaw('customers.id, customers.name, customers.code ,customers.phone')
            ->havingRaw('remainAmount > 0')
            ->orHavingRaw('remainAmount is null')
            ->get();
        $countCus = $OverdueCustomer->count();
        $totalMoneyNotReceipt = 0;
        $totalMoneyHaveReceipt = 0;
        foreach ($OverdueCustomer as $value) {
            if (empty($value->remainAmount)) {
                $totalMoneyNotReceipt += $value->sum_total_money;
            } else {
                $totalMoneyHaveReceipt += $value->remainAmount;
            }
        }
        $totalMoneyAmount = number_format($totalMoneyNotReceipt + $totalMoneyHaveReceipt);
        $request = new Request();
        $request->smsid = env('SMS_ID_OVER_DUE_CUSTOMER', 205004);
        $request->param = $headCodeSMS . "__" . $countCus . "__" . $totalMoneyAmount;
        $api = new SmsGatewayController();
        $api->send($request);
        Log::info(' OverDueCustomerSMS handle() kết thúc OverDueCustomerSMS');
    }
}
