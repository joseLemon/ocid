<div class="row">
    <div class="form-group col-sm-6">
        <label for="first_name" class="col-form-label text-md-right">{{ __('Nombre') }}</label>

        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ $customer->first_name ?? old('first_name') ?? null }}" required autofocus>

        @error('first_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group col-sm-6">
        <label for="last_name" class="col-form-label text-md-right">{{ __('Apellido') }}</label>

        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ $customer->last_name ?? old('last_name') ?? null }}" required>

        @error('last_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="form-group mb-0 mt-4">
    <button type="submit" class="btn btn-primary btn-block">
        {{ __('Guardar') }}
    </button>
</div>
