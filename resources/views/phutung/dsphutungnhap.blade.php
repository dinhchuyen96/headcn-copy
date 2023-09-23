@extends('layouts.master')

@section('title', 'DS phụ tùng nhập')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
   @livewire('phutung.dsphutungnhap')
@endsection
