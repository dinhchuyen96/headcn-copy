<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerCareController extends Controller
{
    public function index()
    {
        return view('dichvu.chamsockhachhang');
    }
    public function serviceSupport()
    {
        return view('dichvu.chamsockhachhangcu');
    }
    public function contactHistory($id)
    {
        return view('dichvu.lichsulienhekhachhangcu', ['customerId' => $id]);
    }

    public function dslienhekhachhang(){
        return view('dichvu.dslienhekhachhang');
    }
}
