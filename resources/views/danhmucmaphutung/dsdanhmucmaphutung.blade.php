@extends('layouts.master')

@section('title', 'Danh sách danh mục mã phụ tùng')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    @livewire('danh-muc-ma-phu-tung.list-danh-muc')
@endsection
@section('js')
    <script>
        window.livewire.on('close-modal-delete', ()=>{
            document.getElementById('close-modal-delete').click();
        })
    </script>
@endsection
