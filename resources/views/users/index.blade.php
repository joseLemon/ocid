@extends('layouts.master')
@section('header')
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-hover" id="users-table">
                <colgroup>
                    <col style="width: 10%">
                    <col style="width: 80%">
                    <col style="width: 10%">
                </colgroup>
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let
            fillTable = (tableId, data) => {
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
        })
    </script>
@endsection
