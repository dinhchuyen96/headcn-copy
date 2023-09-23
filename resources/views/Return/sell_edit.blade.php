@extends('layouts.master')

@section('title', 'Trả lại hàng bán')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    @livewire('sellbuyreturn.sell-edit')
@endsection
