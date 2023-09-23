@extends('layouts.master')

@section('title', 'Cập nhật thông tin đơn hàng xe máy')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    <div class="page-heading">
        <h1 class="page-title">Cập nhật thông tin đơn hàng xe máy</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Cập nhật thông tin đơn hàng xe máy</li>
        </ol>
    </div>
    {!! Form::model($data, ['method' => 'POST', 'autocomplete' => "off",'route' => ['order-buy-motorbike.update', $data->id]]) !!}
        @include('banhang.order-motorbike._formOrderBuyMotorbike')
    {!! Form::close() !!}

@endsection
