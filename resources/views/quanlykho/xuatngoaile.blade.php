@extends('layouts.master')

@section('title', 'Nhập ngoại lệ')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }
    </style>
@endsection
@section('content')
    @livewire('quanlykho.xuatngoaile')
@endsection
