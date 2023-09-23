@extends('layouts.master')

@section('title', 'Danh Mục Mã Phụ Tùng')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')

@livewire('danh-muc-ma-phu-tung.create')

@endsection
