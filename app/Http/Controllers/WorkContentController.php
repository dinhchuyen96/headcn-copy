<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccountMoney;
use Illuminate\Http\Request;

class WorkContentController extends Controller
{
    public function index()
    {
        return view('workcontent.list');
    }
    public function create()
    {
        return view('workcontent.add');
    }
    public function edit($id)
    {
        return view('workcontent.edit', ['id' => $id]);
    }
}
