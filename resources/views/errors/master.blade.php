<!DOCTYPE html>
<html style="height: calc(100% - 75px);">
    <head>
        <title>Ciliatus - @if(App::isDownForMaintenance()) Maintenance @else @yield('error_id') @endif</title>

        <link rel="manifest" href="manifest.json">
        <link rel="icon" href="/images/manifest/launcher-icon-0-75x.png" type="image/png">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,900" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/css/vendors/materialize.min.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/css/vendors/timeline.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/css/vendors/materialize.clockpicker.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/css/vendors/dygraph.min.css"  media="screen,projection"/>

        <script type="text/javascript" src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script>
            window.Laravel = {
                csrfToken: "{{ csrf_token() }}"
            };
        </script>

        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <!-- Chrome, Firefox OS and Opera -->
        <meta name="theme-color" content="#e65100">
        <!-- Windows Phone -->
        <meta name="msapplication-navbutton-color" content="#e65100">
        <!-- iOS Safari -->
        <meta name="apple-mobile-web-app-status-bar-style" content="#e65100">
        <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body class="orange darken-4">

        <div>
            <div class="container" style="padding-top: 20px">
                <div class="row center-align">
                    <img src="/svg/Ciliatus_Logo.svg" class="center-align" style="width: 150px; height: 150px; margin-top: 100px;" />
                </div>
                <div class="row center-align white-text" style="margin-top: 100px;">
                    <h5>@yield('error_id')</h5>
                    <h1 class="thin">@yield('error_text')</h1>
                    <strong>@yield('error_description')</strong>
                </div>
            </div>
            @if(!App::isDownForMaintenance())
            <div class="row center-align white-text">
                <a class="btn btn-primary" href="/">
                    <i class="mdi mdi-18px mdi-keyboard-return"></i>
                    Return to start
                </a>
            </div>
            @endif
        </div>

    </body>

</html>