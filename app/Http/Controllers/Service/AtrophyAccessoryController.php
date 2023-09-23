<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AtrophyAccessoryController extends Controller
{
    public function index()
    {
        return view('dichvu.phutungchothaythe');
    }
}
