@extends('layouts.master')

@section('title', 'Tổng hợp danh sách trả góp')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    @livewire('ketoan.baocao.tra-gop-list')
@endsection
