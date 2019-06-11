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
                                <label for="doctor">Doctor</label>
                                <select class="form-control" id="doctor"></select>
                            </div>
                            <div class="form-group">
                                <label for="service">Servicio</label>
                                <div class="input-group">
                                    <select class="form-control select-2-g-append" id="service">
                                        <option value="" selected disabled></option>
                                        @foreach($services as $id => $service)
                                            <option value="{{ $id }}">{{ $service }}</option>
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
                                    <select class="form-control select-2-g-append" id="customer"></select>
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
                let date = $('#date').val(),
                    start = $('#start_date').val(),
                    end = $('#end_date').val();

                calendar.addEvent({
                    title: 'dynamic event',
                    start: moment(`${date} ${start}`).format('Y-MM-DD HH:mm:ss'),
                    end: moment(`${date} ${end}`).format('Y-MM-DD HH:mm:ss'),
                    resourceId: 'a',
                });
            });

            $('#date').datetimepicker({
                format: 'DD/MM/YYYY',
                defaultDate: false,
                locale: 'es',
            });

            $('#start_date, #end_date').datetimepicker({
                format: 'hh:mm a',
                showClear: true,
                stepping: 10,
                defaultDate: moment(8, "HH"),
                icons: {
                    time: 'far fa-clock',
                    clear: 'fas fa-trash-alt',
                },
            });

            $('#service').select2();
            $('#doctor').select2();

            $('#customer').select2({
                language: 'es',
                allowClear: true,
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
