@extends('layouts.master')

@section('title', 'Sửa thông tin công ty trả góp')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }
        @media screen and (max-width: 1440px) {
            label.col-1 {
                padding:5px 0px 5px 10px;
            }
        }

    </style>
@endsection
@section('content')
    @livewire('installment-company.installment-company-edit',['id' => $id])
@endsection
