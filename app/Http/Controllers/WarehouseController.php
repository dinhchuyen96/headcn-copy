<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        return view('kho.danhsach');
    }

    public function create()
    {
        return view('kho.themmoi');
    }

    public function edit($id)
    {
        return view('kho.capnhat', compact('id'));
    }

    public function giftlist(Request $request)
    {
        return view('kho.danhsachgift');
    }
}
