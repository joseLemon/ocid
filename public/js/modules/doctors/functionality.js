let date = new Date(),
    selEvent = null;
// Primero del mes
date.setDate(1);
let year = date.getFullYear(),
    calendars = [],
    events = [],
    all = [],
    title = $('#title'),
    removeEventById = (calendar, id) => {
        let event = calendar.getEventById(id);
        event.remove();
    },
    dateHasEvent = (calendar, date1, date2) => {
        let start = moment(date1),
            end = moment(date2).subtract(1, 'days');
        let allEvents = calendar.getEvents();
        let events = $.grep(allEvents, function (v) {
            let vstart = moment(v.start),
                vend = moment(v.end).subtract(1, 'days');
            return (start >= vstart && start <= vend) || (end >= vstart && end <= vend) || (vstart >= start && vstart <= end);
        });
        if (events.length > 0)
            events.forEach(function (event) {
                removeEventById(calendar, event.id);
            });
    },
    getAllEvents = () => {
        let id = $('#doctor_id');
        if (id.length > 0)
            $.ajax({
                type: 'GET',
                url: '/doctors/getDaysOff',
                data: {
                    id: id.val(),
                },
                success: (res) => {
                    if (res.success) {
                        all = res.data;
                        initCalendars();
                    }
                },
                error: () => {
                    $.alert({
                        title: 'Error',
                        type: 'red',
                        content: 'Ocurrió un error procesando la solicitud',
                    });
                }
            });
        else {
            initCalendars();
        }
    },
    getMonthEvents = (month, refresh = false) => {
        let events = [];
        if ($('#doctor_id').length > 0 && !refresh) {
            let monthData = all[month + 1];
            if (monthData)
                monthData.forEach(function (item, i) {
                    let ev = {
                        id: generateUUID(),
                        start: moment(`${year}-${item.date}`).format('YYYY-MM-DD'),
                        title: item.title ? item.title : 'Día libre',
                        allDay: true,
                    };
                    if (item.end_date)
                        ev.end = moment(`${year}-${item.end_date}`).add(1, 'days').format('YYYY-MM-DD');
                    events.push(ev);
                });
        }
        else
            switch (month) {
                case 0:
                    events.push({
                        start: `${year}-01-01`,
                        title: "Año Nuevo",
                    });
                    break;
                case 1:
                    events.push({
                        start: `${year}-02-04`,
                        title: "Día de la Constitución",
                    });
                    break;
                case 2:
                    events.push({
                        start: `${year}-03-18`,
                        title: "Natalicio de Benito Juárez",
                    });
                    break;
                case 3:
                    events.push({
                        start: `${year}-04-18`,
                        end: '2019-04-19',
                        title: "Semana Santa",
                    });
                    break;
                case 4:
                    events.push({
                        start: `${year}-05-01`,
                        title: "Día del Trabajo",
                    });
                    break;
                case 5:
                    break;
                case 6:
                    break;
                case 7:
                    break;
                case 8:
                    events.push({
                        start: `${year}-09-16`,
                        title: "Día de la Independencia",
                    });
                    break;
                case 9:
                    break;
                case 10:
                    events.push({
                        start: `${year}-11-02`,
                        title: "Día de los Muertos",
                    },{
                        start: `${year}-11-20`,
                        title: "Día de la Revolución Mexicana",
                    });
                    break;
                case 11:
                    events.push({
                        start: `${year}-12-25`,
                        title: "Navidad",
                    });
                    break;
            }
        return events;
    },
    initCalendars = () => {
        for (let i = 0; i < 12; i++) {
            date.setMonth(i);
            events = getMonthEvents(i);
            let month = document.getElementById(`calendar${i}`);
            let calendar = new FullCalendar.Calendar(month, {
                themeSystem: 'bootstrap',
                plugins: ['bootstrap', 'dayGrid', 'interaction'],
                selectable: true,
                select: (obj) => {
                    dateHasEvent(calendar, obj.start, obj.end);

                    calendar.addEventSource([{
                        id: generateUUID(),
                        start: obj.start,
                        end: obj.end,
                        title: 'Día libre',
                        allDay: true,
                    }]);
                    calendar.unselect();
                },
                /*eventClick: function(obj) {
                    removeEventById(calendar, obj.event.id);
                },*/
                eventClick: function (info) {
                    selEvent = info.event;
                    title.val(info.event.title);
                    $('#event-modal').modal('show');
                },
                showNonCurrentDates: false,
                fixedWeekCount: false,
                unselectAuto: false,
                customButtons: {
                    reload: {
                        text: '↻',
                        click: function() {
                            $.confirm({
                                title: 'Servicio',
                                content: 'Se modificaran las fechas de este mes para establecer las fechas por defecto.',
                                buttons: {
                                    confirm: {
                                        text: 'Confirmar',
                                        btnClass: 'btn-blue',
                                        action: function () {
                                            calendar.getEvents().forEach(function (item, i) {
                                                item.remove();
                                            });
                                            getMonthEvents(i, true).forEach(function (item, i) {
                                                calendar.addEvent(item);
                                            });
                                        }
                                    },
                                    cancel: {
                                        text: 'Cancelar',
                                        action: function () {
                                            //close
                                        }
                                    },
                                },
                                columnClass: 'col-md-6 col-sm-12'
                            });
                        }
                    }
                },
                header: {
                    left: 'title',
                    center: '',
                    right: 'reload',
                },
                titleFormat: {
                    month: 'long',
                },
                locale: 'es',
                defaultDate: date,
                events: events,
                eventOverlap: false,
            });

            calendars.push(calendar);

            calendar.render();

            $('#calendar-tab').on('shown.bs.tab', function () {
                calendar.render();
            });

            $('#calendar-spinner').addClass('d-none');
        }
    };
getAllEvents();

$('.btn-add-schedule').click(function () {
    let btn = $(this),
        extra = btn.closest('.row').find('.extra');

    if (extra.hasClass('d-none')) {
        btn.text('-').removeClass('btn-success').addClass('btn-danger');
        extra.removeClass('d-none');
        extra.find('input').prop('disabled', false);
    } else {
        btn.text('+').addClass('btn-success').removeClass('btn-danger');
        extra.addClass('d-none');
        extra.find('input').prop('disabled', true);
    }
});

(() => {
    let valid = false;
    $('#doctorForm').submit(function (e) {
        let form = $(this);
        if (!valid) {
            e.preventDefault();
            let json = [],
                schedules = $('.schedule-row'),
                schVal = true;
            schedules.each(function () {
                let input = $(this).find('input'),
                    start = moment($(input[0]).val(), 'hh:mm a'),
                    end = moment($(input[1]).val(), 'hh:mm a');
                console.log(start, end);

                if (start.isAfter(end) || start.isSame(end)) {
                    schVal = false;
                    $(input[0]).addClass('is-invalid');
                    $(input[1]).addClass('is-invalid');
                } else {
                    $(input[0]).removeClass('is-invalid');
                    $(input[1]).removeClass('is-invalid');
                }

                let extra = $(input[2]);
                if (!extra.prop('disabled')) {
                    start = moment($(input[2]).val(), 'hh:mm a');
                    end = moment($(input[3]).val(), 'hh:mm a');

                    if (start.isAfter(end) || start.isSame(end)) {
                        schVal = false;
                        $(input[2]).addClass('is-invalid');
                        $(input[3]).addClass('is-invalid');
                    } else {
                        $(input[2]).removeClass('is-invalid');
                        $(input[3]).removeClass('is-invalid');
                    }
                }
            });
            if (!schVal) {
                $.alert({
                    title: 'Error',
                    type: 'red',
                    content: 'Asegurate que los horarios sean correctos para continuar',
                });
                return false;
            }
            calendars.forEach(function (item, i) {
                let events = item.getEvents();
                events.forEach(function (ev) {
                    let start = moment(ev.start).format('YYYY-MM-DD'),
                        end = ev.end ? moment(ev.end).subtract(1, 'days').format('YYYY-MM-DD') : null;
                    if (start === end)
                        end = null;
                    let json_item = {
                        title: ev.title,
                        date: start,
                        date_end: end,
                    };
                    json.push(json_item);
                });
            });
            json = encodeURIComponent(JSON.stringify(json));
            form.append(`<input type="hidden" name="days_off" value="${json}"/>`);
            valid = true;
            form.submit();
        }
    });

    $('#reloadAll').click(function () {
        $.confirm({
            title: 'Servicio',
            content: 'Se modificaran las fechas de todos los meses para establecer las fechas por defecto.',
            buttons: {
                confirm: {
                    text: 'Confirmar',
                    btnClass: 'btn-blue',
                    action: function () {
                        for (let i = 0; i < 12; i++) {
                            let calendar = calendars[i];
                            calendar.getEvents().forEach(function (item, i) {
                                item.remove();
                            });
                            getMonthEvents(i, true).forEach(function (item, i) {
                                calendar.addEvent(item);
                            });
                        }
                    }
                },
                cancel: {
                    text: 'Cancelar',
                    action: function () {
                        //close
                    }
                },
            },
            columnClass: 'col-md-6 col-sm-12'
        });
    });

    $('#update-event').click(function () {
        let month = Number(moment(selEvent.start).format('M')) - 1,
            calendar = calendars[month],
            currEv = calendar.getEventById(selEvent.id),
            event = {
                title: title.val(),
                start: selEvent.start,
                end: selEvent.end,
                allDay: true,
            };
        currEv.remove();
        calendar.addEvent(event);
        $('#event-modal').modal('hide');
    });

    $('#delete-event').click(function () {
        let month = Number(moment(selEvent.start).format('M')) - 1,
            calendar = calendars[month],
            currEv = calendar.getEventById(selEvent.id);
        currEv.remove();
        $('#event-modal').modal('hide');
    });

    $('#event-modal').on('hide.bs.modal', function () {
        selEvent = null;
        title.val('');
    });
})();
