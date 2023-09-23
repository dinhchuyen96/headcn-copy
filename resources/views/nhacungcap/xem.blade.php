@extends('layouts.master')

@section('title', 'Thông tin nhà cung cấp')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet"/>
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    <div>
        <div class="page-content fade-in-up">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Thông tin nhà cung cấp</div>
                </div>
                <div class="ibox-body">
                    <div class="form-group row">
                        <label for="SupplyCode" class="col-1 col-form-label ">Mã NCC</label>
                        <div class="col-5">
                            <input id="SupplyCode" value="{{empty($supply->code) ? '' : $supply->code}}" placeholder="Mã nhà cung cấp"
                                   type="text"
                                   class="form-control" readonly>
                        </div>
                        <label for="SupplyName" class="col-1 col-form-label ">Tên NCC</label>
                        <div class="col-5">
                            <input id="SupplyName" value="{{empty($supply->name) ? '' : $supply->name}}" placeholder="Tên nhà cung cấp"
                                   type="text"
                                   class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="PhoneNumber" class="col-1 col-form-label ">Số điện thoại</label>
                        <div class="col-5">
                            <input id="PhoneNumber" value="{{empty($supply->phone) ? '' : $supply->phone}}" type="number"
                                   placeholder="Số điện thoại"
                                   class="form-control" readonly>
                        </div>
                        <label for="Address" class="col-1 col-form-label">Địa chỉ</label>
                        <div class="col-5">
                            <input id="Address" value="{{empty($supply->address) ? '' : $supply->address}}" placeholder="Địa chỉ" type="text"
                                   class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Email" class="col-1 col-form-label ">Email</label>
                        <div class="col-5">
                            <input id="Email" value="{{empty($supply->email) ? '' : $supply->email}}" type="text" placeholder="Email"
                                   class="form-control" readonly>
                        </div>
                        <label for="SupplyProvince" class="col-1 col-form-label ">Thành phố/ Tỉnh </label>
                        <div class="col-5">
                            <input value="{{empty($supply->province_name) ? '' : $supply->province_name}}" type="text" placeholder="Thành phố/ Tỉnh"
                                   class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="SupplyUrl" class="col-1 col-form-label ">Trang chủ</label>
                        <div class="col-5">
                            <input id="SupplyUrl" value="{{empty($supply->url) ? '' : $supply->url}}" type="text" placeholder="Trang chủ"
                                   class="form-control" readonly>
                        </div>
                        <label for="SupplyDistrict" class="col-1 col-form-label ">Quận/ Huyện</label>
                        <div class="col-5">
                            <input value="{{empty($supply->district_name) ? '' : $supply->district_name}}" type="text" placeholder="Quận/ Huyện"
                                   class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-5">

                        </div>
                        <div class="col-md-1"></div>
                        <label for="SupplyProvince" class="col-1 col-form-label ">Phường/ Xã </label>
                        <div class="col-5">
                            <input value="{{empty($supply->ward_name) ? '' : $supply->ward_name}}" type="text" placeholder="Phường/ Xã"
                                   class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row justify-content-center btn-group-mt">
                        <a type="button" href="{{route('nhacungcap.dsnhacungcap.index')}}" class="btn btn-default mr-3" ><i class="fa fa-arrow-left"></i> Trở lại</a>
                        <a type="button" href="{{route('nhacungcap.capnhat.index',['id'=>$id])}}" class="btn btn-primary text-light float-right" ><i class="fa fa-edit"></i> Sửa</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
