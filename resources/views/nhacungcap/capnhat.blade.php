@extends('layouts.master')

@section('title', 'Thông tin nhà cung cấp')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    @livewire('supplier.edit-supplier',['supply_id'=>$id])

@endsection
