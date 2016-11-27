<!DOCTYPE html>
<html>
    <head>
        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="/css/materialize.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/css/materialize.clockpicker.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/css/app.css"  media="screen,projection"/>
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <meta name="theme-color" content="#009688" />

        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body>

        <header>

            <nav>
                <div class="nav-wrapper teal">
                    <ul class="left">
                        <li><a href="#" data-activates="nav-mobile" class="button-collapse top-nav full hide-on-large-only"><i class="material-icons">menu</i></a></li>
                    </ul>

                    <a href="/" class="breadcrumb">Start</a>
                    @yield('breadcrumbs')

                    <a href="/" class="brand-logo"></a>
                    <ul class="right hide-on-med-and-down">
                        <li><a href="sass.html"><i class="material-icons left">search</i>Link with Left Icon</a></li>
                        <li><a href="badges.html"><i class="material-icons right">view_module</i>Link with Right Icon</a></li>
                    </ul>
                </div>
            </nav>

            <ul id="nav-mobile" class="side-nav fixed">
                <li>
                    <div class="userView" style="height: 250px;">
                        <div class="background" style="height: 250px; background-image: url('/images/logo_demo.jpg')">
                            <div class="background" style="background-color: rgba(0, 0, 0, 0.3); margin-top: 150px; height: 100px; width: 300px">
                                <a href="#!name"><span class="white-text name">{{ Auth::user()->name }}</span></a>
                                <a href="#!email"><span class="white-text email">{{ Auth::user()->email }}</span></a>
                            </div>
                        </div>
                    </div>
                </li>

                <li @if(Request::is('/')) class="active" @endif><a href="{{ url('/') }}" class="waves-effect waves-teal"><i class="material-icons">dashboard</i>@choice('menu.dashboard', 1)</a></li>

                <li><div class="divider"></div></li>

                <li @if(Request::is('animals', 'animals/*')) class="active" @endif><a href="{{ url('animals') }}" class="waves-effect waves-teal"><i class="material-icons">pets</i>@choice('components.animals', 2)</a></li>
                <li @if(Request::is('terraria', 'terraria/*')) class="active" @endif><a href="{{ url('terraria') }}" class="waves-effect waves-teal"><i class="material-icons">video_label</i>@choice('components.terraria', 2)</a></li>

                <li class="no-padding">
                    <ul class="collapsible collapsible-accordion">
                        <li>
                            <a class="collapsible-header">@lang('menu.infrastructure')<i class="material-icons">device_hub</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    <li @if(Request::is('controlunits', 'controlunits/*')) class="active" @endif>
                                        <a href="{{ url('controlunits') }}" class="waves-effect waves-teal">
                                            <i class="material-icons">developer_board</i>
                                            @choice('components.controlunits', 2)
                                        </a>
                                    </li>
                                    <li @if(Request::is('pumps', 'pumps/*')) class="active" @endif>
                                        <a href="{{ url('pumps') }}" class="waves-effect waves-teal">
                                            <i class="material-icons">rotate_right</i>
                                            @choice('components.pumps', 2)
                                        </a>
                                    </li>
                                    <li @if(Request::is('valves', 'valves/*')) class="active" @endif>
                                        <a href="{{ url('valves') }}" class="waves-effect waves-teal">
                                            <i class="material-icons">transform</i>
                                            @choice('components.valves', 2)
                                        </a>
                                    </li>
                                    <li @if(Request::is('physical_sensors', 'physical_sensors/*')) class="active" @endif>
                                        <a href="{{ url('physical_sensors') }}" class="waves-effect waves-teal">
                                            <i class="material-icons">memory</i>
                                            @choice('components.physical_sensors', 2)
                                        </a>
                                    </li>
                                    <li @if(Request::is('logical_sensors', 'logical_sensors/*')) class="active" @endif>
                                        <a href="{{ url('logical_sensors') }}" class="waves-effect waves-teal">
                                            <i class="material-icons">memory</i>
                                            @choice('components.logical_sensors', 2)
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </li>


                <li><div class="divider"></div></li>

                <li @if(Request::is('users/' . Auth::user()->id . '/edit')) class="active" @endif><a href="{{ url('users/' . Auth::user()->id . '/edit') }}" class="waves-effect waves-teal"><i class="material-icons">settings</i>@choice('labels.settings', 2)</a></li>
                <li><a href="https://github.com/matthenning/ciliatus/issues" class="waves-effect waves-teal"><i class="material-icons">bug_report</i>@lang('labels.bugtracker')</a></li>
                <li><a href="https://github.com/matthenning/ciliatus/wiki" class="waves-effect waves-teal"><i class="material-icons">help</i>@lang('labels.doku')</a></li>
                <li><a href="https://github.com/matthenning/ciliatus/releases/tag/v0.3-alpha" class="waves-effect waves-teal"><i class="material-icons">linear_scale</i>Version v0.3-alpha</a></li>

            </ul>

            <div style="width: 100%; margin: 0; height: 10px;">
                <div class="progress" id="global-loading-bar" style="display: none; width: 100%; margin: 0">
                    <div class="indeterminate"></div>
                </div>
            </div>

        </header>

        <main>
            <div class="container">
                <div class="row" id="content">
                    @yield('content')
                </div>
            </div>
        </main>

        <!-- Laravel-Echo -->
        <script src="{{ url('js/echo.js') }}"></script>
        <!-- Vue -->
        <script src="{{ url('js/vue.js') }}"></script>
        <!-- Materialize.css -->
        <script src="{{ url('js/materialize.min.js') }}"></script>
        <!-- Materialize.clockpicker.css -->
        <script src="{{ url('js/materialize.clockpicker.js') }}"></script>
        <!-- ciliatus -->
        <script src="{{ url('js/app.js') }}"></script>


        <script>
            $(document).ready(function() {
                $('select').material_select();

                $(".button-collapse").sideNav();

                var active_headers = $('.collapsible-body ul li.active').parent().parent().parent();
                active_headers.addClass('active');
                active_headers.children('.collapsible-body').css('display', 'block');

                $('form').submit(window.submit_form);
            });
        </script>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/dygraph/1.1.1/dygraph-combined.js"></script>

    </body>
</html>