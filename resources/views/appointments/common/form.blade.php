<div class="row">
    <div class="form-group col-sm-6">
        <label for="doctor" class="col-form-label text-md-right">{{ __('Médico') }}</label>

        <select class="form-control" id="doctor" name="doctor_id" required>
            <option></option>
            @foreach($doctors as $id => $doctor)
                <option value="{{ $doctor->id }}" @if(isset($appointment) && $appointment->doctor_id == $doctor->id){{ 'selected' }}@endif>{{ $doctor->name }}</option>
            @endforeach
        </select>

        @error('doctor')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group col-sm-6">
        <label for="service" class="col-form-label text-md-right">Servicio</label>
        <div class="input-group">
            <select class="form-control select-2-g-append" id="service" name="service_id" required>
                <option></option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}" data-time="{{ $service->time_slot }}" @if(isset($appointment) && $appointment->service_id == $service->id){{ 'selected' }}@endif>{{ $service->name }}</option>
                @endforeach
            </select>
            <div class="input-group-append">
                <button class="btn btn-success" type="button" id="addService">+</button>
            </div>
        </div>
    </div>
    <div class="form-group col-sm-6">
        <label for="customer" class="col-form-label text-md-right">Cliente</label>
        <div class="input-group">
            <select class="form-control select-2-g-append" id="customer" name="customer_id" required>
                @if(isset($appointment->customer_id))
                    <option value="{{ $appointment->customer_id }}" selected>{{ $appointment->customer_name }}</option>
                @endif
            </select>
            <div class="input-group-append">
                <button class="btn btn-success" type="button" id="addCustomer">+</button>
            </div>
        </div>
    </div>
    <div class="form-group col-sm-6">
        <label for="date" class="col-form-label text-md-right">Fecha</label>
        <input id="date" type="text" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ $appointment->date ?? old('date') ?? null }}" required>
    </div>
    <div class="form-group col-sm-6">
        <label for="start_time" class="col-form-label text-md-right">Hora Inicio</label>
        <input id="start_time" type="text" class="form-control @error('start_time') is-invalid @enderror" name="start" value="{{ $appointment->start ?? old('start_time') ?? null }}" required>
    </div>
    <div class="form-group col-sm-6">
        <label for="end_time" class="col-form-label text-md-right">Hora Fin</label>
        <input id="end_time" type="text" class="form-control @error('end_time') is-invalid @enderror" name="end" value="{{ $appointment->end ?? old('end_time') ?? null }}" required>
    </div>
    @if(isset($appointment))
    <div class="form-group col-sm-6" id="status_cont">
        <label for="status" class="col-form-label text-md-right">Estatus</label>
        <select class="form-control" id="status" name="status" required>
            <option value="1">A tiempo</option>
            <option value="2">En curso</option>
            <option value="3">Retrasado</option>
            <option value="4">Cancelado</option>
        </select>
    </div>
    @endif
    <input type="hidden" id="appointment_id" value="{{ $appointment->id ?? null }}">
</div>

<div class="form-group mb-0 mt-4">
    <button type="submit" class="btn btn-primary btn-block">
        {{ __('Guardar') }}
    </button>
</div>
