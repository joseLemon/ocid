$('#addBranch').click(function () {
    $.confirm({
        title: 'Sucursal',
        content: '' +
            '<form id="branchForm">' +
            '<div class="form-group">' +
            '<input type="text" placeholder="Sucursal" class="form-control" id="branch_name" required/>' +
            '</div>' +
            '</form>',
        buttons: {
            formSubmit: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    let name = this.$content.find('#branch_name').val();
                    if (!name) {
                        $.alert('Agrega un nombre de sucursal para continuar');
                        return false;
                    }
                    $.ajax({
                        type: 'POST',
                        url: '/branch',
                        data: {
                            name: $('#branch_name').val(),
                        },
                        success: (res) => {
                            if (res.success) {
                                $('#branch').append(`<option value="${res.branch.id}">${res.branch.name}</option>`).trigger('change');
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
