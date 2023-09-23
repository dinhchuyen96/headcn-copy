<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstallmentCompanyController extends Controller
{
    public function index()
    {
        return view('installmentcompany.list');
    }
    public function create()
    {
        return view('installmentcompany.add');
    }
    public function edit($id)
    {
        return view('installmentcompany.edit',['id' => $id]);
    }
}
