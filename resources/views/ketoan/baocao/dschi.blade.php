@extends('layouts.master')

@section('title', 'Tổng hợp phải trả')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    @livewire('ketoan.baocao.list-outcome')
@endsection
