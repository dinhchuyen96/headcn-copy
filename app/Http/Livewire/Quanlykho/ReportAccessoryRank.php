<?php

namespace App\Http\Livewire\Quanlykho;

use App\Http\Livewire\Base\BaseLive;
use Carbon\Carbon;

use App\Exports\AccessoryRankExport;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use App\Models\CategoryAccessory;
use App\Models\Accessory;
use App\Models\OrderDetail;
use App\Enum\ETranferType;
use App\Enum\EOrderDetail;


class ReportAccessoryRank extends BaseLive
{
    //tudn fix
    public $reportdate;
    public $reportmonth;
    public $searchPartNo ;
    public $exportaccessories;
    public $total=0;

    public function mount(){
        $this->reportdate = carbon::Today()->format('m/Y');
    }

    public function render()
    {

        $reportmonth = $this->reportdate;
        //1. tinh luy ke ban hang trong 6 thang tinh tu thoi diem hien tai
        $stringmonth = Str::substr($reportmonth, 0, 2);
        $stringyear = Str::substr($reportmonth, 3, 4);
        $before6month =$this->reduceMonth( $stringmonth."/01/".$stringyear,-5);

        $temptodate =Carbon::createFromFormat('m/d/Y', $stringmonth."/01/".$stringyear);
        $todate = $temptodate->addMonths(1)->addDays(-1)->format('Y-m-d');
        $fromdate =$temptodate->addMonths(-5)->format('Y-m-d') ;


        //lap danh sach cac pt
        //tinh trung binh luong pt ban ra va % ban ra cua cac pt theo luong ban ra
        //sap xep cac pt theo luong ban ra tu cao den thap
        // tinh % luy ke tu danh sach
        $queryorderdetail = DB::table("order_details")
        ->whereNull('order_details.deleted_at')
        ->where('order_details.category','=',EOrderDetail::CATE_ACCESSORY)
        ->where('order_details.status','=',EOrderDetail::STATUS_SAVED)
        ->where('order_details.type','<>',EOrderDetail::TYPE_NHAP)
        ->whereDate('order_details.created_at','>=',$fromdate)
        ->whereDate('order_details.created_at','<=',$todate);
        $queryorderdetail->select('order_details.code','order_details.quantity');

        $query = DB::table("category_accessories")
        ->whereNull('category_accessories.deleted_at') ;

        if(isset($this->searchPartNo)&& !empty(trim($this->searchPartNo)))
        {
            $searchPartNo = trim($this->searchPartNo);
            $query->where('category_accessories.code' , 'like' ,'%'. $searchPartNo .'%');
            $query->orWhere('category_accessories.name' , 'like' ,'%'. $searchPartNo .'%');
        }

        $query->leftJoinSub($queryorderdetail,'order_details',function($join){
            $join->on('order_details.code','=','category_accessories.code');
        })
        ->select(
            'category_accessories.id as id',
            'category_accessories.code as code',
            'category_accessories.name as name',
            DB::raw('sum(COALESCE(order_details.quantity,0)) as quantity')
        )
        ->groupBy('category_accessories.id',
        'category_accessories.code',
        'category_accessories.name')
        ->orderBy('order_details.quantity');

        $this->total = $query->sum('order_details.quantity');
        $this->exportaccessories = $query->get();
        $accessories = $query->paginate($this->perPage);
        $accumulateper = 0;
        $percentage = 0;
        $rank = 'A'; //default =A
        return view('livewire.quanlykho.baocaophutungtheorank', [
            'accessories'=>$accessories,
            'total'=>$this->total,
            'percentage'=>$percentage,
            'accumulateper' =>$accumulateper,
            'rank' =>$rank
        ]);
    }

    //return prev month by Num
    public function reduceMonth($month,$num){
        $newmonth =Carbon::createFromFormat('m/d/Y', $month);
        $prevmonth = $newmonth->addMonths($num);
        $sprevmonth = $prevmonth->format('m/Y');
        return $sprevmonth;
    }

    public function export()
    {
        if (isset($this->exportaccessories) && count($this->exportaccessories) > 0 ) {
            # code...
            return Excel::download(new AccessoryRankExport(
                $this->exportaccessories,
                $this->total
            ), 'baocaophutungtheorank_' . date('Y-m-d-His') . '.xlsx');
        }else {
            # code...
            $this->dispatchBrowserEvent('show-toast',
            ['type' => 'error', 'message' => 'Không có dữ liệu export']);
        }

    }
}
