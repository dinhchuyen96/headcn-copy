@extends('layouts.master')

@section('title', 'Kho quà tặng')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    @livewire('quanlykho.xemkhoquatang',['warehouse_id'=>$id])
@endsection
