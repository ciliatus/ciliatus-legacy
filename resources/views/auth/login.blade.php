<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ciliatus</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ url('vendors/bootstrap/dist/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('vendors/bootstrap/dist/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('css/bootstrap_xl.css') }} " />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('vendors/font-awesome/css/font-awesome.min.css') }}" />
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" />
    <!-- Switchery -->
    <link rel="stylesheet" href="{{ url('vendors/switchery/dist/switchery.min.css') }}" />
    <!-- Custom Theme Style -->
    <link rel="stylesheet" href="{{ url('build/css/custom.css') }}" />

    <!-- PNotify -->
    <link rel="stylesheet" href="{{ url('vendors/pnotify/dist/pnotify.css') }}" />
    <link rel="stylesheet" href="{{ url('vendors/pnotify/dist/pnotify.buttons.css') }}" />
    <link rel="stylesheet" href="{{ url('vendors/pnotify/dist/pnotify.nonblock.css') }}" />
    <!-- Datatables -->
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" />
    <!-- jQuery -->
    <script src="{{ url('vendors/jquery/dist/jquery.min.js') }}"></script>
    <!-- jQuery UI -->
    <link rel="stylesheet" href="{{ url('vendors/jquery-ui/jquery-ui.min.css') }}" />
    <script src="{{ url('vendors/jquery-ui/jquery-ui.min.js') }}"></script>

</head>

<body class="login">
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <form method="POST" action="{{ url('auth/login') }}">
                    {!! csrf_field() !!}
                    <h1>Login Form</h1>
                    <div>
                        <input type="text" class="form-control" placeholder="Username" required="" name="email" value="{{ old('email') }}" />
                    </div>
                    <div>
                        <input type="password" class="form-control" placeholder="Password" required="" name="password" id="password" />
                    </div>
                    <div>
                        <input type="checkbox" name="remember"> Remember Me
                    </div>
                    <div>
                        <button class="btn btn-default submit" type="submit">Log in</button>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <p class="change_link">New to site?
                            <a href="{{ url('auth/register') }}" class="to_register"> Create Account </a>
                        </p>

                        <div class="clearfix"></div>
                        <br />
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
</body>
</html>