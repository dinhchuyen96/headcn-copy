<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LichsuNhapxuatNgoaileController extends Controller
{
    public function index(Request $request)
    {
        return view('quanlykho.lichsunhapxuatngoaile');
    }
}
