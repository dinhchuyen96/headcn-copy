@extends('layouts.master')

@section('title', 'Thông tin người dùng')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thêm người dùng</div>
            </div>
            <div class="ibox-body">
                {!! Form::open(array('route' => 'nguoiDung.store','method'=>'POST')) !!}
                    @include('nguoidung._form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('assets/select2/select2.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $(function () {
                $(".select_box").select2({
                    placeholder: "Chọn quyền...",
                    allowClear: true
                });
            });
        });
    </script>
@endsection
