<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gentallela Alela! | </title>

    <!-- Bootstrap -->
    {!! Html::style('vendors/bootstrap/dist/css/bootstrap.min.css') !!}
    <!-- Font Awesome -->
    {!! Html::style('vendors/font-awesome/css/font-awesome.min.css') !!}
    <!-- Animate.css -->
    {!! Html::style('vendors/animate.css/animate.min.css') !!}
    <link href="" rel="stylesheet">

    <!-- Custom Theme Style -->
    {!! Html::style('settings/css/custom.min.css') !!}
    <link href="../" rel="stylesheet">
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