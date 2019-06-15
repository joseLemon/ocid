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
    @if(auth()->user()->can('create-branches'))
        <script src="{{ asset('js/modules/branches/addBranchModal.js') }}"></script>
    @endif
@endsection
