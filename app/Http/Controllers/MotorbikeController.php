<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Motorbike;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Route;

class MotorbikeController extends Controller
{
    public function index(){
        return view('banhang.dsxenhap');
    }

    public function buy(){
        return view('banhang.nhaphang');
    }

    public function banBuon(){
        return view('banhang.banbuon');
    }

    public function banLe(){
        return view('banhang.banle');
    }

    /**
     * Bao gia xe may
     * phu tung
     * dich vu
     */
    public function proposal(){
        return view('banhang.baogia');
    }
}
