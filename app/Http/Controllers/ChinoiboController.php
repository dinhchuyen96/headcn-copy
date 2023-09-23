<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChinoiboController extends Controller
{
    public function index()
    {
        return view('chinoibo.index');
    }
    public function create()
    {
        return view('chinoibo.create');
    }
    public function edit($id)
    {
        return view('chinoibo.edit', ['feeOutId' => $id]);
    }
}
