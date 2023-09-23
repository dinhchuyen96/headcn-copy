<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;


class PhuTungController extends Controller
{
    public function index(){

        return view('phutung.banbuon');
    }
    public function banle(){

        return view('phutung.banle');
    }

    public function inhoadonbanle($id) {
        $query = Order::with(['customer'])->findOrFail($id);
        $count = $query->count();
        $data = [
            'type' => $query->type,
            'count' => $query->count(),
            'name' => $query->customer->name,
            'address' => $query->customer->address,
            'totalPrice' => $query->total_money,
        ];
        $query1 = OrderDetail::where('order_id', $id)->get();
        $details = [
            'length' => $query1->count(),
            'id' => $query1->pluck('id'),
            'price' => $query1->pluck('actual_price'),
            'accessoryNumber' => $query1->pluck('code'),
            'accessoryName' => $query1->pluck('name'),
            'qty' => $query1->pluck('quantity'),
            'warehouseName' => $query1->pluck('warehouse.name'),
            'wareHousePositionName' => $query1->pluck('positioninwarehouse.name')
        ];

        // $order_details = Orderdetail::where('order_details.order_id',$id)->get();
        // $details = [
        //     'price' => $order_details->actual_price,
        //     'accessoryNumber' => $order_details->code,
        //     'accessoryName' => $order_details->name,
        //     'qty' => $order_details->quantity,
        //     'total' => $order_details->quantity * $order_details->actual_price,
        // ];
        
        return view('phutung.printhoadonbanle', ['count' => $count,'data' => $data, 'details' => $details]);
    }

    public function inhoadonbanbuon($id) {
        $query = Order::with(['customer'])->findOrFail($id);
        $count = $query->count();
        $data = [
            'count' => $query->count(),
            'name' => $query->customer->name,
            'address' => isset($query->customer->address, $query->customer->address),
            'totalPrice' => $query->total_money,
        ];
        $query1 = OrderDetail::where('order_id', $id)->get();
        $details = [
            'length' => $query1->count(),
            'id' => $query1->pluck('id'),
            'price' => $query1->pluck('actual_price'),
            'accessoryNumber' => $query1->pluck('code'),
            'accessoryName' => $query1->pluck('name'),
            'qty' => $query1->pluck('quantity'),
            'warehouseName' => $query1->pluck('warehouse.name'),
            'wareHousePositionName' => $query1->pluck('positioninwarehouse.name')
        ];

        // $order_details = Orderdetail::where('order_details.order_id',$id)->get();
        // $details = [
        //     'price' => $order_details->actual_price,
        //     'accessoryNumber' => $order_details->code,
        //     'accessoryName' => $order_details->name,
        //     'qty' => $order_details->quantity,
        //     'total' => $order_details->quantity * $order_details->actual_price,
        // ];
        
        return view('phutung.printhoadonbanle', ['count' => $count,'data' => $data, 'details' => $details]);
    }
}
