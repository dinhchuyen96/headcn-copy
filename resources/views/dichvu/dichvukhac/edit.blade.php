@extends('layouts.master')

@section('title', 'Dịch vụ khác')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')

    @livewire('service.other.edit', ['orderId' => $id])

@endsection
