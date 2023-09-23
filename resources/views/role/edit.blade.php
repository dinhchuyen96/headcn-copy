@extends('layouts.master')

@section('title', 'Thông tin vai trò')
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
                <div class="ibox-title">Chỉnh sửa vai trò</div>
            </div>
            <div class="ibox-body">
                {!! Form::model($data, ['method' => 'POST', 'class' => 'form-horizontal',  'autocomplete' => "off",'route' => ['roles.update', $data->id]]) !!}
                    @include('role._form')
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