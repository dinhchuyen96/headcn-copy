<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuanlykhoController extends Controller
{
    //
    public function baocaokhoxemay()
    {
        return view('quanlykho.baocaokhoxemay');
    }
    public function thaydoiphutungxe()
    {
        return view('quanlykho.thaydoiphutungxe');
    }


    public function chuyenkhoxemay()
    {
        return view('quanlykho.chuyenkhoxemay');
    }
    public function baocaokhophutung()
    {
        return view('quanlykho.baocaokhophutung');
    }
    public function baocaosudungphutung()
    {
        return view('quanlykho.baocaosudungphutung');
    }
    public function chuyenkhophutung()
    {
        return view('quanlykho.chuyenkhophutung');
    }
    public function lichsuchuyenkhoxe()
    {
        return view('quanlykho.lichsuchuyenkhoxe');
    }
    public function lichsuchuyenphutung()
    {
        return view('quanlykho.lichsuchuyenphutung');
    }
    public function baocaoxetheomodel()
    {
        return view('quanlykho.baocaoxetheomodel');
    }
    public function baocaophutungtheorank()
    {
        return view('quanlykho.baocaophutungtheorank');
    }
    public function quatang()
    {
        return view('quanlykho.quatang');
    }
    public function giftShow($id)
    {
        return view('quanlykho.xemquatang', compact('id'));
    }
}
