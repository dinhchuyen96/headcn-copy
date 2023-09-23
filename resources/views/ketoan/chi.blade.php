@extends('layouts.master')

@section('title', 'Phiếu chi')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }
        #supplyCodeDiv .select2-selection--single {
            background-color: #FFC0CB !important;
        }

    </style>
@endsection
@section('content')
    @livewire('ketoan.chi-list')
@endsection
