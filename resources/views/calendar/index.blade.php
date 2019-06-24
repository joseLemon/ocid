@extends('layouts.master')
@section('header')
    @include('layouts.fullcalendar.header')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker-build.css') }}">
@stop
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <button data-toggle="modal" data-target="#event-modal" class="btn btn-primary mt-3">Agregar evento</button>
                </div>
                @if(auth()->user()->hasRole('admin'))
                    <div class="col">
                        <div class="form-group">
                            <label for="branch_f">Sucursales</label>
                            <select name="branch_f" id="branch_f" class="form-control">
                                <option></option>
                                @foreach($branches as $id => $branch)
                                    <option value="{{ $id }}">{{ $branch }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div class="col">
                    <div class="form-group">
                        <label for="doctor_f">Médicos</label>
                        <select name="doctor_f" id="doctor_f" class="form-control">
                            <option></option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="calendar"></div>

            <div class="modal fade" role="dialog" id="event-modal">
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
                                <label for="doctor">Médico</label>
                                <select class="form-control" id="doctor">
                                    <option></option>
                                    @foreach($doctors as $id => $doctor)
                                        <option value="{{ $id }}">{{ $doctor }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="service">Servicio</label>
                                <div class="input-group">
                                    <select class="form-control select-2-g-append" id="service">
                                        <option></option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" data-time="{{ $service->time_slot }}">{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-success" type="button" id="addService">+</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="customer">Cliente</label>
                                <div class="input-group">
                                    <select class="form-control select-2-g-append" id="customer">
                                        <option value="1">Example</option>
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-success" type="button" id="addCustomer">+</button>
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
                                        <label for="start_time">Hora Inicio</label>
                                        <input type="text" class="form-control" id="start_time"/>
                                    </div>
                                    <div class="form-group col-lg col-md-12">
                                        <label for="end_time">Hora Fin</label>
                                        <input type="text" class="form-control" id="end_time"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group d-none" id="status_cont">
                                <label for="status">Estatus</label>
                                <select class="form-control" id="status">
                                    <option value="1">A tiempo</option>
                                    <option value="2">En curso</option>
                                    <option value="3">Retrasado</option>
                                    <option value="4">Cancelado</option>
                                </select>
                            </div>
                            <input type="hidden" id="appointment_id">
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
        let events = [
            @foreach($appointments as $item)
            {
                id: Number({{ $item->id }}),
                title: '{{ $item->customer_name }} - {{ $item->service_name }}',
                doctor_id: Number({{ $item->doctor_id }}),
                service_id: Number({{ $item->service_id }}),
                customer_id: Number({{ $item->customer_id }}),
                date: '{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}',
                start: '{{ $item->date.' '.$item->start }}',
                end: '{{ $item->date.' '.$item->end }}',
                resourceId: '{{ $item->doctor_id }}',
                status: Number({{ $item->status }}),
            },
            @endforeach
        ];
        (() => {
            let doctor = $('#doctor'),
                service = $('#service'),
                customer = $('#customer'),
                date = $('#date'),
                start = $('#start_time'),
                end = $('#end_time'),
                status = $('#status'),
                appId = $('#appointment_id'),
                selServ = null;
            let calendar = null,
                doctors = [
                    @foreach($doctors as $id => $doctor)
                    { id: "{{ $id }}", title: "{{ $doctor }}" },
                    @endforeach
                ],
                height = $(window).height() - 250;
            document.addEventListener('DOMContentLoaded', function() {
                let calendarEl = document.getElementById('calendar');
                calendar = new FullCalendar.Calendar(calendarEl, {
                    themeSystem: 'bootstrap4',
                    plugins: [ 'bootstrap', 'dayGrid', 'timeGrid', 'list', 'interaction', 'resourceTimeGrid' ],
                    height: height > 300 ? height : 300,
                    //aspectRatio: 2.4,
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,resourceTimeGridDay,listWeek'
                    },
                    resources: doctors,
                    navLinks: true, // can click day/week names to navigate views
                    editable: true,
                    eventLimit: true, // allow "more" link when too many events
                    allDaySlot: false,
                    locale: 'es',
                    slotLabelFormat: {
                        hour: 'numeric',
                        minute: '2-digit',
                        omitZeroMinute: false,
                        meridiem: 'narrow'
                    },
                    defaultView: 'resourceTimeGridDay',
                    eventClick: function (info) {
                        $('#status_cont').removeClass('d-none');
                        let event = events.find(function (obj) {
                            return Number(obj.id) === Number(info.event.id);
                        });
                        appId.val(info.event.id);
                        date.val(event.date);
                        start.val(moment(event.start).format('hh:mm a'));
                        end.val(moment(event.end).format('hh:mm a'));
                        doctor.val(event.doctor_id).trigger('change');
                        service.val(event.service_id).trigger('change');
                        customer.val(event.customer_id).trigger('change');
                        status.val(event.status);
                        console.log(event.status);
                        $('#event-modal').modal('show');
                    },
                    dateClick: function (info) {
                        date.val(moment(info.dateStr, 'YYYY-MM-DD').format('DD/MM/YYYY'));
                        $.merge(start, end).val(moment(info.date).format('hh:mm a'));
                        $('#event-modal').modal('show');

                    },
                    windowResize: function(view) {
                        let height = $(window).height() - 250;
                        height = height > 300 ? height : 300;
                        calendar.setOption('height', height);
                    },
                    events: events,
                });

                calendar.render();
            });
            let checkEvents = (doctor, date, hour) => {
                let dates = events.filter(obj => {
                        return Number(obj.doctor_id) === Number(doctor) && obj.date === date && Number(appId.val()) !== Number(obj.id);
                    }),
                    check = false;
                dates.forEach(function (item, i) {
                    let mStart = moment(moment(item.start, 'Y-MM-DD HH:mm:ss').format('hh:mm a'), 'hh:mm a'),
                        mEnd = moment(moment(item.end, 'Y-MM-DD HH:mm:ss').format('hh:mm a'), 'hh:mm a'),
                        range = moment(hour, 'hh:mm a');
                    if (range.isBetween(mStart, mEnd) /*|| range.isSame(mStart) || range.isSame(mEnd)*/) {
                        check = true;
                        return false;
                    }
                });
                return check;
            };

            $('#addEvent').click(function () {
                let errors = '',
                    mStart = moment(start.val(), 'h:mm:ss a'),
                    mEnd = moment(end.val(), 'h:mm:ss a'),
                    checkPrevious = checkEvents(doctor.val(), date.val(), start.val()) || checkEvents(doctor.val(), date.val(), end.val());
                if (checkPrevious) {
                    $.alert('Ya existe una cita previa con el médico dentro del rango de tiempo seleccionado.');
                    return false;
                }

                if (doctor.val() === '' || doctor.val() === null)
                    errors += '∙ El médico es obligatorio<br>';
                if (service.val() === '' || service.val() === null)
                    errors += '∙ El servicio es obligatorio<br>';
                if (customer.val() === '' || customer.val() === null)
                    errors += '∙ El cliente es obligatorio<br>';
                if (date.val() === '' || date.val() === null)
                    errors += '∙ La fecha es obligatoria<br>';
                if (start.val() === '' || start.val() === null)
                    errors += '∙ La hora de inicio es obligatoria<br>';
                if (end.val() === '' || end.val() === null)
                    errors += '∙ La hora de fin es obligatoria<br>';
                if (mStart.isAfter(mEnd))
                    errors += '∙ La hora de fin debe ser mayor a la hora de inicio';

                if (errors !== '') {
                    $.alert(errors);
                    return;
                }

                let event = {
                    doctor_id: doctor.val(),
                    service_id: service.val(),
                    customer_id: customer.val(),
                    title: `${customer.find('option:selected').text()} - ${service.find('option:selected').text()}`,
                    date: `${date.val()}`,
                    start: moment(`${date.val()} ${start.val()}`, 'DD/MM/YYYY h:mm:ss a').format('Y-MM-DD HH:mm:ss'),
                    end: moment(`${date.val()} ${end.val()}`, 'DD/MM/YYYY h:mm:ss a').format('Y-MM-DD HH:mm:ss'),
                    // ROOM
                    resourceId: doctor.val().toString(),
                };

                if (appId.val() !== '') {
                    event.status = status.val();
                    $.ajax({
                        type: 'POST',
                        url: `/appointment/update/${appId.val()}`,
                        data: event,
                        success: (res) => {
                            if (res.success) {
                                let index = events.findIndex(i => Number(i.id) === Number(appId.val()));
                                events[index].doctor_id = event.doctor_id;
                                events[index].service_id = event.service_id;
                                events[index].customer_id = event.customer_id;
                                events[index].title = event.title;
                                events[index].date = event.date;
                                events[index].start = event.start;
                                events[index].end = event.end;
                                events[index].resourceId = event.resourceId;
                                events[index].status = event.status;

                                let currEv = calendar.getEventById(events[index].id);
                                event.id = res.appointment.id;

                                currEv.remove();
                                console.log(event);
                                calendar.addEvent(event);

                                $('#event-modal').modal('hide');
                            } else {
                                let errors = '';
                                res.errors.forEach(function (item, i) {
                                    errors += `∙ ${item}<br>`;
                                });
                                $.alert({
                                    title: 'Error',
                                    type: 'red',
                                    content: errors,
                                });
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
                } else {
                    event.status = 1;
                    $.ajax({
                        type: 'POST',
                        url: '/appointment',
                        data: event,
                        success: (res) => {
                            if (res.success) {
                                event.id = res.appointment.id;

                                calendar.addEvent(event);
                                events.push(event);

                                $('#event-modal').modal('hide');

                            } else {
                                let errors = '';
                                res.errors.forEach(function (item, i) {
                                    errors += `∙ ${item}<br>`;
                                });
                                $.alert({
                                    title: 'Error',
                                    type: 'red',
                                    content: errors,
                                });
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

                }
            });

            $('#event-modal').on('hide.bs.modal', function () {
                appId.val('');
                $('#status_cont').addClass('d-none');
            });

            date.datetimepicker({
                format: 'DD/MM/YYYY',
                defaultDate: false,
                locale: 'es',
            });

            $('#start_time, #end_time').datetimepicker({
                format: 'hh:mm a',
                showClear: true,
                stepping: 5,
                defaultDate: moment(8, "HH"),
                icons: {
                    time: 'far fa-clock',
                    clear: 'fas fa-trash-alt',
                },
            });

            start.focusout(function (e) {
                let val = $(this).val(),
                    time = selServ !== null ? selServ.time : 0,
                    mStart = moment(val, 'h:mm:ss a'),
                    mEnd = moment(end.val(), 'h:mm:ss a'),
                    diff = mEnd.diff(mStart, 'minutes', true);
                if (time > 0 && diff < time)
                    end.val((mStart.add(time, 'minute').format('HH:mm a') ));
            });

            service.select2({
                placeholder: 'Servicio',
                allowClear: true,
            }).on("select2:select", function (e) {
                let optn = $(e.params.data.element),
                    time = optn.data('time');
                selServ = {
                    service: service.val(),
                    time: time
                };
                if (time !== '')
                    end.val(( moment(start.val(), 'h:mm:ss a').add(time, 'minute').format('HH:mm a') ));
            }).on("select2:unselect", function () {
                selServ = null;
            });

            doctor.select2({
                placeholder: 'Médico',
            });
            $('#branch_f').select2({
                placeholder: 'Sucursal',
            });
            $('#doctor_f').select2({
                placeholder: 'Médico',
            });

            customer.select2({
                language: 'es',
                placeholder: 'Nombre del cliente',
                ajax: {
                    url: '/customers/searchSelect',
                    method: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            string: params.term.trim() || "", // search term
                            page: params.page || 1,
                        };
                    },
                    processResults: function (items, params) {
                        return {
                            results: items.data,
                            pagination: {
                                more: (items.current_page * items.per_page) < items.total
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            }).on("select2:select", function (e) {
                //o.select(e.params.data);
            }).on("select2:unselect", function () {
                //o.unselect();
            });

            @if(auth()->user()->can('create-services'))
            $('#addService').click(function () {
                $.confirm({
                    title: 'Servicio',
                    content: '' +
                        '<form id="serviceForm">' +
                        '<div class="form-group">' +
                        '<input type="text" placeholder="Servicio" class="form-control" id="service_name" required/>' +
                        '</div>' +
                        '</form>',
                    buttons: {
                        formSubmit: {
                            text: 'Guardar',
                            btnClass: 'btn-blue',
                            action: function () {
                                let name = this.$content.find('#service_name').val();
                                if (!name) {
                                    $.alert('Agrega un nombre de servicio para continuar');
                                    return false;
                                }
                                $.ajax({
                                    type: 'POST',
                                    url: '/service',
                                    data: {
                                        name: $('#service_name').val(),
                                    },
                                    success: (res) => {
                                        if (res.success) {
                                            $('#service').append(`<option value="${res.service.id}">${res.service.name}</option>`).trigger('change');
                                        } else
                                            $.alert({
                                                title: 'Error',
                                                type: 'red',
                                                content: 'Ocurrió un error procesando la solicitud',
                                            });
                                    },
                                    error: () => {
                                        $.alert({
                                            title: 'Error',
                                            type: 'red',
                                            content: 'Ocurrió un error procesando la solicitud',
                                        });
                                    }
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
                    onContentReady: function () {
                        // bind to events
                        let jc = this;
                        this.$content.find('form').on('submit', function (e) {
                            // if the user submits the form by pressing enter in the field.
                            e.preventDefault();
                            jc.$$formSubmit.trigger('click'); // reference the button and click it
                        });
                    },
                    columnClass: 'col-md-6 col-md-offset-3'
                });
            });
            @endif
            @if(auth()->user()->can('create-customers'))
            $('#addCustomer').click(function () {
                $.confirm({
                    title: 'Cliente',
                    content: '' +
                        '<form id="customerForm">' +
                        '<div class="form-group">' +
                        '<input type="text" placeholder="Nombre" class="form-control mb-2" id="customer_name" required/>' +
                        '<input type="text" placeholder="Apellido" class="form-control mb-2" id="customer_last_name" required/>' +
                        '</div>' +
                        '</form>',
                    buttons: {
                        formSubmit: {
                            text: 'Guardar',
                            btnClass: 'btn-blue',
                            action: function () {
                                let name = this.$content.find('#customer_name').val();
                                if (!name) {
                                    $.alert('Agrega un nombre de cliente para continuar');
                                    return false;
                                }
                                $.ajax({
                                    type: 'POST',
                                    url: '/customer',
                                    data: {
                                        first_name: $('#customer_name').val(),
                                        last_name: $('#customer_last_name').val(),
                                    },
                                    success: (res) => {
                                        if (!res.success)
                                            $.alert({
                                                title: 'Error',
                                                type: 'red',
                                                content: 'Ocurrió un error procesando la solicitud',
                                            });
                                    },
                                    error: () => {
                                        $.alert({
                                            title: 'Error',
                                            type: 'red',
                                            content: 'Ocurrió un error procesando la solicitud',
                                        });
                                    }
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
                    onContentReady: function () {
                        // bind to events
                        let jc = this;
                        this.$content.find('form').on('submit', function (e) {
                            // if the user submits the form by pressing enter in the field.
                            e.preventDefault();
                            jc.$$formSubmit.trigger('click'); // reference the button and click it
                        });
                    },
                    columnClass: 'col-md-6 col-md-offset-3'
                });
            });
            @endif
        })();
    </script>
@stop
