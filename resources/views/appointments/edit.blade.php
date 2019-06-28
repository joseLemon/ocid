@extends('layouts.master')
@section('header')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker-build.css') }}">
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('appointment.update',[$appointment->id]) }}">
                @csrf
                @include('appointments.common.form')
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src={{ asset('js/bootstrap-datetimepicker.js') }}></script>
    <script>
        var events = [
                    @foreach($appointments as $item)
                {
                    id: Number({{ $item->id }}),
                    title: '{{ $item->customer_name }} - {{ $item->service_name }}',
                    doctor_id: Number({{ $item->doctor_id }}),
                    service_id: Number({{ $item->service_id }}),
                    customer_id: Number({{ $item->customer_id }}),
                    customer_name: '{{ $item->customer_name }}',
                    date: '{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}',
                    start: '{{ $item->date.' '.$item->start }}',
                    end: '{{ $item->date.' '.$item->end }}',
                    resourceId: '{{ $item->doctor_id }}',
                    status: Number({{ $item->status }}),
                },
                @endforeach
            ],
            doctors = [
                    @foreach($doctors as $id => $doctor)
                {
                    id: "{{ $doctor->id }}",
                    title: "{{ $doctor->name }}",
                    daysOff: {!! json_encode($doctor->daysOff) !!} ,
                },
                @endforeach
            ];
    </script>
    <script src="{{ asset('js/modules/appointments/functionality.js?1.0.0') }}"></script>
@endsection
