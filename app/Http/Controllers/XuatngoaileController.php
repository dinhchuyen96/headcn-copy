<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class XuatngoaileController extends Controller
{
    public function index(Request $request)
    {
        return view('quanlykho.xuatngoaile');
    }
}
