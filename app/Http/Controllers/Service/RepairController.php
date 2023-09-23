<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index()
    {
        return view('dichvu.suachuathongthuong.themmoi');
    }
    public function show($id)
    {
        return view('dichvu.suachuathongthuong.xem', compact('id'));
    }
    public function edit($id)
    {
        return view('dichvu.suachuathongthuong.sua', compact('id'));
    }

    public function reportByWork()
    {
        return view('dichvu.baocaotheocongviec');
    }

    public function reportByWorker()
    {
        return view('dichvu.baocaotheotho');
    }

    public function report()
    {
        return view('dichvu.baocao');
    }
}
