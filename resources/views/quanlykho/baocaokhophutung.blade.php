@extends('layouts.master')

@section('title', 'Báo cáo kho phụ tùng')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }
    </style>
@endsection
@section('content')
    @livewire('quanlykho.report-accessories')
@endsection
