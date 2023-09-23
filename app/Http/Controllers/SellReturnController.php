<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class SellReturnController extends Controller
{
    public function index()
    {
        return view('Return.sell_index');
    }
    public function create()
    {
        return view('Return.sell_create');
    }
    public function edit()
    {
        return view('Return.sell_edit');
    }
}
