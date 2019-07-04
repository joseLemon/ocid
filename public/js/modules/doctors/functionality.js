let date = new Date(),
    year = date.getFullYear(),
    calendars = {},
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
    };

// Primero del mes
date.setDate(1);

let events = [
    {
        start: `${year}-01-01`,
        title: "Año Nuevo",
    },
    {
        start: `${year}-02-04`,
        title: "Día de la Constitución",
    },
    {
        start: `${year}-03-18`,
        title: "Natalicio de Benito Juárez",
    },
    {
        start: `${year}-04-18`,
        end: '2019-04-19',
        title: "Semana Santa",
    },
    {
        start: `${year}-05-01`,
        title: "Día del Trabajo",
    },
    {
        start: `${year}-09-16`,
        title: "Día de la Independencia",
    },
    {
        start: `${year}-11-02`,
        title: "Día de los Muertos",
    },
    {
        start: `${year}-11-20`,
        title: "Día de la Revolución Mexicana",
    },
    {
        start: `${year}-12-25`,
        title: "Navidad",
    },
];

for (let i = 0; i < 12; i++) {
    date.setMonth(i);
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
        eventClick: function(obj) {
            removeEventById(calendar, obj.event.id);
        },
        showNonCurrentDates: false,
        fixedWeekCount: false,
        unselectAuto: false,
        header: {
            left: '',
            center: 'title',
            right: '',
        },
        titleFormat: {
            month: 'long',
        },
        locale: 'es',
        defaultDate: date,
        events: events,
        eventOverlap: false,
    });

    calendars[i] = calendar;

    calendar.render();

    $('#calendar-tab').on('show.bs.tab', function () {
        calendar.render();
    });
}

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
