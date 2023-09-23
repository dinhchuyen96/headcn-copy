<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;

class OtherServiceController extends Controller
{
    public function index()
    {
        return view('dichvu.dichvukhac.index');
    }

    public function create()
    {
        return view('dichvu.dichvukhac.create');
    }

    public function show($id)
    {
        return view('dichvu.dichvukhac.show', compact('id'));
    }

    public function edit($id)
    {
        return view('dichvu.dichvukhac.edit', compact('id'));
    }
}
