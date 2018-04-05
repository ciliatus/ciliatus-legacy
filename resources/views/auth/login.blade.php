<!DOCTYPE html>
<html>
    <head>
        <title>Ciliatus - Login</title>

        <link rel="manifest" href="manifest.json">
        <link rel="icon" href="/images/manifest/launcher-icon-0-75x.png" type="image/png">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/css/vendors/materialize.min.css"  media="screen,projection"/>

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

    <body class="orange darken-4">
        <div class="container" style="padding-top: 20px">
            <div class="row center-align">
                <img src="/svg/Ciliatus_Logo.svg" class="center-align" style="width: 300px; height: 300px" />
                <div class="white-text" id="login-title">
                    <span>Ciliatus</span>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 offset-m3 l4 offset-l4">
                    @if (count($errors) > 0)
                    <div class="card-panel">
                        @foreach ($errors->all() as $error)
                            <i class="mdi mdi-18px mdi-window-close red-text">
                            <strong>{{ $error }}</strong><br />
                        @endforeach
                    </div>
                    @endif
                    <div class="card">
                        <form method="POST" action="{{ url('auth/login') }}">
                            {!! csrf_field() !!}
                            <div class="card-content">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" class="validate" required=""
                                               name="email" id="email" placeholder="@lang('labels.email')"
                                               value="{{ old('email') }}" />
                                        <label for="email">@lang('labels.email')</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="password" class="form-control" required=""
                                               name="password" id="password" placeholder="@lang('labels.password')" />
                                        <label for="password">@lang('labels.password')</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col s12">
                                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label for="remember">@lang('labels.remember_me')</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <button class="btn btn-default submit" type="submit">Log in</button>
                                    </div>
                                </div>
                                @if (env('DEMO_ENVIRONMENT', false) == true)
                                <div>
                                    <br />
                                    Username: <strong>demo@ciliatus.io</strong><br />
                                    Password: <strong>demo</strong><br />
                                    <br />
                                    Data is pulled from the live environment occasionally.<br />
                                    In the meantime demo data is generated.
                                </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materialize.js -->
        <script src="{{ url('/js/vendors/materialize.min.js') }}"></script>

    </body>
</html>