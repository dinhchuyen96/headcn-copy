@extends('layouts.master')

@section('title', 'Bán buôn phụ tùng')
@section('css')
    <link href="{{ asset('assets/css/table-common.css') }}" rel="stylesheet" />
    <style>
        .custom-select {
            width: 100%;
        }

    </style>
@endsection
@section('content')
    @livewire('phutung.banbuon')


@endsection
@section('js')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script>
        window.livewire.on('close-modal-import', ()=>{
            document.getElementById('modal-form-import').click();
        })
    </script>

@endsection