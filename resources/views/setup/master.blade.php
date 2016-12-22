<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="/css/materialize.css"  media="screen,projection"/>

    <link type="text/css" rel="stylesheet" href="/css/materialize.clockpicker.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="/css/app.css"  media="screen,projection"/>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <meta name="theme-color" content="#009688" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body data-lang="en">

    <script>
        var domCallbacks = [];
    </script>

    <div id="content" style="padding-top: 100px;">
        @yield('content')
    </div>

<!-- Materialize.css -->
<script src="{{ url('js/materialize.min.js') }}"></script>
<!-- Materialize.clockpicker.css -->
<script src="{{ url('js/materialize.clockpicker.js') }}"></script>
<!-- ciliatus -->
<script src="{{ url('js/app.js') }}"></script>


<script>
    $(document).ready(function() {
        window.runPage();
    });
</script>

</body>
</html>