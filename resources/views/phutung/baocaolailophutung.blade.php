@extends('layouts.master')

@section('title', 'DS đơn hàng')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
   @livewire('phutung.bao-cao-lai-lo-phu-tung')
@endsection

