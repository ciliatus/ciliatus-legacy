<!DOCTYPE html>
<html>
    <head>
        <title>Ciliatus - Login</title>

        <link rel="manifest" href="manifest.json">
        <link rel="icon" href="/images/manifest/launcher-icon-0-75x.png" type="image/png">

        <link rel="stylesheet" href="/css/vendors/materialdesignicons-2.1.99.min.css?v=201804291751">
        <link rel="stylesheet" href="/css/vendors/montserrat.css?v=201804291751">
        <link type="text/css" rel="stylesheet" href="/css/vendors/materialize.min.css?v=201804291751"  media="screen,projection"/>

        <script type="text/javascript" src="{{ url('/js/vendors/jquery-3.3.1.min.js?v=201804291751') }}"></script>
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
        <div id="login-failed-modal" class="modal" style="max-width: 600px;">
            <div class="modal-content">
                @foreach ($errors->all() as $error)
                <h5>
                    <i class="mdi mdi-36px mdi-close-circle-outline red-text"></i>
                    @lang('errors.frontend.auth.title')
                </h5>
                @lang('errors.frontend.' . $error)
                @endforeach
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close btn btn-default waves-effect waves-orange">@lang('buttons.ok')</a>
            </div>
        </div>

        <div class="container" style="padding-top: 20px">
            <div class="row center-align">
                <img src="/svg/Ciliatus_Logo.svg" class="center-align" style="width: 200px; height: 200px" />
                <div class="white-text" id="login-title">
                    <span>Ciliatus</span>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 offset-m3 l4 offset-l4">
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
                                    <div class="col s12 m6">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <span>@lang('labels.remember_me')</span>
                                        </label>
                                    </div>
                                    <div class="col s12 m6 right-align">
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

        <script>
            $(function() {
                $('.modal').modal();
                @if (count($errors) > 0)
                    M.Modal.getInstance($('#login-failed-modal')).open();
                @endif
            })
        </script>
    </body>
</html>