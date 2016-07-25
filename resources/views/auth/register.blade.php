<!-- resources/views/auth/login.blade.php -->

<form method="POST" action="/auth/login">
    {!! csrf_field() !!}

    <div>
        Email
        <input type="email" name="email" value="{{ old('email') }}">
    </div>

    <div>
        Password
        <input type="password" name="password" id="password">
    </div>

    <div>
        <input type="checkbox" name="remember"> Remember Me
    </div>

    <div>
        <button type="submit">Login</button>
    </div>
</form>

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
    {!! Html::style('build/css/custom.min.css') !!}
    <link href="../" rel="stylesheet">
</head>

<body class="login">
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
        <div id="register" class="animate form registration_form">
            <section class="login_content">
                <form method="POST" action="{{ url('auth/register') }}">
                    {!! csrf_field() !!}
                    <h1>Create Account</h1>
                    <div>
                        <input type="text" class="form-control" placeholder="Username" required="" name="name" />
                    </div>
                    <div>
                        <input type="email" class="form-control" placeholder="Email" required="" name="email" />
                    </div>
                    <div>
                        <input type="password" class="form-control" placeholder="Password" required="" name="password" />
                    </div>
                    <div>
                        <input type="password" class="form-control" placeholder="Confirm Password" required="" name="password_confirmation" />
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default submit">Submit</button>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <p class="change_link">Already a member ?
                            <a href="{{ url('auth/login') }}" class="to_register"> Log in </a>
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