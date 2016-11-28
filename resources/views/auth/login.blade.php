<!DOCTYPE html>
<html>
    <head>
        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="/css/materialize.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/css/app.css"  media="screen,projection"/>

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col s12 m6 offset-m3 l4 offset-l4">
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
                </div>
            </div>
        </div>
    </body>
</html>