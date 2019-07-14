@extends('layouts.master')
@section('header')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-clockpicker.min.css') }}">
    @include('layouts.fullcalendar.header')
@stop
@section('content')
    <div class="card">
        <div class="card-body">
            <form  method="POST" action="{{ auth()->user()->hasRole('doctor') ? route('doctor.updateProfile',[$user->id]) : route('doctor.update',[$user->id]) }}" id="doctorForm">
                @csrf
                @include('doctors.common.form')
            </form>
            <input type="hidden" id="doctor_id" value="{{ $user->id }}">
        </div>
    </div>
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
