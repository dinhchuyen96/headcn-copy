@extends('layouts.master')

@section('title', 'Doanh thu tài chính')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    @livewire('ketoan.baocao.doanh-thu-tai-chinh')
@endsection

