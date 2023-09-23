<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MantainController extends Controller
{
    public function index()
    {
        return view('dichvu.baoduongdinhki');
    }

    public function report()
    {
        return view('dichvu.report');
    }
}
