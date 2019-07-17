<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="data-tab" data-toggle="tab" href="#form" role="tab" aria-controls="form" aria-selected="true">Informacion Personal</a>
    </li>
    @if(!auth()->user()->hasRole('doctor'))
    <li class="nav-item">
        <a class="nav-link" id="calendar-tab" data-toggle="tab" href="#calendars" role="tab" aria-controls="calendars" aria-selected="false">Dias Libres</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="schedule-tab" data-toggle="tab" href="#schedule" role="tab" aria-controls="schedule" aria-selected="false">Horario</a>
    </li>
    @endif
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="form" role="tabpanel" aria-labelledby="home-tab">
        <div class="row mt-3">

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

                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email ?? old('email') ?? null }}">

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group col-lg-6 col-md-6">
                <label for="branch" class="col-form-label text-md-right">{{ __('Sucursal') }}</label>

                <div class="input-group">
                    <select name="branch" id="branch" class="form-control @error('branch') is-invalid @enderror" name="branch" autocomplete="branch">
                        <option value="" selected></option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" @isset($user->branch) @if($user->branch == $branch->id){{ 'selected' }}@endif @endisset>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @if(auth()->user()->can('create-branches'))
                        <div class="input-group-append">
                            <button class="btn btn-success" type="button" id="addBranch">+</button>
                        </div>
                    @endif
                </div>

                @error('branch')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="w-100"></div>

            <div class="form-group col-lg-6 col-md-6">
                <label for="password" class="col-form-label text-md-right">{{ __('Password') }}</label>

                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group col-lg-6 col-md-6">
                <label for="password-confirm" class="col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
            </div>

        </div>
    </div>

    @if(!auth()->user()->hasRole('doctor'))
    <div class="tab-pane fade" id="calendars" role="tabpanel" aria-labelledby="profile-tab">

        <div class="mt-2 text-right">
            <label>Recargar todos los días inhábiles</label>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-primary" id="reloadAll">↻</button>
        </div>

        <div class="text-center" id="calendar-spinner"><span class="spinner-border" role="status"></span></div>

        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div id="calendar0"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div id="calendar1"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div id="calendar2"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div id="calendar3"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div id="calendar4"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div id="calendar5"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div id="calendar6"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div id="calendar7"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div id="calendar8"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div id="calendar9"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div id="calendar10"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div id="calendar11"></div>
            </div>
        </div>

    </div>

    <div class="tab-pane fade" id="schedule" role="tabpanel" aria-labelledby="profile-tab">

        <div class="row no-margin">

            <div class="col-12 schedule-row">
                <h3>
                    Lunes
                </h3>
                <div class="row no-margin">

                    <div class="col-4">
                        <div class="input-group clockpicker">
                            <input type="text" name="mon-time-start[]" id="mon-time-start" class="form-control" value="{{ $arrangedSchedules[1][0]['start_time'] ?? '9:00' }}">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <p>
                            a:
                        </p>
                    </div>
                    <div class="col-4">
                        <div class="input-group clockpicker">
                            <input type="text" name="mon-time-end[]" id="mon-time-end" class="form-control" value="{{ $arrangedSchedules[1]['end_time'] ?? '14:00' }}">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <button class="btn @isset($arrangedSchedules[1][1]['start_time']){{ 'btn-danger' }}@else{{ 'btn-success' }}@endisset btn-add-schedule" type="button">@isset($arrangedSchedules[1][1]['start_time']){{ '-' }}@else{{ '+' }}@endif</button>
                    </div>

                    <div class="col-12 mt-3 extra @if(!isset($arrangedSchedules[1][1]['start_time'])){{ 'd-none' }}@endif">
                        <div class="row">
                            <div class="col-4">
                                <div class="input-group clockpicker">
                                    <input type="text" name="mon-time-start[]" id="mon-time-start-2" class="form-control" value="{{ $arrangedSchedules[1][1]['start_time'] ?? null }}" @if(!isset($arrangedSchedules[1][1]['start_time'])){{ 'disabled' }}@endif>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 text-center">
                                <p>
                                    a:
                                </p>
                            </div>
                            <div class="col-4">
                                <div class="input-group clockpicker">
                                    <input type="text" name="mon-time-end[]" id="mon-time-end-2" class="form-control" value="{{ $arrangedSchedules[1][1]['end_time'] ?? null }}" @if(!isset($arrangedSchedules[1][1]['start_time'])){{ 'disabled' }}@endif>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="far fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="w-100 spacing"></div>

            <div class="col-12 schedule-row">
                <h3>
                    Martes
                </h3>
                <div class="row no-margin">

                    <div class="col-4">
                        <div class="input-group clockpicker">
                            <input type="text" name="tue-time-start[]" id="tue-time-start" class="form-control" value="{{ $arrangedSchedules[2]['start_time'] ?? '9:00' }}">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <p>
                            a:
                        </p>
                    </div>
                    <div class="col-4">
                        <div class="input-group clockpicker">
                            <input type="text" name="tue-time-end[]" id="tue-time-end" class="form-control" value="{{ $arrangedSchedules[2]['end_time'] ?? '14:00' }}">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <button class="btn @isset($arrangedSchedules[2][1]['start_time']){{ 'btn-danger' }}@else{{ 'btn-success' }}@endisset btn-add-schedule" type="button">@isset($arrangedSchedules[2][1]['start_time']){{ '-' }}@else{{ '+' }}@endif</button>
                    </div>

                    <div class="col-12 mt-3 extra @if(!isset($arrangedSchedules[2][1]['start_time'])){{ 'd-none' }}@endif">
                        <div class="row">
                            <div class="col-4">
                                <div class="input-group clockpicker">
                                    <input type="text" name="tue-time-start[]" id="tue-time-start-2" class="form-control" value="{{ $arrangedSchedules[2][1]['start_time'] ?? null }}" @if(!isset($arrangedSchedules[2][1]['start_time'])){{ 'disabled' }}@endif>
                                    <div class="input-group-append">
                                       <span class="input-group-text">
                                           <i class="far fa-clock"></i>
                                       </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 text-center">
                                <p>
                                    a:
                                </p>
                            </div>
                            <div class="col-4">
                                <div class="input-group clockpicker">
                                    <input type="text" name="tue-time-end[]" id="tue-time-end-2" class="form-control" value="{{ $arrangedSchedules[2][1]['end_time'] ?? null }}" @if(!isset($arrangedSchedules[2][1]['start_time'])){{ 'disabled' }}@endif>
                                    <div class="input-group-append">
                                       <span class="input-group-text">
                                           <i class="far fa-clock"></i>
                                       </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="w-100 spacing"></div>

            <div class="col-12 schedule-row">
                <h3>
                    Miercoles
                </h3>
                <div class="row no-margin">

                    <div class="col-4">
                        <div class="input-group clockpicker">
                            <input type="text" name="wed-time-start[]" id="wed-time-start" class="form-control" value="{{ $arrangedSchedules[3]['start_time'] ?? '9:00' }}">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <p>
                            a:
                        </p>
                    </div>
                    <div class="col-4">
                        <div class="input-group clockpicker">
                            <input type="text" name="wed-time-end[]" id="wed-time-end" class="form-control" value="{{ $arrangedSchedules[3]['end_time'] ?? '14:00' }}">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <button class="btn @isset($arrangedSchedules[3][1]['start_time']){{ 'btn-danger' }}@else{{ 'btn-success' }}@endisset btn-add-schedule" type="button">@isset($arrangedSchedules[3][1]['start_time']){{ '-' }}@else{{ '+' }}@endif</button>
                    </div>

                    <div class="col-12 mt-3 extra @if(!isset($arrangedSchedules[3][1]['start_time'])){{ 'd-none' }}@endif">
                        <div class="row">
                            <div class="col-4">
                                <div class="input-group clockpicker">
                                    <input type="text" name="wed-time-start[]" id="wed-time-start-2" class="form-control" value="{{ $arrangedSchedules[3][1]['start_time'] ?? null }}" @if(!isset($arrangedSchedules[3][1]['start_time'])){{ 'disabled' }}@endif>
                                    <div class="input-group-append">
                                       <span class="input-group-text">
                                           <i class="far fa-clock"></i>
                                       </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 text-center">
                                <p>
                                    a:
                                </p>
                            </div>
                            <div class="col-4">
                                <div class="input-group clockpicker">
                                    <input type="text" name="wed-time-end[]" id="wed-time-end-2" class="form-control" value="{{ $arrangedSchedules[3][1]['end_time'] ?? null }}" @if(!isset($arrangedSchedules[3][1]['start_time'])){{ 'disabled' }}@endif>
                                    <div class="input-group-append">
                                       <span class="input-group-text">
                                           <i class="far fa-clock"></i>
                                       </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="w-100 spacing"></div>

            <div class="col-12 schedule-row">
                <h3>
                    Jueves
                </h3>
                <div class="row no-margin">

                    <div class="col-4">
                        <div class="input-group clockpicker">
                            <input type="text" name="thu-time-start[]" id="thu-time-start" class="form-control" value="{{ $arrangedSchedules[4]['start_time'] ?? '9:00' }}">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <p>
                            a:
                        </p>
                    </div>
                    <div class="col-4">
                        <div class="input-group clockpicker">
                            <input type="text" name="thu-time-end[]" id="thu-time-end" class="form-control" value="{{ $arrangedSchedules[4]['end_time'] ?? '14:00' }}">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <button class="btn @isset($arrangedSchedules[4][1]['start_time']){{ 'btn-danger' }}@else{{ 'btn-success' }}@endisset btn-add-schedule" type="button">@isset($arrangedSchedules[4][1]['start_time']){{ '-' }}@else{{ '+' }}@endif</button>
                    </div>

                    <div class="col-12 mt-3 extra @if(!isset($arrangedSchedules[4][1]['start_time'])){{ 'd-none' }}@endif">
                        <div class="row">
                            <div class="col-4">
                                <div class="input-group clockpicker">
                                    <input type="text" name="thu-time-start[]" id="thu-time-start-2" class="form-control" value="{{ $arrangedSchedules[4][1]['start_time'] ?? null }}" @if(!isset($arrangedSchedules[4][1]['start_time'])){{ 'disabled' }}@endif>
                                    <div class="input-group-append">
                                       <span class="input-group-text">
                                           <i class="far fa-clock"></i>
                                       </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 text-center">
                                <p>
                                    a:
                                </p>
                            </div>
                            <div class="col-4">
                                <div class="input-group clockpicker">
                                    <input type="text" name="thu-time-end[]" id="thu-time-end-2" class="form-control" value="{{ $arrangedSchedules[4][1]['end_time'] ?? null }}" @if(!isset($arrangedSchedules[4][1]['start_time'])){{ 'disabled' }}@endif>
                                    <div class="input-group-append">
                                       <span class="input-group-text">
                                           <i class="far fa-clock"></i>
                                       </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="w-100 spacing"></div>

            <div class="col-12 schedule-row">
                <h3>
                    Viernes
                </h3>
                <div class="row no-margin">

                    <div class="col-4">
                        <div class="input-group clockpicker">
                            <input type="text" name="fri-time-start[]" id="fri-time-start" class="form-control" value="{{ $arrangedSchedules[5]['start_time'] ?? '9:00' }}">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <p>
                            a:
                        </p>
                    </div>
                    <div class="col-4">
                        <div class="input-group clockpicker">
                            <input type="text" name="fri-time-end[]" id="fri-time-end" class="form-control" value="{{ $arrangedSchedules[5]['end_time'] ?? '14:00' }}">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <button class="btn @isset($arrangedSchedules[5][1]['start_time']){{ 'btn-danger' }}@else{{ 'btn-success' }}@endisset btn-add-schedule" type="button">@isset($arrangedSchedules[5][1]['start_time']){{ '-' }}@else{{ '+' }}@endif</button>
                    </div>

                    <div class="col-12 mt-3 extra @if(!isset($arrangedSchedules[5][1]['start_time'])){{ 'd-none' }}@endif">
                        <div class="row">
                            <div class="col-4">
                                <div class="input-group clockpicker">
                                    <input type="text" name="fri-time-start[]" id="fri-time-start-2" class="form-control" value="{{ $arrangedSchedules[5][1]['start_time'] ?? null }}" @if(!isset($arrangedSchedules[5][1]['start_time'])){{ 'disabled' }}@endif>
                                    <div class="input-group-append">
                                       <span class="input-group-text">
                                           <i class="far fa-clock"></i>
                                       </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 text-center">
                                <p>
                                    a:
                                </p>
                            </div>
                            <div class="col-4">
                                <div class="input-group clockpicker">
                                    <input type="text" name="fri-time-end[]" id="fri-time-end-2" class="form-control" value="{{ $arrangedSchedules[5][1]['end_time'] ?? null }}" @if(!isset($arrangedSchedules[5][1]['start_time'])){{ 'disabled' }}@endif>
                                    <div class="input-group-append">
                                       <span class="input-group-text">
                                           <i class="far fa-clock"></i>
                                       </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="w-100 spacing"></div>

            <div class="col-12 schedule-row">
                <h3>
                    Sabado
                </h3>
                <div class="row no-margin">

                    <div class="col-4">
                        <div class="input-group clockpicker">
                            <input type="text" name="sat-time-start[]" id="sat-time-start" class="form-control" value="{{ $arrangedSchedules[6]['start_time'] ?? '9:00' }}">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <p>
                            a:
                        </p>
                    </div>
                    <div class="col-4">
                        <div class="input-group clockpicker">
                            <input type="text" name="sat-time-end[]" id="sat-time-end" class="form-control" value="{{ $arrangedSchedules[6]['end_time'] ?? '14:00' }}">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-2">
                        <button class="btn @isset($arrangedSchedules[6][1]['start_time']){{ 'btn-danger' }}@else{{ 'btn-success' }}@endisset btn-add-schedule" type="button">@isset($arrangedSchedules[6][1]['start_time']){{ '-' }}@else{{ '+' }}@endif</button>
                    </div>

                    <div class="col-12 mt-3 extra @if(!isset($arrangedSchedules[6][1]['start_time'])){{ 'd-none' }}@endif">
                        <div class="row">
                            <div class="col-4">
                                <div class="input-group clockpicker">
                                    <input type="text" name="sat-time-start[]" id="sat-time-start-2" class="form-control" value="{{ $arrangedSchedules[6][1]['start_time'] ?? null }}" @if(!isset($arrangedSchedules[6][1]['start_time'])){{ 'disabled' }}@endif>
                                    <div class="input-group-append">
                                       <span class="input-group-text">
                                           <i class="far fa-clock"></i>
                                       </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 text-center">
                                <p>
                                    a:
                                </p>
                            </div>
                            <div class="col-4">
                                <div class="input-group clockpicker">
                                    <input type="text" name="sat-time-end[]" id="sat-time-end-2" class="form-control" value="{{ $arrangedSchedules[6][1]['end_time'] ?? null }}" @if(!isset($arrangedSchedules[6][1]['start_time'])){{ 'disabled' }}@endif>
                                    <div class="input-group-append">
                                       <span class="input-group-text">
                                           <i class="far fa-clock"></i>
                                       </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
    @endif
</div>

<div class="mt-3">
    <button type="submit" class="btn btn-primary btn-block">
        {{ __('Guardar') }}
    </button>
</div>

<div class="modal fade" role="dialog" id="event-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar fecha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="title">Título</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Título">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="update-event">Actualizar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger" id="delete-event">Borrar</button>
            </div>
        </div>
    </div>
</div>
