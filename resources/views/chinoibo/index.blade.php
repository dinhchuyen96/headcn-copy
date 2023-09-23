@extends('layouts.master')

@section('title', 'Danh sách chi nội bộ')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    @livewire('chinoibo.index')
@endsection
