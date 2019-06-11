<div class="card">
    <div class="card-body">

        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="data-tab" data-toggle="tab" href="#form" role="tab" aria-controls="form" aria-selected="true">Informacion Personal</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="calendar-tab" data-toggle="tab" href="#calendars" role="tab" aria-controls="calendars" aria-selected="false">Dias Libres</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="schedule-tab" data-toggle="tab" href="#schedule" role="tab" aria-controls="schedule" aria-selected="false">Horario</a>
            </li>
        </ul>

        <form  method="POST" action="">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="form" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row mt-3">

                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $doctor->name  ?? null }}">
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $doctor->email  ?? null }}">
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="confirm_password">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="tab-pane fade" id="calendars" role="tabpanel" aria-labelledby="profile-tab">

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

                        <div class="col-12">
                            <h3>
                                Lunes
                            </h3>
                            <div class="row no-margin">

                                <div class="col-4">
                                    <div class="input-group clockpicker">
                                        <input type="text" name="mon-time-start" id="mon-time-start" class="form-control" value="09:30">
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
                                        <input type="text" name="mon-time-end" id="mon-time-end" class="form-control" value="09:30">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="far fa-clock"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="w-100 spacing"></div>

                        <div class="col-12">
                            <h3>
                                Martes
                            </h3>
                            <div class="row no-margin">

                                <div class="col-4">
                                    <div class="input-group clockpicker">
                                        <input type="text" name="tur-time-start" id="tur-time-start" class="form-control" value="09:30">
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
                                        <input type="text" name="tur-time-end" id="tur-time-end" class="form-control" value="09:30">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="far fa-clock"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="w-100 spacing"></div>

                        <div class="col-12">
                            <h3>
                                Miercoles
                            </h3>
                            <div class="row no-margin">

                                <div class="col-4">
                                    <div class="input-group clockpicker">
                                        <input type="text" name="wen-time-start" id="wen-time-start" class="form-control" value="09:30">
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
                                        <input type="text" name="wen-time-end" id="wen-time-end" class="form-control" value="09:30">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="far fa-clock"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="w-100 spacing"></div>

                        <div class="col-12">
                            <h3>
                                Jueves
                            </h3>
                            <div class="row no-margin">

                                <div class="col-4">
                                    <div class="input-group clockpicker">
                                        <input type="text" name="thu-time-start" id="thu-time-start" class="form-control" value="09:30">
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
                                        <input type="text" name="thu-time-end" id="thu-time-end" class="form-control" value="09:30">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="far fa-clock"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="w-100 spacing"></div>

                        <div class="col-12">
                            <h3>
                                Viernes
                            </h3>
                            <div class="row no-margin">

                                <div class="col-4">
                                    <div class="input-group clockpicker">
                                        <input type="text" name="fri-time-start" id="fri-time-start" class="form-control" value="09:30">
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
                                        <input type="text" name="fri-time-end" id="fri-time-end" class="form-control" value="09:30">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="far fa-clock"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="w-100 spacing"></div>

                        <div class="col-12">
                            <h3>
                                Sabado
                            </h3>
                            <div class="row no-margin">

                                <div class="col-4">
                                    <div class="input-group clockpicker">
                                        <input type="text" name="sat-time-start" id="sat-time-start" class="form-control" value="09:30">
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
                                        <input type="text" name="sat-time-end" id="sat-time-end" class="form-control" value="09:30">
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

            <div class="mt-2">
                <button class="btn btn-primary btn-block">Guardar</button>
            </div>

        </form>


    </div>
</div>
