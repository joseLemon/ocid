@extends('layouts.master')
@section('header')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-clockpicker.min.css') }}">
    @include('layouts.fullcalendar.header')
@stop
@section('content')
    @include('doctors.common.form')
@stop
@section('scripts')
    @if(auth()->user()->can('create-branches'))
        <script src="{{ asset('js/modules/branches/addBranchModal.js') }}"></script>
    @endif
    @include('layouts.fullcalendar.footer')
    <script src="{{ asset('js/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('js/modules/doctors/functionality.js?1.0.0') }}"></script>
    <script type="text/javascript">
        $('.clockpicker').clockpicker({
            donetext: "Aceptar",
        });
    </script>
@stop