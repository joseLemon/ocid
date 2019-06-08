@extends('layouts.master')
@section('header')
@endsection
@section('content')
    <div class="card">

        <div class="card-body">
            <form method="POST" action="{{ route('user.update',[$user->id]) }}">
                @csrf
                @include('users.common.form')
            </form>
        </div>
    </div>

@endsection
@section('scripts')
@endsection
