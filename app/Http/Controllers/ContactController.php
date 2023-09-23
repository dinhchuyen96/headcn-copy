<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.list');
    }
    public function create()
    {
        return view('contact.add');
    }
    public function edit($id)
    {
        return view('contact.edit', ['id' => $id]);
    }
}
