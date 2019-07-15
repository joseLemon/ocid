@extends('layouts.master')
@section('header')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/b-1.5.6/b-html5-1.5.6/b-print-1.5.6/fh-3.1.4/r-2.2.2/sc-2.0.0/datatables.min.css"/>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-hover w-100" id="data-table">
                <colgroup>
                    <col style="width: 3%">
                    <col style="width: 15%">
                    <col style="width: 15%">
                    <col style="width: 15%">
                    <col style="width: 15%">
                    <col style="width: 17%">
                    <col style="width: 17%">
                    <col style="width: 3%">
                </colgroup>
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Estatus</th>
                    <th>Fecha</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Médico</th>
                    <th>Cliente</th>
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
        (() => {
            let table = null,
                getStatusName = (status) => {
                    switch (status) {
                        default:
                            return 'A tiempo';
                        case 'active':
                            return 'Visto';
                        case 'delayed':
                            return 'Retrasado';
                        case 'cancelled':
                            return 'Cancelado';
                    }
                },
                getStatusColorClass = (status) => {
                    switch (status) {
                        default:
                            return 'primary';
                        case 'active':
                            return 'success';
                        case 'delayed':
                            return 'warning';
                        case 'cancelled':
                            return 'danger';
                    }
                },
                renderMenu = (id) => {
                    let menu =
                        `<div class="dropdown">
                        <a href="#" class="d-block" style="width:30px;" id="dropdownMenu${id}" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">
                            <i class="fas fa-bars"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu${id}">
                            <a class="dropdown-item" href="/appointment/${id}"><i class="fas fa-edit"></i> Edit</a>
                            <button class="dropdown-item btn btn-link" type="button" onclick="deleteHandler(${id})"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                    </div>`;
                    return menu;
                };
            dataTables.services = table = $("#data-table").DataTable({
                keys: !0,
                language: dataTables.lang,
                responsive: true,
                scrollCollapse: true,
                processing: true,
                serverSide: true,
                //searchDelay: 600,
                ajax: {
                    length: 1,
                    url: "/appointments/search",
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
                                status: `<span class="badge badge-pill badge-${getStatusColorClass(json.data[i].status)}">&nbsp;</span>&nbsp;${getStatusName(json.data[i].status)}`,
                                date: json.data[i].date,
                                start: json.data[i].start,
                                end: json.data[i].end,
                                doctor_name: json.data[i].doctor_name,
                                customer_name: json.data[i].customer_name,
                                options: renderMenu(json.data[i].id),
                            });
                        }
                        json.data = columns;
                        return JSON.stringify(json);
                    },
                },
                columns: [
                    {data: 'id'},
                    {data: 'status'},
                    {data: 'date'},
                    {data: 'start'},
                    {data: 'end'},
                    {data: 'doctor_name'},
                    {
                        data: 'customer_name',
                        orderable: false,
                        searchable: false,
                    },
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
