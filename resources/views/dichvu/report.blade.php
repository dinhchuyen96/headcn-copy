@extends('layouts.master')

@section('title', 'Báo cáo bảo dưỡng định kì')
@section('css')
    <link href="{{ asset('assets/css/table-common.css') }}" rel="stylesheet" />
@endsection
@section('content')
   @livewire('service.report')
@endsection
