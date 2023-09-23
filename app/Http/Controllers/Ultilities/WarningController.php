<?php

namespace App\Http\Controllers\Ultilities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WarningController extends Controller
{
    public function index(){
        return view('tienich.canhbao');
    }
    public function warningDetailWrongTime(){
        return view('tienich.canhbaochitiet_khongbaocaodungthoigian');
    }
    public function warningLatePayment(){
        return view('tienich.canhbaochitiet_noptienmuon');
    }
    public function warrantyClaim(){
        return view('tienich.canhbaochitiet_khieunaibaohanh');
    }
    public function warningUrgent(){
        return view('tienich.canhbaochitiet_canhbaourgent');
    }
    public function applyInsurance(){
        return view('tienich.canhbaochitiet_chapthuanbaohanh');
    }
    public function overdueCustomer(){
        return view('tienich.canhbaochitiet_khachhangnoquahan');
    }
}
