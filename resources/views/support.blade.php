@extends('layouts.master')

@section('title', 'Hỗ trợ')

@section('content')
    <div class="row justify-content-center">
        <h1>Thông tin hỗ trợ</h1>
    </div>
    <div class="row pt-3 justify-content-center">
        <p class="font-weight-bold">Tên công ty: Công ty TNHH ETS</p>
    </div>
    <div class="row justify-content-center">
        <p class="font-weight-bold">Số điện thoại: 0968 824 081</p>
    </div>
    <div class="row justify-content-center">
        <p class="font-weight-bold">Địa chỉ: Phạm Văn Đồng, Hà Nội</p>
    </div>
    <div class="row justify-content-center">
        <p class="font-weight-bold">Email: ets@gmail.com</p>
    </div>
    <div class="row justify-content-center">
        <p class="font-weight-bold">Hỗ trợ kỹ thuật viên: Lê Minh Quân</p>
    </div>

    <div class="row justify-content-center pt-3">
        <a class="btn btn-primary" href="{{ route('dashboard') }}"> Back</a>
    </div>
@endsection
