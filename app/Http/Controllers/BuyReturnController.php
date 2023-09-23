<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class BuyReturnController extends Controller
{
    public function index()
    {
        return view('Return.buy_index');
    }
}
