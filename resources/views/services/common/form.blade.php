<div class="row">
    <div class="form-group col">
        <label for="name" class="col-form-label text-md-right">{{ __('Servicio') }}</label>

        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $service->name ?? old('name') ?? null }}" required autocomplete="name" autofocus>

        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group col">
        <label for="time_slot" class="col-form-label text-md-right">{{ __('Intervalo de Tiempo') }}</label>

        <input id="time_slot" type="number" class="form-control @error('time_slot') is-invalid @enderror" name="time_slot" value="{{ $service->time_slot ?? old('time_slot') ?? null }}" required autocomplete="time_slot" min="0" step="5">

        @error('time_slot')
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
