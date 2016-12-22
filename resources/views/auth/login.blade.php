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
        <div class="container" style="padding-top: 100px">
            <div class="row">
                <div class="col s12 m6 offset-m3 l4 offset-l4">
                    <div class="card">
                        <form method="POST" action="{{ url('auth/login') }}">
                            {!! csrf_field() !!}
                            <div class="card-content">
                                <span class="card-title">Ciliatus</span>

                                <div>
                                    <input type="text" class="form-control" placeholder="Username" required="" name="email" value="{{ old('email') }}" />
                                </div>
                                <div>
                                    <input type="password" class="form-control" placeholder="Password" required="" name="password" id="password" />
                                </div>
                                <div>
                                    <button class="btn btn-default submit" type="submit">Log in</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>