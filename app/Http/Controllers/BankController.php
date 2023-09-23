<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccountMoney;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        return view('Bank.dsBank');
    }
    public function create()
    {
        return view('Bank.themmoi');
    }
}
