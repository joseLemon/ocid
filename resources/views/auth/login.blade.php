@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header pt-4 pb-4 text-center bg-primary"></div>

        <div class="card-body p-5">

            <div class="text-center w-75 m-auto">
                <h3 class="font-weight-bold">{{ __('Iniciar Sesión') }}</h3>
                <p class="text-muted mb-4"></p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group d-inline-block">
                    <!--  <label for="email" class="col-form-label text-md-right">{{ __('E-Mail Address') }}</label>  -->
                        <label for="email" class="col-form-label text-md-right icon"><i class="fas fa-user"></i></label>
                    <input id="email" type="email" placeholder="Correo electrónico" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group d-inline-block">
                    <!--  <label for="password" class="col-form-label text-md-right">{{ __('Contraseña') }}</label>  -->
                        <label for="password" class="col-form-label text-md-right icon"><i class="fas fa-lock"></i></label>
                    <input id="password" type="password" placeholder="Contraseña" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group mt-4 d-inline-block">
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Login') }}
                    </button>
                </div>

                <div class="form-group row text-center mb-0">
                    <div class="col-md-6">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
