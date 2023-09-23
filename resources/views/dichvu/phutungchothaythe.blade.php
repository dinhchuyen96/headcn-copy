@extends('layouts.master')

@section('title', 'DS phụ tùng chờ thay thế')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')

    @livewire('service.atrophy-accessory')

@endsection
