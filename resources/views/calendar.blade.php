<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker-build.css') }}">
    <link rel="stylesheet" href="{{ asset('fullcalendar/core/main.css') }}">
    <link rel="stylesheet" href="{{ asset('fullcalendar/daygrid/main.css') }}">
    <link rel="stylesheet" href="{{ asset('fullcalendar/timegrid/main.css') }}">
    <link rel="stylesheet" href="{{ asset('fullcalendar/list/main.css') }}">
    <link rel="stylesheet" href="{{ asset('fullcalendar/bootstrap/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hyper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons.min.css') }}">
    <style>
        .datepicker table tr td.today {
            background-color: rgba(114, 124, 245, 0.61) !important;
        }
    </style>
</head>
<body>

<div class="container">
    <button data-toggle="modal" data-target="#event-modal" class="btn btn-primary">Agregar cita</button>
    <div id="calendar"></div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="event-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar cita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <label for="title">Título Cita</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="title">
                        </div>
                    </div>
                    <div class="col">
                        <label for="customer">Cliente</label>
                        <div class="input-group">
                            <select class="form-control" id="customer">
                                <option value="" selected></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="start_date">Fecha Inicio</label>
                        <div class="form-group">
                            <input type="text" class="form-control datetimepicker-input" id="start_date" data-toggle="datetimepicker" data-target="#date"/>
                        </div>
                    </div>
                    <div class="col">
                        <label for="end_date">Fecha Fin</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="end_date" readonly/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script src={{ asset('js/app.js') }}></script>
<script src={{ asset('js/moment.js') }}></script>
<script src={{ asset('js/bootstrap-datetimepicker.js') }}></script>
<script src={{ asset('fullcalendar/core/main.js') }}></script>
<script src={{ asset('fullcalendar/core/locales/es.js') }}></script>
<script src={{ asset('fullcalendar/interaction/main.js') }}></script>
<script src={{ asset('fullcalendar/daygrid/main.js') }}></script>
<script src={{ asset('fullcalendar/timegrid/main.js') }}></script>
<script src={{ asset('fullcalendar/list/main.js') }}></script>
<script src={{ asset('fullcalendar/bootstrap/main.js') }}></script>
<script>
    var events = [
        {
            title: 'Cita 1',
            start: '{{ \Carbon\Carbon::parse()->format('Y-m-d').' 09:00' }}',
            end: '{{ \Carbon\Carbon::parse()->format('Y-m-d').' 09:15' }}',
        }
    ];
    (() => {
        let calendarEl = document.getElementById('calendar'),
            calendar = new FullCalendar.Calendar(calendarEl, {
                themeSystem: 'bootstrap',
                plugins: [ 'bootstrap', 'dayGrid', 'timeGrid', 'list', 'interaction' ],
                height: $(window).height() - 50,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                eventLimit: true, // allow "more" link when too many events
                events: events,
                locale: 'es',
            });

        calendar.render();

        $('#addEvent').click(function () {
            let title = $('#title'),
                customer = $('#customer'),
                date = $('#date'),
                event = {
                    title: title.val(),
                    start: moment(date.val()).format('Y-M-D H:mm:s'),
                    allDay: false,
                    customer: customer.val(),
                };

            events.push({event});

            calendar.addEvent(event);
        });

        $('#start_date').datetimepicker({
            format: 'DD/MM/YYYY HH:mm',
            showClear: true,
            stepping: 15,
            defaultDate: moment(8, "HH"),
            icons: {
                time: 'far fa-clock',
                clear: 'fas fa-trash-alt',
            },
            //disabledTimeIntervals: [[moment({ h: 0 }), moment({ h: 8 })], [moment({ h: 14 }), moment({ h: 16 })], [moment({ h: 18 }), moment({ h: 24 })]],
            enabledHours: [9, 10, 11, 12, 13, 14, 15, 16],
        });
    })();
</script>
</body>
</html>
