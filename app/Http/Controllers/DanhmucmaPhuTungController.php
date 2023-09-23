<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Motorbike;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Route;

class DanhMucMaPhuTungController extends Controller
{
    public function index(){
         return view('danhmucmaphutung.dsdanhmucmaphutung');
    }

    public function create()
    {
        return view('danhmucmaphutung.themmoi');
    }
    public function edit($id)
    {
        return view('danhmucmaphutung.capnhat',compact("id"));
    }




}
