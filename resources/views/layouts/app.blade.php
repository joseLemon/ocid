<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
          integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        @font-face {
            font-family: 'Kaushan';
            src: url("{{ asset('fonts/KaushanScriptRegular.otf') }}");
        }
        .card {
            border: none;
            -webkit-box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
            box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
            margin-bottom: 30px;
        }

        .kaushan {
            font-family: Kaushan, "sans-serif";
        }

        /*** LOGIN ***/
        #app {
            background: url("{{ asset('img/login_bg.jpg') }}") no-repeat center center;
            background-size: cover;
            height: 100vh;
        }

        #app .card {
            background-color: transparent;
            border: 1px solid rgba(255,255,255,1);
            border-radius: 0;
        }

        #app .card-header:first-child {
            display: none;
        }

        #app .card-body h3 {
            font-family: Kaushan, sans-serif;
            color: #fff;
            font-size: 40px;
            /*margin-bottom: 75px;*/
        }

        #app form {
            text-align: center;
        }

        #app form .form-group {
            position: relative;
        }

        #app form label.icon {
            position: absolute;
            display: flex;
            height: 36px;
            width: 36px;
            top: 2px;
            left: 3px;
            font-size: 18px;
            background-color: #e4e8ea;
            border-radius: 50%;
        }

        #app form label.icon i {
            color: #e6a43b;
            left: calc(50% - 1px);
            top: 50%;
            transform: translate(-50%,-50%);
            position: relative;
        }

        #app input[type="email"],
        #app input[type="password"] {
            font-size: 16px;
            color: #8d8f87;
            /*width: 325px;*/
            margin-bottom: 5px;
            padding-left: 45px;
            border: none;
            border-radius: 50px;
            background-color: #d0d2d5;
        }

        #app button[type="submit"] {
            font-size: 16px;
            color: #fff;
            /*width: 325px;*/
            padding: 5px 20px;
            border: none;
            border-radius: 50px;
            background-color: #e6a43b;
            transition: all 350ms ease;
            -webkit-transition: all 350ms ease;
            -moz-transition: all 350ms ease;
            -ms-transition: all 350ms ease;
            -o-transition: all 350ms ease;
        }

        #app ::-webkit-input-placeholder {
            color: #8d8f87;
        }
        #app ::-moz-placeholder {
            color: #8d8f87;
        }
        #app :-moz-placeholder {
            color: #8d8f87;
        }
        #app :-ms-input-placeholder {
            color: #8d8f87;
        }

        #app .btn-link,
        #app .form-check-label {
            color: #fff;
        }

    </style>
</head>
<body>
<div id="app">
    <main class="py-4">
        <div class="mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        @yield('content')
                    </div>
                    <div class="w-100"></div>
                    <div class="col-lg-5 text-center">
                        <img src="../img/logo_white.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
