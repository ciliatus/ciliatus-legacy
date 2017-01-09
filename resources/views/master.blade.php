<!DOCTYPE html>
<html>
    <head>
        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--Import materialize.css-->
        @if(Auth::user()->setting('permanent_nightmode_enabled') == 'on' || (Auth::user()->setting('auto_nightmode_enabled') == 'on' && Auth::user()->night()))
            <link type="text/css" rel="stylesheet" href="/css/materialize_dark.css"  media="screen,projection"/>
        @else
            <link type="text/css" rel="stylesheet" href="/css/materialize.css"  media="screen,projection"/>
        @endif
        <link type="text/css" rel="stylesheet" href="/css/timeline.css"  media="screen,projection"/>


        <link type="text/css" rel="stylesheet" href="/css/materialize.clockpicker.css"  media="screen,projection"/>
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <meta name="theme-color" content="#009688" />

        <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body data-lang="{{ Auth::user()->locale }}">

        <script>
            var domCallbacks = [];
        </script>

        <header>

            <div class="navbar-fixed">
                <nav>
                    <div class="nav-wrapper">
                        <ul class="left">
                            <li><a href="#" data-activates="nav-mobile" class="button-collapse top-nav full hide-on-large-only"><i class="material-icons">menu</i></a></li>
                        </ul>

                        <a href="/" class="breadcrumb">Start</a>
                        @yield('breadcrumbs')

                        <a href="/" class="brand-logo"></a>
                        <ul class="right hide-on-med-and-down">
                            <!--
                            <li><a href="sass.html"><i class="material-icons left">search</i>Link with Left Icon</a></li>
                            <li><a href="badges.html"><i class="material-icons right">view_module</i>Link with Right Icon</a></li>
                            -->
                        </ul>
                    </div>
                </nav>
            </div>

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

                @if(Gate::allows('admin'))
                <li class="no-padding">
                    <ul class="collapsible collapsible-accordion">
                        <li>
                            <a class="collapsible-header">@lang('menu.administration')<i class="material-icons">build</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    <li @if(Request::is('users', 'users/*')) class="active" @endif>
                                        <a href="{{ url('users') }}" class="waves-effect waves-teal">
                                            <i class="material-icons">group</i>
                                            @lang('menu.users')
                                        </a>
                                    </li>
                                    <li @if(Request::is('logs', 'logs/*')) class="active" @endif>
                                        <a href="{{ url('logs') }}" class="waves-effect waves-teal">
                                            <i class="material-icons">history</i>
                                            @lang('menu.logs')
                                        </a>
                                    </li>
                                    <li @if(Request::is('categories')) class="active" @endif>
                                        <a href="{{ url('categories') }}" class="waves-effect waves-teal">
                                            <i class="material-icons">layers</i>
                                            @lang('menu.categories')
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </li>

                <li><div class="divider"></div></li>

                <li><a href="https://github.com/matthenning/ciliatus/issues" class="waves-effect waves-teal"><i class="material-icons">bug_report</i>@lang('labels.bugtracker')</a></li>
                <li><a href="https://github.com/matthenning/ciliatus/wiki" class="waves-effect waves-teal"><i class="material-icons">help</i>@lang('labels.doku')</a></li>
                <li><a href="https://github.com/matthenning/ciliatus/releases/tag/v1.0-beta" class="waves-effect waves-teal"><i class="material-icons">linear_scale</i>Version v1.0-beta</a></li>
                @endif

            </ul>

            <div style="width: 100%; margin: 0; height: 10px; position: relative; z-index: 1001">
                <div class="progress" id="global-loading-bar" style="display: none; width: 100%; margin: 0">
                    <div class="indeterminate"></div>
                </div>
            </div>

        </header>

        <main>
            <div id="content" style="position: relative; top: -10px;">
                @yield('content')
            </div>
        </main>

        <!-- Google Charts -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <!-- Materialize.css -->
        <script src="{{ url('js/materialize.min.js') }}"></script>
        <!-- Materialize.clockpicker.css -->
        <script src="{{ url('js/materialize.clockpicker.js') }}"></script>
        <!-- Masonry -->
        <script src="{{ url('js/masonry.pkgd.min.js') }}"></script>
        <!-- Laravel-Echo -->
        <script src="{{ url('js/echo.js') }}"></script>
        <!-- Vue -->
        <script src="{{ url('js/vue.js') }}"></script>
        <!-- ciliatus -->
        <script src="{{ url('js/app.js') }}"></script>

        @yield('scripts')

        <script>
            $(document).ready(function() {
                window.runPage();
                $.ajaxSetup({
                    headers:
                        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });
            });
        </script>

        <script src="//cdnjs.cloudflare.com/ajax/libs/dygraph/1.1.1/dygraph-combined.js"></script>

    </body>
</html>