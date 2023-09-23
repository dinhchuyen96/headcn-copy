<?php

namespace App\Http\Controllers\Accessary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accessory;
class OrderBuyController extends Controller
{
    public function index(){
        return view('phutung.nhaphang');
    }

    public function show($id)
    {
        $data=Accessory::findOrFail($id);
        return view('phutung.nhaphang',compact('data'));
    }
    public function orderBuyList(){
        return view('phutung.dsdonhang');
    }
    public function orderBuyAccessary(){
        return view('phutung.dsphutungnhap');
    }

    public function baocao(){
        return view('phutung.baocaolailophutung');
    }
}
