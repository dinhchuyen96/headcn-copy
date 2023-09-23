@extends('layouts.master')

@section('title', 'Chăm sóc khách hàng cũ')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
    </style>
@endsection
@section('content')

@livewire('service.support-service-old-customer')

@endsection
