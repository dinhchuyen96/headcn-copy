<?php

namespace App\Http\Controllers\MTOCS;

use App\Http\Controllers\Controller;
use App\Models\Mtoc;
use Illuminate\Http\Request;

class MTOCController extends Controller
{
    public function index()
    {
        return view('MTOC.dsMTOC');
    }
    public function create()
    {
        return view('MTOC.themmoi');
    }
    public function edit($id)
    {
        $mtoclist = Mtoc::findOrFail($id);
        return view('MTOC.suaMTOC', ['mtoclist' => $mtoclist]);
    }
    public function show($id)
    {
        $mtoclist = Mtoc::findOrFail($id);
        return view('MTOC.xemMTOC', ['mtoclist' => $mtoclist]);
    }
}
