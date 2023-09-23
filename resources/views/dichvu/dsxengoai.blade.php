@extends('layouts.master')

@section('title', 'DS xe ngoài')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')

@livewire('service.list-out-motorbike')

@endsection
