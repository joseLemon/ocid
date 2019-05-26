$(document).ready(function () {
    let date = new Date(),
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

    date.setDate(1);

    let events = [
        {
            start: '2019-01-01',
            title: "Año Nuevo",
        },
        {
            start: '2019-02-04',
            title: "Día de la Constitución",
        },
        {
            start: '2019-03-18',
            title: "Natalicio de Benito Juárez",
        },
        {
            start: '2019-04-18',
            end: '2019-04-19',
            title: "Semana Santa",
        },
        {
            start: '2019-05-01',
            title: "Día del Trabajo",
        },
        {
            start: '2019-09-16',
            title: "Día de la Independencia",
        },
        {
            start: '2019-11-02',
            title: "Día de los Muertos",
        },
        {
            start: '2019-11-20',
            title: "Día de la Revolución Mexicana",
        },
        {
            start: '2019-12-25',
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
                let calendar = obj.view.calendar;
                dateHasEvent(calendar, obj.start, obj.end);

                calendar.addEventSource([{
                    id: generateUUID(),
                    start: obj.start,
                    end: obj.end,
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

        calendar.render();

        $('#calendar-tab').on('show.bs.tab', function () {
            calendar.render();
        });
    }
});
