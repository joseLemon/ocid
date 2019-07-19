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
        (() => {
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
        })();
    </script>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="side-menu col-auto p-0">
            <div class="menu-toggle">
                <button class="toggler d-lg-none"><i class="fas fa-bars"></i></button>
            </div>
            <div class="fixed-top">
                <button class="toggler d-lg-none"><i class="fas fa-bars"></i></button>
                <img src="../img/logo_white.png" alt="" class="nav-img">
                <ul>
                    <li class="side-nav-item">
                        <a href="/" class="btn side-nav-link">
                            <i class="fas fa-home"></i>
                            <span> Inicio </span>
                        </a>
                    </li>
                    @canany(['read-appointments', 'create-appointments'])
                        <li class="side-nav-item">
                            <button class="btn side-nav-link" aria-expanded="false">
                                <i class="fas fa-calendar-day"></i>
                                <span> Citas </span>
                                <span class="fas fa-chevron-right"></span>
                            </button>
                            <ul class="side-nav-second-level collapse" aria-expanded="false">
                                @if(auth()->user()->can('create-appointments'))
                                    <li>
                                        <a href="/appointment">Agregar</a>
                                    </li>
                                @endif
                                @if(auth()->user()->can('read-appointments'))
                                    <li>
                                        <a href="/appointments">Ver</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endcanany
                    @if(auth()->user()->hasRole('doctor'))
                        <li class="side-nav-item">
                            <a class="btn side-nav-link" href="/doctor/profile/{{ auth()->user()->id }}">
                                <i class="fas fa-user-md"></i>
                                <span> Mi cuenta </span>
                            </a>
                        </li>
                    @endif
                    @canany(['read-users', 'create-users'])
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
                    @endcanany
                    @canany(['read-users', 'create-users'])
                        <li class="side-nav-item">
                            <button class="btn side-nav-link" aria-expanded="false">
                                <i class="fas fa-user-md"></i>
                                <span> Médicos </span>
                                <span class="fas fa-chevron-right"></span>
                            </button>
                            <ul class="side-nav-second-level collapse" aria-expanded="false">
                                @if(auth()->user()->can('create-users'))
                                    <li>
                                        <a href="/doctor">Agregar</a>
                                    </li>
                                @endif
                                @if(auth()->user()->can('read-users'))
                                    <li>
                                        <a href="/doctors">Ver</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endcanany
                    @canany(['read-branches', 'create-branches'])
                        <li class="side-nav-item">
                            <button class="btn side-nav-link" aria-expanded="false">
                                <i class="fas fa-store-alt"></i>
                                <span> Sucursales </span>
                                <span class="fas fa-chevron-right"></span>
                            </button>
                            <ul class="side-nav-second-level collapse" aria-expanded="false">
                                @if(auth()->user()->can('create-branches'))
                                    <li>
                                        <a href="/branch">Agregar</a>
                                    </li>
                                @endif
                                @if(auth()->user()->can('read-branches'))
                                    <li>
                                        <a href="/branches">Ver</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endcanany
                    @canany(['read-customers', 'create-customers'])
                        <li class="side-nav-item">
                            <button class="btn side-nav-link" aria-expanded="false">
                                <i class="fas fa-user-friends"></i>
                                <span> Clientes </span>
                                <span class="fas fa-chevron-right"></span>
                            </button>
                            <ul class="side-nav-second-level collapse" aria-expanded="false">
                                @if(auth()->user()->can('create-customers'))
                                    <li>
                                        <a href="/customer">Agregar</a>
                                    </li>
                                @endif
                                @if(auth()->user()->can('read-customers'))
                                    <li>
                                        <a href="/customers">Ver</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endcanany
                    @canany(['read-services', 'create-services'])
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
                    @endcanany
                    <li class="side-nav-item">
                        <a class="btn side-nav-link" href="/logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span> Cerrar sesión </span>
                        </a>
                    </li>
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
    (() => {
        $('.toggler').click(function () {
            console.log('here');
            let menu = $('.side-menu .fixed-top');

            if (menu.hasClass('active'))
                menu.removeClass('active');
            else
                menu.addClass('active');
        });
    })();
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
