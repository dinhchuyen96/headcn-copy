<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SupplierController extends Controller
{
    public function index(){
        return view('nhacungcap.dsnhacungcap');
    }
    public function create(){
        return view('nhacungcap.themmoi');
    }
    public function edit($id){
        return view('nhacungcap.capnhat',compact('id'));
    }
    public function show($id){
        $supply= Supplier::leftJoin('ex_province','ex_province.province_code','=','suppliers.province_id')
            ->join('ex_district','ex_district.district_code','=','suppliers.district_id')
            ->join('ex_ward','ex_ward.ward_code','=','suppliers.ward_id')
            ->where('suppliers.id',$id)
            ->select('suppliers.*',DB::raw('ex_province.name as province_name'),
                DB::raw('ex_district.name as district_name'),
                DB::raw('ex_ward.name as ward_name'))->first();
        if($supply == null) {
            $supply= Supplier::leftJoin('ex_province','ex_province.province_code','=','suppliers.province_id')
            ->join('ex_district','ex_district.district_code','=','suppliers.district_id')
            ->where('suppliers.id',$id)
            ->select('suppliers.*',DB::raw('ex_province.name as province_name'),
                DB::raw('ex_district.name as district_name'),
                DB::raw('0 as ward_name'))->first();
        }
        if($supply == null) {
            $supply= Supplier::leftJoin('ex_province','ex_province.province_code','=','suppliers.province_id')
            ->where('suppliers.id',$id)
            ->select('suppliers.*',DB::raw('ex_province.name as province_name'),
                DB::raw('0 as district_name'),
                DB::raw('0 as ward_name'))->first();
        }
        return view('nhacungcap.xem',compact('supply', 'id'));
    }

}
