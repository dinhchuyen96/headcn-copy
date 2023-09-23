<?php

namespace App\Console\Commands;

use App\Enum\EOrderDetail;
use App\Http\Controllers\API\SmsGatewayController;
use App\Models\HMSPartNotAllowUrgent;
use App\Models\OrderDetail;
use App\Models\RepairTask;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TotalSaleSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:TotalSaleSMS';

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
        Log::info('TotalSaleSMS handle() bắt đầu TotalSaleSMS');
        $headCodeSMS = env('APP_HEADCODE');
        $daySMS = date("d", strtotime(date('Y-m-d')));
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        $countAccessoryRetail = OrderDetail::where('category', EOrderDetail::CATE_ACCESSORY)->where('type', EOrderDetail::TYPE_BANLE)->whereDate('created_at', $today)->count();
        $countAccessoryWholesale = OrderDetail::where('category', EOrderDetail::CATE_ACCESSORY)->where('type', EOrderDetail::TYPE_BANBUON)->whereDate('created_at', $today)->count();
        $totalQuantityAccessory = $countAccessoryRetail + $countAccessoryWholesale;
        $totalPriceAccessoryRetail = OrderDetail::where('category', EOrderDetail::CATE_ACCESSORY)
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->whereDate('created_at', $today)
            ->select(DB::raw('(quantity * price) as total'))->get('total')->sum('total');
        $totalPriceAccessoryWholesale = OrderDetail::where('category', EOrderDetail::CATE_ACCESSORY)
            ->where('type', EOrderDetail::TYPE_BANBUON)
            ->whereDate('created_at', $today)
            ->select(DB::raw('(quantity * price) as total'))->get('total')->sum('total');
        $totalPriceAccessory = $totalPriceAccessoryRetail + $totalPriceAccessoryWholesale;

        //
        $totalQuantityRepair = RepairTask::whereDate('created_at', $today)->count();
        $totalPriceRepair = RepairTask::whereDate('created_at', $today)->get('price')->sum('price');

        //
        $countMotorRetail = OrderDetail::where('category', EOrderDetail::CATE_MOTORBIKE)->where('type', EOrderDetail::TYPE_BANLE)->whereDate('created_at', $today)->count();
        $countMotorWholesale = OrderDetail::where('category', EOrderDetail::CATE_MOTORBIKE)->where('type', EOrderDetail::TYPE_BANBUON)->whereDate('created_at', $today)->count();
        $totalQuantityMotor = $countMotorRetail + $countMotorWholesale;
        $totalMPriceMotorRetail = OrderDetail::where('category', EOrderDetail::CATE_MOTORBIKE)
            ->where('type', EOrderDetail::TYPE_BANLE)
            ->whereDate('created_at', $today)
            ->select(DB::raw('(quantity * price) as total'))->get('total')->sum('total');
        $totalPriceMotorWholesale = OrderDetail::where('category', EOrderDetail::CATE_MOTORBIKE)
            ->where('type', EOrderDetail::TYPE_BANBUON)
            ->whereDate('created_at', $today)
            ->select(DB::raw('(quantity * price) as total'))->get('total')->sum('total');
        $totalPriceMotor = $totalMPriceMotorRetail + $totalPriceMotorWholesale;
        $totalQuantitySMS = $totalQuantityAccessory + $totalQuantityRepair + $totalQuantityMotor;

        $totalSaleSMS = $totalPriceAccessory + $totalPriceRepair + $totalPriceMotor;
        $request = new Request();
        $request->smsid = env('SMS_ID_TOTAL_SALE', 205003);
        $request->param = $headCodeSMS . "__" . $daySMS . "__" . $totalQuantitySMS . "__" . $totalSaleSMS;
        $api = new SmsGatewayController();
        $api->send($request);
        Log::info(' WarningUrgentSMS handle() kết thúc WarningUrgentSMS');
    }
}
