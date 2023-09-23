@extends('layouts.master')

@section('title', 'Thay đổi phụ tùng xe')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }
    </style>
@endsection
@section('content')
    @livewire('quanlykho.change-bike-info')
@endsection
