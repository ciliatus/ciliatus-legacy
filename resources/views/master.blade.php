<!DOCTYPE html>
<html style="height: calc(100% - 75px);">
    <head>
        <title>Ciliatus</title>

        <link rel="manifest" href="manifest.json">
        <link rel="icon" href="/images/manifest/launcher-icon-0-75x.png" type="image/png">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        @if(Auth::user()->setting('permanent_nightmode_enabled') == 'on' || (Auth::user()->setting('auto_nightmode_enabled') == 'on' && Auth::user()->night()))
            <link type="text/css" rel="stylesheet" href="/css/vendors/materialize_dark.min.css"  media="screen,projection"/>
        @else
            <link type="text/css" rel="stylesheet" href="/css/vendors/materialize.min.css"  media="screen,projection"/>
        @endif
        <link type="text/css" rel="stylesheet" href="/css/vendors/timeline.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/css/vendors/materialize.clockpicker.css"  media="screen,projection"/>

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

    <body data-lang="{{ Auth::user()->locale }}" style="height: 100%;">

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

                        <a href="/" class="breadcrumb hide-on-small-and-down">Start</a>
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
                    <div class="userView orange darken-4" id="left-top-menu-logo-wrapper">
                        <div class="background" id="left-top-menu-logo"></div>
                    </div>
                    <div class="no-padding" id="system-indicator">
                        <system-indicator></system-indicator>
                    </div>
                </li>

                <li @if(Request::is('/')) class="active" @endif><a href="{{ url('/') }}" class="waves-effect waves-orange"><i class="material-icons">dashboard</i>@choice('menu.dashboard', 1)</a></li>

                <li><div class="divider"></div></li>

                <li @if(Request::is('animals', 'animals/*')) class="active" @endif><a href="{{ url('animals') }}" class="waves-effect waves-orange"><i class="material-icons">pets</i>@choice('components.animals', 2)</a></li>
                <li @if(Request::is('terraria', 'terraria/*')) class="active" @endif><a href="{{ url('terraria') }}" class="waves-effect waves-orange"><i class="material-icons">video_label</i>@choice('components.terraria', 2)</a></li>

                <li class="no-padding">
                    <ul class="collapsible collapsible-accordion">
                        <li>
                            <a class="collapsible-header">@lang('menu.infrastructure')<i class="material-icons">device_hub</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    <li @if(Request::is('map', 'map/*')) class="active" @endif>
                                        <a href="{{ url('map') }}" class="waves-effect waves-orange">
                                            <i class="material-icons">map</i>
                                            @lang('labels.relation_map')
                                        </a>
                                    </li>
                                    <li @if(Request::is('controlunits', 'controlunits/*')) class="active" @endif>
                                        <a href="{{ url('controlunits') }}" class="waves-effect waves-orange">
                                            <i class="material-icons">developer_board</i>
                                            @choice('components.controlunits', 2)
                                        </a>
                                    </li>
                                    <li @if(Request::is('pumps', 'pumps/*')) class="active" @endif>
                                        <a href="{{ url('pumps') }}" class="waves-effect waves-orange">
                                            <i class="material-icons">rotate_right</i>
                                            @choice('components.pumps', 2)
                                        </a>
                                    </li>
                                    <li @if(Request::is('valves', 'valves/*')) class="active" @endif>
                                        <a href="{{ url('valves') }}" class="waves-effect waves-orange">
                                            <i class="material-icons">transform</i>
                                            @choice('components.valves', 2)
                                        </a>
                                    </li>
                                    <li @if(Request::is('physical_sensors', 'physical_sensors/*')) class="active" @endif>
                                        <a href="{{ url('physical_sensors') }}" class="waves-effect waves-orange">
                                            <i class="material-icons">memory</i>
                                            @choice('components.physical_sensors', 2)
                                        </a>
                                    </li>
                                    <li @if(Request::is('logical_sensors', 'logical_sensors/*')) class="active" @endif>
                                        <a href="{{ url('logical_sensors') }}" class="waves-effect waves-orange">
                                            <i class="material-icons">memory</i>
                                            @choice('components.logical_sensors', 2)
                                        </a>
                                    </li>
                                    @foreach(\App\GenericComponentType::get() as $gct)
                                    <li @if(Request::is('generic_component_types/' . $gct->id, 'generic_component_types/' . $gct->id . '/*')) class="active" @endif>
                                        <a href="{{ url('generic_component_types/' . $gct->id) }}" class="waves-effect waves-orange">
                                            <i class="material-icons">{{ $gct->icon }}</i>
                                            {{ $gct->name_plural }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    </ul>
                </li>

                <li><div class="divider"></div></li>

                <li @if(Request::is('users/' . Auth::user()->id . '/edit')) class="active" @endif><a href="{{ url('users/' . Auth::user()->id . '/edit') }}" class="waves-effect waves-orange"><i class="material-icons">settings</i>@choice('labels.settings', 2)</a></li>

                @if(Gate::allows('admin'))
                <li class="no-padding">
                    <ul class="collapsible collapsible-accordion">
                        <li>
                            <a class="collapsible-header">@lang('menu.administration')<i class="material-icons">build</i></a>
                            <div class="collapsible-body">
                                <ul>
                                    <li @if(Request::is('users', 'users/*')) class="active" @endif>
                                        <a href="{{ url('users') }}" class="waves-effect waves-orange">
                                            <i class="material-icons">group</i>
                                            @lang('menu.users')
                                        </a>
                                    </li>
                                    <li @if(Request::is('logs', 'logs/*')) class="active" @endif>
                                        <a href="{{ url('logs') }}" class="waves-effect waves-orange">
                                            <i class="material-icons">history</i>
                                            @lang('menu.logs')
                                        </a>
                                    </li>
                                    <li @if(Request::is('categories')) class="active" @endif>
                                        <a href="{{ url('categories') }}" class="waves-effect waves-orange">
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

                <li><a href="https://github.com/matthenning/ciliatus/issues" class="waves-effect waves-orange"><i class="material-icons">bug_report</i>@lang('labels.bugtracker')</a></li>
                <li><a href="https://ciliatus.io/docs/v1.5-beta" class="waves-effect waves-orange"><i class="material-icons">help</i>@lang('labels.doku')</a></li>
                <li><a href="https://github.com/matthenning/ciliatus/releases/tag/v1.5-beta" class="waves-effect waves-orange"><i class="material-icons">linear_scale</i>Version v1.6-beta dev</a></li>
                @endif

                <li class="red lighten-5"><a @if(!App\ActionSequence::stopped())href="/action_sequences/stop_all" @else href="/action_sequences/resume_all" @endif class="waves-effect waves-red red-text"><i class="material-icons red-text">power_settings_new</i>@lang('buttons.emergency_stop')</a></li>

                <li><div class="divider"></div></li>

                <li><a href="#" onclick="$.post('/auth/logout'); setTimeout(function () { window.location.replace('/') }, 200);" class="waves-effect waves-orange"><i class="material-icons">exit_to_app</i>@lang('labels.logout')</a></li>
            </ul>

            <div style="width: 100%; margin: 0; height: 10px; position: relative; z-index: 1001">
                <div class="progress" id="global-loading-bar" style="display: none; width: 100%; margin: 0">
                    <div class="indeterminate"></div>
                </div>
            </div>

        </header>

        <main style="height: 100%;">
            <div id="content" style="height: 100%; position: relative; top: -10px;">
                @yield('content')
            </div>
        </main>

        <!-- Google Charts -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <!-- Materialize.js -->
        <script src="{{ url('/js/vendors/materialize.min.js') }}"></script>
        <!-- Materialize.clockpicker.js -->
        <script src="{{ url('/js/vendors/materialize.clockpicker.js') }}"></script>
        <!-- Masonry -->
        <script src="{{ url('/js/vendors/masonry.pkgd.min.js') }}"></script>
        <!-- Laravel-Echo -->
        <script src="{{ url('/js/vendors/echo.min.js') }}"></script>
        <!-- ciliatus -->
        <script src="{{ url('/js/app.min.js') }}"></script>
        <!-- Vue -->
        <script src="{{ url('/js/vendors/vue.min.js') }}"></script>

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