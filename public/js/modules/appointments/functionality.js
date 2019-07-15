(() => {
    let doctor = $('#doctor'),
        service = $('#service'),
        customer = $('#customer'),
        date = $('#date'),
        start = $('#start_time'),
        end = $('#end_time'),
        selServ = null,
        selDoc = null,
        valid = false,
        appId = $('#appointment_id'),
        checkEvents = (doctor, date, hour, start) => {
            let dates = events.filter(obj => {
                    return Number(obj.doctor_id) === Number(doctor) && obj.date === date && Number(appId.val()) !== Number(obj.id);
                }),
                check = false;
            dates.forEach(function (item, i) {
                let mStart = moment(moment(item.start, 'Y-MM-DD hh:mm:ss').format('hh:mm a'), 'hh:mm a'),
                    mEnd = moment(moment(item.end, 'Y-MM-DD hh:mm:ss').format('hh:mm a'), 'hh:mm a'),
                    range = moment(hour, 'hh:mm a');
                if (range.isBetween(mStart, mEnd) || (start ? range.isSame(mStart) : range.isSame(mEnd))/*|| range.isSame(mStart) || range.isSame(mEnd)*/) {
                    check = true;
                    return false;
                }
            });
            return check;
        },
        checkSchedules = () => {
            let check = true,
                check2 = true,
                dow = moment(date.val(), 'DD/MM/YYYY').day(),
                mStart = moment(start.val(), 'hh:mm a'),
                mEnd = moment(end.val(), 'hh:mm a'),
                schedule = 'Horario:<br>';
            if (selDoc.schedules[dow]) {
                selDoc.schedules[dow].forEach(function (item, i) {
                    let scStart = moment(moment(item.start_time, 'hh:mm').format('hh:mm a'), 'hh:mm a'),
                        scEnd = moment(moment(item.end_time, 'hh:mm').format('hh:mm a'), 'hh:mm a');
                    if (mStart.isBetween(scStart, scEnd) || mStart.isSame(scStart) || mStart.isSame(scEnd) && check) {
                        check = false;
                    }
                    if (mEnd.isBetween(scStart, scEnd) || mEnd.isSame(scStart) || mEnd.isSame(scEnd) && check2) {
                        check2 = false;
                    }

                    schedule += `• De ${scStart.format('hh:mm a')} a ${scEnd.format('hh:mm a')}<br>`;
                });

                return {
                    success: check || check2,
                    schedule: schedule,
                };
            } else {
                return {
                    success: false,
                }
            }
        },
        initDoctorTime = (init = false) => {
            let dateVal = date.val();
            if (!init)
                date.datetimepicker('destroy');

            let dP = {
                format: 'DD/MM/YYYY',
                defaultDate: false,
                locale: 'es',
                minDate: false,
            };

            if (appId.val() === '')
                dP.minDate = moment();

            if (selDoc !== null) {
                dP.disabledDates = selDoc.daysOff;
            }

            date.datetimepicker(dP).val(dateVal);

            $('#start_time, #end_time').datetimepicker({
                format: 'hh:mm a',
                showClear: true,
                stepping: 5,
                defaultDate: moment(8, "hh"),
                icons: {
                    time: 'far fa-clock',
                    clear: 'fas fa-trash-alt',
                },
            });

            //start.val(startVal);
            //end.val(endVal);
        };
    initDoctorTime(true);

    start.focusout(function (e) {
        let val = $(this).val(),
            time = selServ !== null ? selServ.time : 0,
            mStart = moment(val, 'h:mm:ss a'),
            mEnd = moment(end.val(), 'h:mm:ss a'),
            diff = mEnd.diff(mStart, 'minutes', true);
        if (time > 0 && diff < time)
            end.val((mStart.add(time, 'minute').format('hh:mm a') ));
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
            end.val(( moment(start.val(), 'h:mm:ss a').add(time, 'minute').format('hh:mm a') ));
    }).on("select2:unselect", function () {
        selServ = null;
    });

    doctor.select2({
        placeholder: 'Médico',
    }).on("select2:select", function (e) {
        selDoc = doctors.find(function (obj) {
            return obj.id === doctor.val();
        });
        initDoctorTime();
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

    $('form').submit(function (e) {
        if (!valid) {
            e.preventDefault();

            let errors = '',
                mStart = moment(start.val(), 'h:mm:ss a'),
                mEnd = moment(end.val(), 'h:mm:ss a'),
                checkPrevious = checkEvents(doctor.val(), date.val(), start.val(), true) || checkEvents(doctor.val(), date.val(), end.val(), false);

            if (mEnd.isBefore(mStart) || mEnd.isSame(mStart)) {
                $.alert('La hora de fin debe ser mayor a la hora de inicio');
                return false;
            }
            if (checkPrevious) {
                $.alert('Ya existe una cita previa con el médico dentro del rango de tiempo seleccionado');
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
                return false;
            }

            selDoc = doctors.find(function (obj) {
                return Number(obj.id) === Number(doctor.val());
            });

            let schCheck = checkSchedules();
            if (schCheck.success) {
                $.alert(`El periodo de tiempo elegido no se encuentra disponible dentro de los horarios del médico.<br><br>${schCheck.schedule}`);
                return false;
            }

            valid = true;
            $(this).submit();
        }
    });
})();
