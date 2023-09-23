@extends('layouts.master')

@section('title', 'Thông tin kho')
@section('css')
    <link href="{{ asset('assets/css/table-common.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }
        @media screen and (max-width: 1440px) {
        label.col-1 {
            padding:5px 0px 5px 10px;
        }
    }

    </style>
@endsection
@section('content')
    @livewire('kho.edit',['warehouse_id'=>$id])

@endsection
