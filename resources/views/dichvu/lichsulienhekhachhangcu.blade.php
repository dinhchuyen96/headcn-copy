@extends('layouts.master')

@section('title', 'Liên hệ khách hàng cũ')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
    </style>
@endsection
@section('content')

    @livewire('service.contact-history-old-customer',['customerId'=>$customerId])

@endsection
