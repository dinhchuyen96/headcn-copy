@extends('layouts.master')

@section('title', 'Thông tin đơn hàng xe máy')
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
        <h1 class="page-title">Thông tin đơn hàng xe máy</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Thông tin đơn hàng hàng xe máy</li>
        </ol>
    </div>
    {!! Form::model($data, ['method' => 'POST', 'autocomplete' => "off"]) !!}
        @include('banhang.order-motorbike._formOrderBuyMotorbike')
    {!! Form::close() !!}

@endsection
