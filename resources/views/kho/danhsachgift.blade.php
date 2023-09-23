@extends('layouts.master')

@section('title', 'Danh sách kho quà tặng')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    @livewire('kho.kho-gift-list')
@endsection
