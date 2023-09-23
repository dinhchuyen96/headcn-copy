@extends('layouts.master')

@section('title', 'Cập nhật thông tin đơn hàng phụ tùng')
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
        <h1 class="page-title">Cập nhật thông tin đơn hàng phụ tùng</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Cập nhật thông tin đơn hàng phụ tùng</li>
        </ol>
    </div>
    {!! Form::model($data, ['method' => 'POST', 'autocomplete' => "off",'route' => ['order-buy-accessories.update', $data->id]]) !!}
        @include('phutung.order-accessories._formOrderBuyAccessories')
    {!! Form::close() !!}

@endsection
