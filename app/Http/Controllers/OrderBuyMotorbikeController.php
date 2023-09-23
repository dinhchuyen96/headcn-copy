<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderBuyMotorbikeController extends Controller
{
    //
    public function index()
    {

        return view('banhang.dsdonhang');
    }

    public function show($id)
    {
        $data = Order::findOrFail($id);

        return view('banhang.order-motorbike.show', compact('data'));
    }
    public function print($id)
    {
        return view('banhang.order-motorbike.phieumua', compact('id'));
    }

    public function edit($id)
    {
        $data = Order::findOrFail($id);

        return view('banhang.order-motorbike.edit', compact('data'));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'type',
            'total_money' => 'required|numeric',
            'order_no',
            'total_items' => 'required|numeric',
            'tax' => 'required|numeric',
            'discount' => 'required|numeric',
            'status',
            'date_payment' => 'date_format:Y-m-d H:i:s',
        ], [], [
            'order_no' => 'Mã đơn hàng',
            'tax' => 'Thuế',
            'total_items' => 'Số lượng',
            'discount',
            'type' => 'Phân loại mua hàng',
            'total_money' => 'Tổng tiền',
            'status' => 'Trạng thái thanh toán',
            'date_payment' => 'Hạn thanh toán',
        ]);

        $od = Order::findOrFail($id);
        $od->type = $request->type;
        $od->total_money = $request->total_money;
        $od->tax = $request->tax;
        $od->discount = $request->discount;
        $od->status = $request->status;
        $od->total_items = $request->total_items;
        $od->date_payment = $request->date_payment;

        $od->save();

        return redirect()->route('motorbikes.orders');
    }
}
