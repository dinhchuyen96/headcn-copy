<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderBuyAccessoriesController extends Controller
{
    //
    public function index(){
        
        return view('phutung.dsdonhang');
    }

    public function show($id){
        $data = Order::findOrFail($id);

        return view('phutung.order-accessories.show', compact('data'));
    }

    public function edit($id){
        $data = Order::findOrFail($id);

        return view('phutung.order-accessories.edit', compact('data'));
    }

    public function update($id, Request $request){
        $this->validate($request, [
            'type' => 'required',
            'total' => 'required',
            'order_no',
            'total_items',
            'sub_total',
            'tax',
            'discount',
        ], [], [
            'type' => 'Phân loại mua hàng',
            'total' => 'Tổng tiền',
        ]);

        $od = Order::findOrFail($id);
        $od->type = $request->type;
        $od->total = $request->total;
        $od->save();

        return redirect()->route('accessories.orders');
    }
}
