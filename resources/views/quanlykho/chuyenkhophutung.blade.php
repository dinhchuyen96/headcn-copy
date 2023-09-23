@extends('layouts.master')

@section('title', 'Chuyển kho phụ tùng')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

        .fa-star {
            color: red;
            font-size: 10px;
        }

    </style>
@endsection
@section('content')
    @livewire('quanlykho.transfer-accessories')
@endsection
