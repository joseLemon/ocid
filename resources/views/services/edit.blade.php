@extends('layouts.master')
@section('header')
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('service.update',[$service->id]) }}">
                @csrf
                @include('services.common.form')
            </form>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
