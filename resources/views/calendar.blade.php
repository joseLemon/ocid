<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('fullcalendar/core/main.css') }}">
    <link rel="stylesheet" href="{{ asset('fullcalendar/daygrid/main.css') }}">
    <link rel="stylesheet" href="{{ asset('fullcalendar/timegrid/main.css') }}">
    <link rel="stylesheet" href="{{ asset('fullcalendar/list/main.css') }}">
    <link rel="stylesheet" href="{{ asset('fullcalendar/bootstrap/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hyper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons.min.css') }}">
</head>
<body>

<button data-toggle="modal" data-target="#event-modal">Agregar evento</button>
<div id="calendar"></div>

<div class="modal fade" tabindex="-1" role="dialog" id="event-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" id="title">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src={{ asset('js/app.js') }}></script>
<script src={{ asset('fullcalendar/core/main.js') }}></script>
<script src={{ asset('fullcalendar/core/locales/es.js') }}></script>
<script src={{ asset('fullcalendar/interaction/main.js') }}></script>
<script src={{ asset('fullcalendar/daygrid/main.js') }}></script>
<script src={{ asset('fullcalendar/timegrid/main.js') }}></script>
<script src={{ asset('fullcalendar/list/main.js') }}></script>
<script src={{ asset('fullcalendar/bootstrap/main.js') }}></script>

<script>

    (() => {
        document.addEventListener('DOMContentLoaded', function() {
            let calendarEl = document.getElementById('calendar'),
                calendar = new FullCalendar.Calendar(calendarEl, {
                    themeSystem: 'bootstrap',
                    plugins: [ 'bootstrap', 'dayGrid', 'timeGrid', 'list', 'interaction' ],
                    height: $(window).height() - 40,
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    navLinks: true, // can click day/week names to navigate views
                    editable: true,
                    eventLimit: true, // allow "more" link when too many events
                    events: [
                        {
                            title: 'All Day Event',
                            start: '2019-05-01',
                        },
                        {
                            title: 'Long Event',
                            start: '2019-05-07',
                            end: '2019-05-10'
                        },
                        {
                            groupId: 999,
                            title: 'Repeating Event',
                            start: '2019-05-09T16:00:00'
                        },
                        {
                            groupId: 999,
                            title: 'Repeating Event',
                            start: '2019-05-16T16:00:00'
                        },
                        {
                            title: 'Conference',
                            start: '2019-05-11',
                            end: '2019-05-13'
                        },
                        {
                            title: 'Meeting',
                            start: '2019-05-12T10:30:00',
                            end: '2019-05-12T12:30:00'
                        },
                        {
                            title: 'Lunch',
                            start: '2019-05-12T12:00:00'
                        },
                        {
                            title: 'Meeting',
                            start: '2019-05-12T14:30:00'
                        },
                        {
                            title: 'Happy Hour',
                            start: '2019-05-12T17:30:00'
                        },
                        {
                            title: 'Dinner',
                            start: '2019-05-12T20:00:00'
                        },
                        {
                            title: 'Birthday Party',
                            start: '2019-05-13T07:00:00'
                        },
                        {
                            title: 'Click for Google',
                            url: 'http://google.com/',
                            start: '2019-05-28'
                        }
                    ],
                    locale: 'es',
                });

            calendar.render();
        });

        $('#addEvent').click(function () {
            calendar.addEvent({
                title: 'dynamic event',
                start: date,
                allDay: true
            });
        })
    })();
</script>
</body>
</html>
