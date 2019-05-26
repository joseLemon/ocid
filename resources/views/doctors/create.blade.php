@extends('layouts.master')
@section('header')
    @include('layouts.fullcalendar.header')
@stop
@section('content')
    @include('doctors.common.form')
@stop
@section('scripts')
    @include('layouts.fullcalendar.footer')
    <script src="{{ asset('js/modules/doctors/functionality.js?1.0.0') }}"></script>
@stop
