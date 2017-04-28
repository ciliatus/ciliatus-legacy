<!DOCTYPE html>
<html>
    <head>
        <title>Ciliatus - Login</title>

        <link rel="manifest" href="manifest.json">
        <link rel="icon" href="/images/manifest/launcher-icon-0-75x.png" type="image/png">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/css/vendors/materialize.css"  media="screen,projection"/>

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
        <div class="container" style="padding-top: 100px">
            <div class="row center-align">
                <img src="/images/menu_logo.png" class="center-align" />
            </div>
            <div class="row">
                <div class="col s12 m6 offset-m3 l4 offset-l4">
                    <div class="card">
                        <form method="POST" action="{{ url('auth/login') }}">
                            {!! csrf_field() !!}
                            <div class="card-content">
                                <div>
                                    <input type="text" class="form-control" placeholder="Username" required="" name="email" value="{{ old('email') }}" />
                                </div>
                                <div>
                                    <input type="password" class="form-control" placeholder="Password" required="" name="password" id="password" />
                                </div>
                                <div>
                                    <button class="btn btn-default submit" type="submit">Log in</button>
                                </div>
                                @if (env('DEMO_ENVIRONMENT', false) == true)
                                <div>
                                    <br />
                                    Username: <strong>demo@ciliatus.io</strong><br />
                                    Password: <strong>demo</strong><br />
                                    <br />
                                    Real-time updates are disabled.<br />
                                    Data pulled from live environment daily.
                                </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>