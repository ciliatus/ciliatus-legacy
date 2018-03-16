<!DOCTYPE html>
<html>
    <head>
        <title>Ciliatus Setup</title>

        <link rel="manifest" href="manifest.json">
        <link rel="icon" href="/images/manifest/launcher-icon-0-75x.png" type="image/png">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,900" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/css/vendors/materialize.min.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/css/vendors/timeline.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/css/vendors/materialize.clockpicker.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/css/vendors/dygraph.min.css"  media="screen,projection"/>

        <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
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

    <body data-lang="en">

        <script>
            var domCallbacks = [];
        </script>

        <div id="body" style="padding-top: 100px;">
            @yield('content')
        </div>

        <!-- Materialize.js -->
        <script src="{{ url('/js/vendors/materialize.min.js') }}"></script>
        <!-- Materialize.clockpicker.js -->
        <script src="{{ url('/js/vendors/materialize.clockpicker.js') }}"></script>
        <!-- Masonry -->
        <script src="{{ url('/js/vendors/masonry-4.1.1.min.js') }}"></script>
        <!-- ciliatus -->
        <script src="{{ url('/js/app.js') }}"></script>
        <!-- Vue -->
        <script src="{{ url('/js/vendors/vue.js') }}"></script>

        @yield('scripts')

        <script>
            $(document).ready(function() {
                window.runPage();
            });
        </script>

    </body>
</html>