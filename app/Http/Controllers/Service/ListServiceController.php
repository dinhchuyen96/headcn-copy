<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ListServiceController extends Controller
{
    public function index(){
      
        return view('dichvu.dsdonhang');
    }
    public function print($id){
        return view('dichvu.phieusuachua', compact('id'));
    }

    public function printCheckNo(){
        return view('dichvu.phieuktdk');
    }
}
