@extends('layouts.master')

@section('title', 'Danh sách nhà cung cấp')
@section('css')
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    @livewire('supplier.list-supplier')
@endsection
@section('js')
    <script>
        window.livewire.on('close-modal-delete', ()=>{
            document.getElementById('close-modal-delete').click();
        })
    </script>
@endsection
