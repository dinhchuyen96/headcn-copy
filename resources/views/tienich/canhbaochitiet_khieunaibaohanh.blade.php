@extends('layouts.master')

@section('title', 'Chi tiết cảnh báo')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }
    </style>
@endsection
@section('content')
    @livewire('utilities.warranty-claim')
@endsection
