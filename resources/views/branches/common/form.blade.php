<div class="row">
    <div class="form-group col">
        <label for="name" class="col-form-label text-md-right">{{ __('Sucursal') }}</label>

        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $branch->name ?? old('name') ?? null }}" required autocomplete="name" autofocus>

        @error('name')
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
