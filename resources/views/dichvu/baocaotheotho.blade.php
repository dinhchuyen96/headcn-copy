@extends('layouts.master')

@section('title', 'Báo cáo doanh thu theo thợ')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }
    </style>
@endsection
@section('content')
    @livewire('service.report-worker')
@endsection
