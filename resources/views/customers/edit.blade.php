@extends('layouts.master')
@section('header')
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('customer.update',[$customer->id]) }}">
                @csrf
                @include('customers.common.form')
            </form>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
