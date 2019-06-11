<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
          integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hyper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('header')
    <script src={{ asset('js/app.js') }}></script>
    <script>
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
    </script>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="side-menu col-auto">
            <div class="fixed-top">
                <ul>
                    <li class="side-nav-item">
                        <a href="/" class="btn side-nav-link">
                            <i class="fas fa-home"></i>
                            <span> Inicio </span>
                        </a>
                    </li>
                    @if(auth()->user()->can(['read-users', 'create-users']))
                        <li class="side-nav-item">
                            <button class="btn side-nav-link" aria-expanded="false">
                                <i class="fas fa-user"></i>
                                <span> Usuarios </span>
                                <span class="fas fa-chevron-right"></span>
                            </button>
                            <ul class="side-nav-second-level collapse" aria-expanded="false">
                                @if(auth()->user()->can('create-users'))
                                    <li>
                                        <a href="/user">Agregar</a>
                                    </li>
                                @endif
                                @if(auth()->user()->can('read-users'))
                                    <li>
                                        <a href="/users">Ver</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    <li class="side-nav-item">
                        <button class="btn side-nav-link" aria-expanded="false">
                            <i class="fas fa-user-md"></i>
                            <span> Medicos </span>
                            <span class="fas fa-chevron-right"></span>
                        </button>
                        <ul class="side-nav-second-level collapse" aria-expanded="false">
                            <li>
                                <a href="/doctor">Agregar</a>
                            </li>
                            <li>
                                <a href="">Ver</a>
                            </li>
                        </ul>
                    </li>
                    @if(auth()->user()->can(['read-services', 'create-services']))
                        <li class="side-nav-item">
                            <button class="btn side-nav-link" aria-expanded="false">
                                <i class="fas fa-list"></i>
                                <span> Servicios </span>
                                <span class="fas fa-chevron-right"></span>
                            </button>
                            <ul class="side-nav-second-level collapse" aria-expanded="false">
                                @if(auth()->user()->can('create-services'))
                                    <li>
                                        <a href="/service">Agregar</a>
                                    </li>
                                @endif
                                @if(auth()->user()->can('read-services'))
                                    <li>
                                        <a href="/services">Ver</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="content col">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item @if(!isset($crumb1)){{ 'active' }}@endif"><a href="/">Inicio</a></li>
                            @if(isset($crumb1))
                                <li class="breadcrumb-item @if(!isset($crumb2)){{ 'active' }}@endif"><a href="{{ $crumb1['route'] }}">{{ $crumb1['name'] }}</a></li>
                            @endif
                            @if(isset($crumb2))
                                <li class="breadcrumb-item active"><a href="{{ $crumb2['route'] }}">{{ $crumb2['name'] }}</a></li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="card-deck">
                @yield('content')
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/es.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script>
    let generateUUID = () => {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                let r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        },
        dataTables = {
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
    $(document).ready(function () {
        (() => {
            $('.side-nav-link').click(function () {
                let btn = $(this),
                    collapse = btn.next();
                collapse.collapse('toggle');
            });
            $('.collapse').on('show.bs.collapse', function () {
                let shown = $('.collapse.show');
                shown.collapse('hide');
            }).on('show.bs.collapse', function () {
                let collapse = $(this),
                    btn = collapse.prev();
                $.merge(collapse, btn).attr('aria-expanded', "true");
            }).on('hide.bs.collapse', function () {
                let collapse = $(this),
                    btn = collapse.prev();
                $.merge(collapse, btn).attr('aria-expanded', "false");
            });
        })();
    });
</script>
@yield('scripts')

</body>
</html>
