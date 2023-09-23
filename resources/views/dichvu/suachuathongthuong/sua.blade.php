@extends('layouts.master')

@section('title', 'Sửa chữa thông thường')
@section('css')
    <link href="{{ asset('assets/css/table-common.css') }}" rel="stylesheet" />
    <style>
        table,
        td,
        th {
            border: 1px solid black;
            height: 30px;
            text-align: center;
        }

    </style>
@endsection
@section('content')
    @livewire('service.sua-chua-thong-thuong.sua',['orderId'=>$id])
@endsection
