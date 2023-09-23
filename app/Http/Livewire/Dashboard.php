<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Base\BaseLive;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\HMSReceivePlan;
use Livewire\Component;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Carbon\Carbon;

//tudn add carbon
class Dashboard extends BaseLive
{
    public $receivedate;
    protected $listeners = [
        'changereceiveDate' => 'changereceiveDate',
    ];
    public function render(Request $request)
    {
        $arrayBox = $this->dataHeaderDashboard();
        $order_detail = OrderDetail::where('status', 1)
        ->where('category', 2)->whereIn('type', [1, 2])
        ->whereDate('created_at', '=', date('Y-m-d'))->get();

        $total = $order_detail->count();
        $currentPage = $request->input("page") ?? 1;
        $perPage = 50;
        $startingPoint = ($currentPage * $perPage) - $perPage;
        $order_detail = $order_detail->skip($startingPoint)->take($perPage);
        $order_detail = new Paginator($order_detail, $total, $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        //Get data  of receive plan in date
        if (isset($this->receivedate)) {
            # code...
            $date = str_replace('/', '-', $this->receivedate);
            $today =date("Y-m-d", strtotime($date));
        }else $today =date('Y-m-d');

        $hms_receive_plan = DB::table('hms_receive_plan')
        ->where(DB::raw('STR_TO_DATE(hms_receive_plan.eta, "%d/%m/%Y")' ), '=',$today)
        ->leftJoin('motorbikes', 'hms_receive_plan.chassic_no', '=', 'motorbikes.chassic_no')
        ->select('hms_receive_plan.*','motorbikes.buy_date')
        ->get();
        $hms_total = $hms_receive_plan->count();
        $hms_currentPage = $request->input("page") ?? 1;
        $hms_perPage = 50;
        $hms_startingPoint = ($hms_currentPage * $hms_perPage) - $hms_perPage;
        $hms_receive_plan = $hms_receive_plan->skip($hms_startingPoint)->take($hms_perPage);
        $hms_receive_plan = new Paginator($hms_receive_plan, $hms_total, $hms_perPage, $hms_currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
        return view('livewire.dashboard',
            ['arrayBox'=>$arrayBox,
            'order_detail'=> $order_detail,
            'hms_receive_plan'=>$hms_receive_plan ,
            ]);
    }

    /**
     *
     */
    public function changereceiveDate($receivedate){
        $this->receivedate =$receivedate ;
    }

    public function dataHeaderDashboard()
    {
        $array = [0, 0, 0, 0, 0];
        $today =date('Y-m-d');
        $startmonth = Carbon::now()->startOfMonth();
        $receive_plan_count = DB::table('hms_receive_plan')->where('stock_out_date_time', '!=', null)
            //->whereDate('eta', '<=', date('Y-m-d'))
            ->where(DB::raw('STR_TO_DATE(hms_receive_plan.eta, "%d/%m/%Y")' ), '<=',$today)
            ->where(DB::raw('STR_TO_DATE(hms_receive_plan.eta, "%d/%m/%Y")' ), '>=',$startmonth)
            ->count();

        $receive_actual_count = OrderDetail::where('status', 1)
        ->where('deleted_at','is', null)
        ->where('category', 2)->where('type', 3)->where('buy_date', '=', date('Y-m-d'))
        ->whereHas('order', function ($query) {
            $query->whereHas('supplier', function ($q) {
                $q->where('suppliers.code', 'HVN');
            });
        })->count();

        $array[0] = $receive_plan_count ? $receive_actual_count . ' /' . $receive_plan_count : 0;

        $motoCount = Order::where('total_items', '!=', null)
            ->where('deleted_at','is',null)
            ->where('customer_id','is not',null)
            ->where('total_money', '!=', null)->where('category', 2)
            ->whereDate('created_at', date('Y-m-d'))
            ->whereIn('order_type', [1,2])
            ->select(DB::raw('sum(total_items) as tongsoluong'), DB::raw('sum(total_money) as tongdoanhthu'))
            ->first();


       $array[1] = isset($motoCount) ? numberFormat($motoCount->tongsoluong) .'/'.numberFormat($motoCount->tongdtongdoanhthuoangthu)  : 0;

        $accessories = OrderDetail::select(DB::raw('sum(quantity) as soluongnhap'))
            ->where('status', 1)
            ->where('deleted_at','is', null)
            ->where('category', 1)
            ->where('type', 3)
            ->where('buy_date', '=', date('Y-m-d'))
            ->first();

        $array[2] = $accessories && $accessories->soluongnhap ? numberFormat($accessories->soluongnhap) : 0;

        $accessoriesSaleInday =OrderDetail::select(DB::raw('count(code) as soluongptban'))
        ->where('status', 1)
        ->where('category', 1)
        ->whereIn('type', [1,2])
        ->where('deleted_at','is', null)
        ->where('created_at', '=', date('Y-m-d'))
        ->where('actual_price', '!=', 'null')
        ->where('quantity', '!=', 'null')
        ->first();
        $numaccessoriesSaleInday = isset($accessoriesSaleInday) ? number_format($accessoriesSaleInday->soluongptban) :0;
        $accessoriesMoney = OrderDetail::select(DB::raw('sum(quantity * actual_price) as tongdoanhthu'))
            ->where('status', 1)
            ->where('category', 1)
            ->whereIn('type', [1,2])
            ->where('deleted_at','is', null)
            ->where('created_at', '=', date('Y-m-d'))
            ->where('actual_price', '!=', 'null')
            ->where('quantity', '!=', 'null')
            ->first();
        $numaccessoriesMoney = isset($accessoriesMoney) ? numberFormat($accessoriesMoney->tongdoanhthu) :0 ;
        $array[3] = $numaccessoriesSaleInday . '/ ' . $numaccessoriesMoney;

        $periodic = DB::table('periodic_checklist')
        ->where('deleted_at','is',null)
        ->whereMonth('check_date', date('m'))->whereYear('check_date', date('Y'))
            ->select(
                DB::raw("COUNT(CASE WHEN periodic_level = 1 THEN 1 END) as L1"),
                DB::raw("COUNT(CASE WHEN periodic_level = 2 THEN 1 END) as L2"),
                DB::raw("COUNT(CASE WHEN periodic_level = 3 THEN 1 END) as L3"),
                DB::raw("COUNT(CASE WHEN periodic_level = 4 THEN 1 END) as L4"),
                DB::raw("COUNT(CASE WHEN periodic_level = 5 THEN 1 END) as L5"),
                DB::raw("COUNT(CASE WHEN periodic_level = 6 THEN 1 END) as L6")
            )->get()->toArray();
        $array[4] = $periodic[0];
        return $array;
    }
}
