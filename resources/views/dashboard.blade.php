@extends('layouts.master')

@section('title', 'Dashboard')
<style>
    @media screen and (max-width: 1440px) {
        h5{
            font-size: 1.10 rem;
        }
    }
</style>

@section('content')
    @livewire('dashboard')
@endsection

@section('js')
    <script src="{{asset('assets/js/Chart.min.js')}}" type="text/javascript"></script>
@endsection
