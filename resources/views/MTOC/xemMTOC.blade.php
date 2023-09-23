@extends('layouts.master')

@section('title', 'Thông tin MTOC')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }
    </style>
@endsection
@section('content')
    @livewire('mtocs.mtoc-show',['mtoclist'=>$mtoclist])
@endsection