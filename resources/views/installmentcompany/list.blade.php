@extends('layouts.master')

@section('title', 'Danh sách công ty trả góp')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    @livewire('installment-company.installment-company-list')
@endsection
