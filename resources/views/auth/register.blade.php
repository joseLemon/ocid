@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header pt-4 pb-4 text-center bg-primary"></div>

        <div class="card-body p-5">

            <div class="text-center w-75 m-auto">
                <h3 class="font-weight-bold">{{ __('Register') }}</h3>
                <p class="text-muted mb-4"></p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name" class="col-form-label text-md-right">{{ __('Name') }}</label>

                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role" class="col-form-label text-md-right">{{ __('Tipo de usuario') }}</label>

                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" name="role" required autocomplete="role">
                        <option value="" selected disabled="disabled"></option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>

                    @error('role')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="col-form-label text-md-right">{{ __('Password') }}</label>

                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div>

                <div class="form-group mb-0 mt-4">
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Guardar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
