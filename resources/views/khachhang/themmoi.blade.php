@extends('layouts.master')

@section('title', 'Thông tin khách hàng')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    {!! Form::open(['method' => 'POST',  'autocomplete' => "off",'route' => ['customers.store']]) !!}
        @include('khachhang._formCustomer')
    {!! Form::close() !!}

@endsection
