@extends('layouts.master')
@section('header')
    @include('layouts.fullcalendar.header')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker-build.css') }}">
@stop
@section('content')
    <div class="card">
        <div class="card-body">
            <button data-toggle="modal" data-target="#event-modal" class="btn btn-primary">Agregar evento</button>
            <div id="calendar"></div>

            <div class="modal fade" tabindex="-1" role="dialog" id="event-modal">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Agregar evento</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="doctor">Doctor</label>
                                <select class="form-control" id="doctor"></select>
                            </div>
                            <div class="form-group">
                                <label for="service">Servicio</label>
                                <div class="input-group">
                                    <select class="form-control" id="service"></select>
                                    <div class="input-group-append">
                                        <button class="btn btn-success" type="button">+</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="customer">Cliente</label>
                                <div class="input-group">
                                    <select class="form-control" id="customer"></select>
                                    <div class="input-group-append">
                                        <button class="btn btn-success" type="button">+</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="date">Fecha</label>
                                    <input type="text" class="form-control" id="date"/>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg col-md-12">
                                        <label for="start_date">Fecha Inicio</label>
                                        <input type="text" class="form-control" id="start_date"/>
                                    </div>
                                    <div class="form-group col-lg col-md-12">
                                        <label for="end_date">Fecha Fin</label>
                                        <input type="text" class="form-control" id="end_date"/>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="addEvent">Guardar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    @include('layouts.fullcalendar.footer')
    <script src={{ asset('js/bootstrap-datetimepicker.js') }}></script>

    <script>
        (() => {
            let calendar = null;
            document.addEventListener('DOMContentLoaded', function() {
                let calendarEl = document.getElementById('calendar');
                calendar = new FullCalendar.Calendar(calendarEl, {
                    themeSystem: 'bootstrap4',
                    plugins: [ 'bootstrap', 'dayGrid', 'timeGrid', 'list', 'interaction', 'resourceTimeGrid' ],
                    height: $(window).height() - 250,
                    //aspectRatio: 2.4,
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,resourceTimeGridDay,listWeek'
                    },
                    resources: [
                        { id: 'a', title: 'Room A' },
                        { id: 'b', title: 'Room B'},
                        { id: 'c', title: 'Room C' },
                        { id: 'd', title: 'Room D' }
                    ],
                    navLinks: true, // can click day/week names to navigate views
                    editable: true,
                    eventLimit: true, // allow "more" link when too many events
                    allDaySlot: false,
                    locale: 'en',
                    slotLabelFormat: {
                        hour: 'numeric',
                        minute: '2-digit',
                        omitZeroMinute: true,
                        meridiem: 'short'
                    },
                });

                calendar.render();
            });

            $('#addEvent').click(function () {
                let date = $('#date'),
                    start = $('#start_date'),
                    end = $('#end_date');
                calendar.addEvent({
                    title: 'dynamic event',
                    start: moment(`${date.val()} ${start.val()}`).format('Y-MM-DD HH:mm:ss'),
                    end: moment(`${date.val()} ${end.val()}`).format('Y-MM-DD HH:mm:ss'),
                    resourceId: 'a',
                });
            });

            $('#date').datetimepicker({
                format: 'L',
            });

            $('#start_date, #end_date').datetimepicker({
                format: 'LT',
                showClear: true,
                stepping: 10,
                defaultDate: moment(8, "HH"),
                icons: {
                    time: 'far fa-clock',
                    clear: 'fas fa-trash-alt',
                },
            });
        })();
    </script>
@stop
