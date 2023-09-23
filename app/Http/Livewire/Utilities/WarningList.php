<?php

namespace App\Http\Livewire\Utilities;

use App\Models\HMSPartNotAllowUrgent;
use App\Models\HMSPartOrderPlan;
use App\Models\HMSPartOrderPlanDetail;
use App\Models\HMSReceivePlan;
use App\Models\HMSServiceResults;
use App\Models\Order;
use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Http\Controllers\API\SmsGatewayController;
use App\Models\OrderDetail;
use App\Models\Periodic;
use App\Models\RepairBill;
use App\Models\AverageRevenue;
use App\Models\Customer;
use App\Models\HMSPayment;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\RepairTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class WarningList extends Component
{
    public function render()
    {
        //
        $get_first_day = date('Y-m-01');
        $today = date('Y-m-d');
        //Cảnh báo số xe nhập về không báo cáo đúng thời gian
        $count_hmsRP = HMSReceivePlan::query()->where('eta', '>=', $get_first_day)->where('eta', '<=', $today)->where('arrival_date', '>', 'eta')->count();
        //Cảnh báo số lượng dịch vụ có khả năng không đúng hạn
        $countWarrantyClaim = HMSServiceResults::query()->whereRaw('DATEDIFF(sr_closed_date_time,sr_created_date_time) > 5')->count();

        //Cảnh báo số lượng phụ tùng đặt urgent trong tháng
        $countUrgents = HMSPartNotAllowUrgent::query()
            ->with('orderPlanDetails.orderPlan')
            ->whereHas('orderPlanDetails', function ($query2) use ($get_first_day, $today) {
                return $query2->whereHas('orderPlan', function ($query3) use ($get_first_day, $today) {
                    return $query3->whereDate('po_date', '>=', $get_first_day)
                        ->whereDate('po_date', '<=', $today)
                        ->where('part_order_type', 'LIKE', '%Urgent Order%');
                });
            })
            ->count();

        //Tỉ lệ dịch vụ chấp thuận,tổng yêu cầu dịch vụ
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

        if ($serviceInMonth != 0)
            $applyInsurance = round(($applyService / $serviceInMonth) * 100);
        else $applyInsurance = 0;

        //Tỉ lệ doanh thu phụ tùng

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


        //Tỷ lệ bỏ hàng,nộp tiền muộn
        $deliveryNo = HMSReceivePlan::query()
            ->where('stock_out_date_time', '>=', $get_first_day)
            ->where('stock_out_date_time', '<=', $today)
            ->get()->all();
        $countLatePayment = 0;
        foreach ($deliveryNo as $value) {
            $stock_out_date_time = $value->stock_out_date_time;
            $diffDay =  strtotime('-3 day', strtotime($stock_out_date_time));
            $lateDay = date('Y-m-d', $diffDay);
            $hvn_lot_number = $value->hvn_lot_number;
            $deliveryNos = HMSPayment::query()
                ->where('delivery_nos', 'like', '%' . $hvn_lot_number . '%')
                ->where('credit_date', '>', $lateDay)
                ->get()->all();
            if (count($deliveryNos) > 0) {
                $countLatePayment++;
            }
        }

        //Tổng bán hàng
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

        $totalSale = $totalPriceAccessory + $totalPriceRepair + $totalPriceMotor;

        //Khach hang no qua han

        $day =  strtotime('-7 day', strtotime($today));
        $diffDate  = date('Y-m-d', $day);

        $OverdueCustomer = DB::table('customers')
            ->whereNull('customers.deleted_at')
            ->leftJoin('orders', 'orders.customer_id', '=', 'customers.id')
            ->leftJoin('receipts', 'receipts.customer_id', '=', 'customers.id')
            ->whereNull('orders.deleted_at')
            ->whereDate('orders.created_at', '<=', $today)
            //->whereRaw("((receipt_date <= '{$today}' AND receipt_date > '{$diffDate}') OR receipt_date IS NULL)")
            ->selectRaw("max(receipts.receipt_date) as receipt_date, SUM(orders.total_money) as sum_total_money, SUM(orders.total_money) - SUM(receipts.money) AS remainAmount")
            ->selectRaw('customers.id, customers.name,customers.code,customers.phone')
            ->groupByRaw('customers.id, customers.name, customers.code ,customers.phone')
            ->havingRaw("max(receipts.receipt_date) <= '{$diffDate}'")
            ->havingRaw('remainAmount > 0')
            //->orHavingRaw('remainAmount is null')
            ->get();
        $totalOverDueCustomer = $OverdueCustomer->count();
        return view('livewire.utilities.warning-list', [
            'count_hmsRP' => $count_hmsRP,
            'countWarrantyClaim' => $countWarrantyClaim,
            'countUrgents' => $countUrgents,
            'applyInsurance' => $applyInsurance ?? 0,
            'averageRate' => $averageRate,
            'countLatePayment' => $countLatePayment,
            'totalQuantityAccessory' => $totalQuantityAccessory,
            'totalQuantityRepair' => $totalQuantityRepair,
            'totalQuantityMotor' => $totalQuantityMotor,
            'totalSale' => $totalSale,
            'totalOverDueCustomer' => $totalOverDueCustomer
        ]);
    }
}
