@extends('layouts.master')

@section('title', 'Cập nhật thông tin khách hàng')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    
    {!! Form::model($data, ['method' => 'POST', 'autocomplete' => "off",'route' => ['customers.update', $data->id]]) !!}
        @include('khachhang._formCustomer')
    {!! Form::close() !!}

@endsection
