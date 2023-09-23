@extends('layouts.master')
@section('title', 'Chăm sóc khách hàng')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
    </style>
@endsection
@section('content')

@livewire('service.list-contact-customer')

@endsection
