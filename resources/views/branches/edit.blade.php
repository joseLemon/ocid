@extends('layouts.master')
@section('header')
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('branch.update',[$branch->id]) }}">
                @csrf
                @include('branches.common.form')
            </form>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
