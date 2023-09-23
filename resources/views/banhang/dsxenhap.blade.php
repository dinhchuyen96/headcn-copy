@extends('layouts.master')

@section('title', 'DS xe nhập')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    @livewire('motorbike.list-motorbike')
@endsection
