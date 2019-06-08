@extends('layouts.master')
@section('header')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/b-1.5.6/b-html5-1.5.6/b-print-1.5.6/fh-3.1.4/r-2.2.2/sc-2.0.0/datatables.min.css"/>

@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-hover w-100" id="users-table">
                <colgroup>
                    <col style="width: 10%">
                    <col style="width: 40%">
                    <col style="width: 40%">
                    <col style="width: 10%">
                </colgroup>
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Tipo de usuario</th>
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/fh-3.1.4/r-2.2.2/sc-2.0.0/datatables.min.js"></script>

    <script>
        /*let fillTable = (tableId, data) => {
                let table = $(`#${tableId}`),
                    tbody = table.find('tbody'),
                    html = '';

                tbody.empty();
                data.forEach(function (item, i) {
                    html += `<tr>`;
                    html += `<td>${item.id}</td>`;
                    html += `<td>${item.name}</td>`;
                    html += `<td><ul class="list-unstyled mb-0">` +
                        `<li><a href="/user/${item.id}" class=" text-left btn btn-block btn-link btn-sm p-0" style="white-space: nowrap;"><i class="far fa-edit"></i>&nbsp;Editar</a></li>` +
                        `<li><a href="" class=" text-left btn btn-block btn-link btn-sm p-0" style="white-space: nowrap;"><i class="far fa-trash-alt"></i>&nbsp;Eliminar</a></li>` +
                        `</ul></td>`;
                    html += `</tr>`;
                });

                tbody.append(html);
            };
        $.ajax({
            type: 'GET',
            url: '/users/search',
            success: function (res) {
                console.log(res.links);
                fillTable('users-table', res.data);
            },
            error: function () {

            }
        })*/
        let dataTables = {
            lang: {
                sProcessing: "Procesando...",
                sLengthMenu: "Mostrar _MENU_ registros",
                sZeroRecords: "No se encontraron resultados",
                sEmptyTable: "Ningún dato disponible en esta tabla",
                sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                sInfoPostFix: "",
                sSearch: "Buscar:",
                sUrl: "",
                sInfoThousands: ",",
                sLoadingRecords: "Cargando...",
                oPaginate: {
                    sFirst: "Primero",
                    sLast: "Último",
                    sNext: "Siguiente",
                    sPrevious: "Anterior"
                },
                oAria: {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
            }
        };
        (() => {
            let table = null,
                renderMenu = (id) => {
                    let menu =
                        `<div class="dropdown">
                        <a href="#" class="d-block" style="width:30px;" id="dropdownMenu${id}" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">
                            <i class="fas fa-bars"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu${id}">
                            <a class="dropdown-item" href="/user/${id}"><i class="fas fa-edit"></i> Edit</a>
                            <button class="dropdown-item btn btn-link" type="button" onclick="deleteHandler(${id})"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                    </div>`;
                    return menu;
                };
            dataTables.users = table = $("#users-table").DataTable({
                keys: !0,
                language: dataTables.lang,
                responsive: true,
                scrollCollapse: true,
                processing: true,
                serverSide: true,
                //searchDelay: 600,
                ajax: {
                    length: 1,
                    url: "/users/search",
                    type: "GET",
                    beforeSend: () => {
                        if (table && table.hasOwnProperty('settings')) {
                            table.settings()[0].jqXHR.abort();
                        }
                    },
                    dataFilter: (data) => {
                        let json = $.parseJSON(data),
                            columns = [];
                        for (let i in json.data) {
                            columns.push({
                                id: json.data[i].id,
                                name: json.data[i].name,
                                role: json.data[i].role,
                                options: renderMenu(json.data[i].id),
                            });
                        }
                        json.data = columns;
                        return JSON.stringify(json);
                    },
                },
                columns: [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'role'},
                    {
                        data: 'options',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '30px',
                    },
                ],
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                },
                initComplete: function (settings, json) {
                    let search = $(this).closest('.dataTables_wrapper').find('input[type=search]');
                    search.unbind();
                    search.bind('keyup', function (e) {
                        if (e.which === 13) {
                            table.search(this.value).draw();
                        }
                    });
                }
            });
        })();
    </script>
@endsection
