@extends('layouts.master')

@section('title', 'Sửa nội dung công việc')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    @livewire('work-content.work-content-edit',['id' => $id])
@endsection
