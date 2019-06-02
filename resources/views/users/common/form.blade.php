<div class="row">
    <div class="form-group col-lg-6 col-md-6">
        <label for="name" class="col-form-label text-md-right">{{ __('Name') }}</label>

        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name ?? old('name') ?? null }}" required autocomplete="name" autofocus>

        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group col-lg-6 col-md-6">
        <label for="email" class="col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email ?? old('email') ?? null }}" required autocomplete="email">

        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group col-lg-6 col-md-6">
        <label for="role" class="col-form-label text-md-right">{{ __('Tipo de usuario') }}</label>

        <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" name="role" required autocomplete="role">
            <option value="" selected disabled="disabled"></option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" @isset($user->role) @if($user->role == $role->id){{ 'selected' }}@endif @endisset>{{ $role->name }}</option>
            @endforeach
        </select>

        @error('role')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="w-100"></div>

    <div class="form-group col-lg-6 col-md-6">
        <label for="password" class="col-form-label text-md-right">{{ __('Password') }}</label>

        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group col-lg-6 col-md-6">
        <label for="password-confirm" class="col-form-label text-md-right">{{ __('Confirm Password') }}</label>

        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
    </div>
</div>

<div class="form-group mb-0 mt-4">
    <button type="submit" class="btn btn-primary btn-block">
        {{ __('Guardar') }}
    </button>
</div>
