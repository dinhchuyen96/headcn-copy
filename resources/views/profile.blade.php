@extends('layouts.master')

@section('title', 'Thông tin cá nhân')

@section('content')
    <div class="row justify-content-center">
        <h2>Thông tin cá nhân</h2>
    </div>


    <div class="row justify-content-center pt-3">
        <div class="form-group">
            <strong>Tên đăng nhập:</strong>
            {{ $user->name }}
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="form-group">
            <strong>Email:</strong>
            {{ $user->email }}
        </div>
    </div>
    <div class="row justify-content-center">
        <a class="btn btn-secondary" href="{{ route('dashboard') }}">Quay lại</a>
        <a type="button" href="{{ route('edit') }}" class="btn btn-primary ml-3">Chỉnh sửa</a>
    </div>
@endsection
