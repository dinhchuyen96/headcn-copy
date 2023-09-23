@extends('layouts.master')

@section('title', 'Bảo dưỡng định kì')
@section('css')
    <link href="{{ asset('assets/css/table-common.css') }}" rel="stylesheet" />
@endsection
@section('content')
   @livewire('service.mantain-list')
@endsection
