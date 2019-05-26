<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
          integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hyper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('header')
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="side-menu float-left">
            <div class="fixed-top">
                <ul>
                    <li class="side-nav-item">
                        <a href="/" class="btn side-nav-link">
                            <i class="fas fa-home"></i>
                            <span> Inicio </span>
                        </a>
                    </li>
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
                    <li class="side-nav-item">
                        <button class="btn side-nav-link" aria-expanded="false">
                            <i class="fas fa-list"></i>
                            <span> Servicios </span>
                            <span class="fas fa-chevron-right"></span>
                        </button>
                        <ul class="side-nav-second-level collapse" aria-expanded="false">
                            <li>
                                <a href="">Agregar</a>
                            </li>
                            <li>
                                <a href="">Ver</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="content col">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"><a href="#">Library</a></li>
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

<script src={{ asset('js/app.js') }}></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
@yield('scripts')
<script>
    let generateUUID = () => {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            let r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
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

</body>
</html>
