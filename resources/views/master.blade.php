<!DOCTYPE html>
<html style="height: calc(100% - 75px);">
    <head>
        <title>Ciliatus</title>

        <link rel="manifest" href="manifest.json">
        <link rel="icon" href="/images/manifest/launcher-icon-0-75x.png" type="image/png">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,900" rel="stylesheet">
        @if(Auth::user()->setting('permanent_nightmode_enabled') == 'on' || (Auth::user()->setting('auto_nightmode_enabled') == 'on' && Auth::user()->night()))
            <link type="text/css" rel="stylesheet" href="/css/vendors/materialize_dark.min.css"  media="screen,projection"/>
        @else
            <link type="text/css" rel="stylesheet" href="/css/vendors/materialize.min.css"  media="screen,projection"/>
        @endif
        <link type="text/css" rel="stylesheet" href="/css/vendors/timeline.min.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/css/vendors/materialize.clockpicker.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/css/vendors/dygraph.min.css"  media="screen,projection"/>

        <script type="text/javascript" src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
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

        <div id="body">
            <div id="api-io-widget-result-modal" class="modal" style="z-index: 1003;">
                <div class="modal-content">
                    <h4>Ciliatus</h4>
                    <strong>@lang('tooltips.experimental_feature')</strong>
                    <p id="api-io-widget-result-modal-content"></p>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-action modal-close btn btn-flat orange darken-4">@lang('buttons.close')</a>
                </div>
            </div>
            <header>
                <div class="navbar-fixed">
                    <nav>
                        <div class="nav-wrapper">
                            <ul class="left">
                                <li>
                                    <a href="#" data-activates="nav-mobile" class="button-collapse top-nav full hide-on-large-only"
                                        style="padding-left: 15px; padding-right: 0; margin: 0;">
                                        <i class="material-icons">menu</i>
                                    </a>
                                </li>
                            </ul>

                            <span class="hide-on-med-and-down" style="padding-left: 15px;">
                                <a href="/" class="breadcrumb hide-on-small-and-down">Start</a>
                                @yield('breadcrumbs')
                            </span>

                            <span class="right overlay-loader hide-on-med-and-up">
                                <img src="https://dev-43256.ciliatus.io/svg/Ciliatus_Logo.svg"
                                     class="main-loader loader-icon" id="loader-icon"
                                     style="height: 44px; width: 44px; position: relative; top: 6px; right: 10px;">
                            </span>

                            <ul class="right" style="position:relative; right: 0px;">
                                <li>
                                    @if(App\System::hasVoiceCapability())
                                    <span>API.AI is not available in your language yet.</span>
                                    @else
                                    <api-io-widget></api-io-widget>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>

                <ul id="nav-mobile" class="side-nav fixed">
                    <li>
                        <div class="userView primary-background-color" id="left-top-menu-logo-wrapper">
                            <div class="overlay-loader center">
                                <img src="https://dev-43256.ciliatus.io/svg/Ciliatus_Logo.svg"
                                     class="main-loader loader-icon" id="loader-icon"
                                     style="width: 100px; width: 100px;">
                            </div>
                            <div class="center">
                                <span class="white-text brand-title">Ciliatus</span>
                            </div>
                        </div>
                        <div class="no-padding" id="system-indicator">
                            <system-indicator></system-indicator>
                        </div>
                    </li>

                    <li @if(Request::is('/')) class="active" @endif><a href="{{ url('/') }}" class="waves-effect waves-orange"><i class="material-icons">dashboard</i>@choice('menu.dashboard', 1)</a></li>

                    <li><div class="divider"></div></li>

                    <li @if(Request::is('animals', 'animals/*')) class="active" @endif><a href="{{ url('animals') }}" class="waves-effect waves-orange"><i class="material-icons">pets</i>@choice('components.animals', 2)</a></li>
                    <li @if(Request::is('terraria', 'terraria/*')) class="active" @endif><a href="{{ url('terraria') }}" class="waves-effect waves-orange"><i class="material-icons">video_label</i>@choice('components.terraria', 2)</a></li>
                    <li @if(Request::is('controlunits', 'controlunits/*')) class="active" @endif><a href="{{ url('controlunits') }}" class="waves-effect waves-orange"><i class="material-icons">memory</i>@choice('components.controlunits', 2)</a></li>

                    <li class="no-padding">
                        <ul class="collapsible collapsible-accordion">
                            <li>
                                <a class="collapsible-header">
                                    @lang('menu.monitoring')
                                    <i class="material-icons">alarm_on</i>
                                    <i class="material-icons right" style="margin-right: 10px;">keyboard_arrow_down</i>
                                </a>
                                <div class="collapsible-body">
                                    <ul>
                                        <!--
                                        <li @if(Request::is('map', 'map/*')) class="active" @endif>
                                            <a href="{{ url('map') }}" class="waves-effect waves-orange">
                                                <i class="material-icons">map</i>
                                                @lang('labels.relation_map')
                                            </a>
                                        </li>
                                        -->

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
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li class="no-padding">
                        <ul class="collapsible collapsible-accordion">
                            <li>
                                <a class="collapsible-header">
                                    @lang('menu.automation')
                                    <i class="material-icons">autorenew</i>
                                    <i class="material-icons right" style="margin-right: 10px;">keyboard_arrow_down</i>
                                </a>
                                <div class="collapsible-body">
                                    <ul>
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
                                        @foreach(\App\GenericComponentType::get() as $gct)
                                            <li @if(Request::is('generic_component_types/' . $gct->id, 'generic_component_types/' . $gct->id . '/*')) class="active" @endif>
                                                <a href="{{ url('generic_component_types/' . $gct->id) }}" class="waves-effect waves-orange">
                                                    <i class="material-icons">{{ $gct->icon }}</i>
                                                    {{ $gct->name_plural }}
                                                </a>
                                            </li>
                                        @endforeach
                                        <li @if(Request::is('action_sequences', 'action_sequences/*')) class="active" @endif>
                                            <a href="{{ url('action_sequences') }}" class="waves-effect waves-orange">
                                                <i class="material-icons">playlist_play</i>
                                                @choice('components.action_sequences', 2)
                                            </a>
                                        </li>
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
                                <a class="collapsible-header">
                                    @lang('menu.administration')
                                    <i class="material-icons">build</i>
                                    <i class="material-icons right" style="margin-right: 10px;">keyboard_arrow_down</i>
                                </a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li @if(Request::is('users', 'users/*')) class="active" @endif>
                                            <a href="{{ url('users') }}" class="waves-effect waves-orange">
                                                <i class="material-icons">group</i>
                                                @lang('menu.users')
                                            </a>
                                        </li>
                                        <li @if(Request::is('categories')) class="active" @endif>
                                            <a href="{{ url('categories') }}" class="waves-effect waves-orange">
                                                <i class="material-icons">layers</i>
                                                @lang('menu.categories')
                                            </a>
                                        </li>
                                        <li @if(Request::is('logs', 'logs/*')) class="active" @endif>
                                            <a href="{{ url('logs') }}" class="waves-effect waves-orange">
                                                <i class="material-icons">history</i>
                                                @lang('menu.ciliatus_logs')
                                            </a>
                                        </li>
                                        <li @if(Request::is('system_logs', 'system_logs/*')) class="active" @endif>
                                            <a href="{{ url('system_logs') }}" class="waves-effect waves-orange">
                                                <i class="material-icons">history</i>
                                                @lang('menu.system_logs')
                                            </a>
                                        </li>
                                        <li @if(Request::is('system', 'system/*')) class="active" @endif>
                                            <a href="{{ url('system/status') }}" class="waves-effect waves-orange">
                                                <i class="material-icons">public</i>
                                                @lang('menu.system_status')
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://github.com/matthenning/ciliatus/issues" class="waves-effect waves-orange">
                                                <i class="material-icons">bug_report</i>
                                                @lang('labels.bugtracker')</a>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li><div class="divider"></div></li>

                    <li><a href="https://ciliatus.io/docs/{{ config('app.version') }}" class="waves-effect waves-orange"><i class="material-icons">help</i>@lang('labels.doku')</a></li>
                    @endif

                    <li><a @if(!App\ActionSequence::stopped())href="/action_sequences/stop_all" @else href="/action_sequences/resume_all" @endif class="waves-effect waves-red red-text"><i class="material-icons red-text">power_settings_new</i>@lang('buttons.emergency_stop')</a></li>

                    <li><div class="divider"></div></li>

                    <li><a href="{{ url('auth/logout') }}" class="waves-effect waves-orange"><i class="material-icons">exit_to_app</i>@lang('labels.logout')</a></li>
                </ul>

            </header>

            <main style="height: 100%;">

                <div id="content" style="height: 100%;">
                    @yield('content')
                </div>
            </main>

        </div>

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
        <!-- Dygraph -->
        <script src="{{ url('/js/vendors/dygraph.min.js') }}"></script>

        @yield('scripts')

        <script>

            $(document).ready(function() {
                window.runPage();
            });
        </script>

    </body>
</html>