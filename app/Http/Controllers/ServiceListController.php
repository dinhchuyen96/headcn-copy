<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ListService;
use App\Models\Order;
use Illuminate\Http\Request;

class ServiceListController extends Controller
{
    public function index()
    {
        return view('ServiceList.dsService');
    }
    public function create()
    {
        return view('ServiceList.themmoi');
    }
    public function edit($id)
    {
        $listServiceItem = ListService::findOrFail($id);
        return view('ServiceList.sua', ['listServiceItem' => $listServiceItem]);
    }
    public function updataWorkStatus(Request $request,$id){
        $order = Order::findOrFail($id);
        $order->work_status=$request->input("status");
        $order->save();
    }
}
