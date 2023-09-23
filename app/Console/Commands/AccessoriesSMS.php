<?php

namespace App\Console\Commands;

use App\Enum\EOrderDetail;
use App\Http\Controllers\API\SmsGatewayController;
use App\Models\AverageRevenue;
use App\Models\OrderDetail;
use App\Models\Periodic;
use App\Models\RepairBill;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccessoriesSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:AccessoriesSMS';

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
    {   Log::info('AccessoriesSMS handle() bắt đầu AccessoriesSMS');
        $headCodeSMS = env('APP_HEADCODE');
        $monthSMS = date("m", strtotime(date('Y-m-d')));
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        $totalMoneyAccessoriesBanLe = OrderDetail::where('category', EOrderDetail::CATE_ACCESSORY)->where('type', EOrderDetail::TYPE_BANLE)->where('status', EOrderDetail::STATUS_SAVED)->select(DB::raw('(quantity * actual_price) as total'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->get('total')->sum('total');
        $totalMoneyAccessories = OrderDetail::where('category', EOrderDetail::CATE_REPAIR)->select(DB::raw('(quantity * price) as total'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->get('total')->sum('total');
        $countPeriodic = Periodic::query()->whereMonth('check_date', date('m'))->whereYear('created_at', date('Y'))->count();
        $countRepair = RepairBill::query()->whereMonth('in_factory_date', date('m'))->whereYear('created_at', date('Y'))->count();
        $totalService = $countPeriodic + $countRepair;
        if ($totalService != 0) {
            $averageRevenueCurrentMonth = ($totalMoneyAccessories + $totalMoneyAccessoriesBanLe) / $totalService;
        } else {
            $averageRevenueCurrentMonth = 0;
        }

        AverageRevenue::updateOrCreate([
            'month' => date('m'),
            'year' => date('Y'),
        ], [
            'average_rate' => $averageRevenueCurrentMonth,
        ]);
        $averageRevenueBeforePeriod = 0;

        for ($i = date('m'); $i <= 12; $i++) {
            $averageRevenueBeforePeriod += AverageRevenue::where('month', $i)->where('year', date('Y') - 1)->first()->average_rate ?? 0;
        }
        for ($i = 1; $i <= (date('m') + 6) % 12 - 1; $i++) {
            $averageRevenueBeforePeriod += AverageRevenue::where('month', $i)->where('year', date('Y'))->first()->average_rate ?? 0;
        }
        $averageRate = 0;
        if ($averageRevenueBeforePeriod != 0) {
            $averageRate = number_format($averageRevenueCurrentMonth * 100 / ($averageRevenueBeforePeriod / 6), 2);
        }
        $paramSMS = $averageRate;
        $request = new Request();
        $request->smsid = env('SMS_ID_ACCESSORIES', 205002);
        $request->param = $headCodeSMS . "__" . $monthSMS . "__" . $paramSMS;
        $api = new SmsGatewayController();
        $api->send($request);
        Log::info( 'AccessoriesSMS handle() kết thúc AccessoriesSMS');


    }
}
