<!DOCTYPE html>
<html style="height: calc(100% - 75px);">
    <head>
        <title>Ciliatus</title>

        <link rel="manifest" href="manifest.json">
        <link rel="icon" href="/images/manifest/launcher-icon-0-75x.png" type="image/png">

        <link rel="stylesheet" href="/css/vendors/materialdesignicons-2.1.99.min.css?v=201804291751">
        <link rel="stylesheet" href="/css/vendors/montserrat.css?v=201804291751">
        @if(Auth::user()->setting('permanent_nightmode_enabled') == 'on' || (Auth::user()->setting('auto_nightmode_enabled') == 'on' && Auth::user()->night()))
            <link type="text/css" rel="stylesheet" href="/css/vendors/materialize_dark.min.css?v=201804291751"  media="screen,projection"/>
        @else
            <link type="text/css" rel="stylesheet" href="/css/vendors/materialize.min.css?v=201804291751"  media="screen,projection"/>
        @endif

        <link type="text/css" rel="stylesheet" href="/css/vendors/timeline.css?v=201804291751"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/css/vendors/dygraph-2.1.0.min.css?v=201804291751"  media="screen,projection"/>

        <script type="text/javascript" src="{{ url('/js/vendors/jquery-3.3.1.min.js?v=201804291751') }}"></script>
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

    <body data-lang="{{ app()->getLocale() }}"
          data-base-url="{{ config()->get('app.url') }}"
          data-pusher-app-key="{{ env('PUSHER_APP_KEY') }}"
          style="height: 100%;">

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
                    <a href="#!" class="modal-action modal-close btn btn-flat orange darken-4 white-text">@lang('buttons.close')</a>
                </div>
            </div>

            <nav>
                <div class="nav-wrapper">
                    <ul class="left">
                        <li>
                            <a href="#!" data-target="slide-out" class="sidenav-trigger button-collapse top-nav full hide-on-large-only"
                               style="padding-left: 15px; padding-right: 0; margin: 0;">
                                <i class="mdi mdi-24px mdi-menu white-text"></i>
                            </a>
                        </li>
                    </ul>

                    <span class="hide-on-med-and-down" style="padding-left: 15px;">
                        <a href="/" class="breadcrumb hide-on-small-and-down">Start</a>
                        @yield('breadcrumbs')
                    </span>

                    <!--
                    <span class="right overlay-loader hide-on-med-and-up">
                        <img src="https://dev-43256.ciliatus.io/svg/Ciliatus_Logo.svg"
                             class="main-loader loader-icon" id="loader-icon"
                             style="height: 44px; width: 44px; position: relative; top: 6px; right: 10px;">
                    </span>
                    -->

                    <!--
                    <ul class="right" style="position:relative; right: 0px;">
                        <li>
                            @if(App\System::hasVoiceCapability())
                                <span>Your language is not installed.</span>
                            @else
                                <api-io-widget></api-io-widget>
                            @endif
                        </li>
                    </ul>
                    -->
                </div>
            </nav>
                <ul class="sidenav sidenav-fixed" id="slide-out">
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

                    <li class="hide-on-small-and-down">
                        <div class="input-field ciliatus-search-wrapper">
                            <input type="text" id="search-ciliatus" class="no-margin">
                            <label for="search-ciliatus">
                                <i class="mdi mdi-24px mdi-magnify"></i>
                                @lang('labels.search_ciliatus')
                            </label>
                        </div>
                    </li>

                    <li @if(Request::is('/')) class="active" @endif>
                        <a href="{{ url('/') }}" class="waves-effect waves-orange">
                            <i class="mdi mdi-24px mdi-view-dashboard"></i>
                            @choice('menu.dashboard', 1)
                        </a>
                    </li>

                    <li><div class="divider"></div></li>

                    <li @if(Request::is('animals', 'animals/*')) class="active" @endif>
                        <a href="{{ url('animals') }}" class="waves-effect waves-orange">
                            <i class="mdi mdi-24px mdi-paw"></i>
                            @choice('labels.animals', 2)
                        </a>
                    </li>

                    <li @if(Request::is('terraria', 'terraria/*')) class="active" @endif>
                        <a href="{{ url('terraria') }}" class="waves-effect waves-orange">
                            <i class="mdi mdi-24px mdi-trackpad"></i>
                            @choice('labels.terraria', 2)
                        </a>
                    </li>

                    <li @if(Request::is('controlunits', 'controlunits/*')) class="active" @endif>
                        <a href="{{ url('controlunits') }}" class="waves-effect waves-orange">
                            <i class="mdi mdi-24px mdi-developer-board"></i>
                            @choice('labels.controlunits', 2)
                        </a>
                    </li>

                    <li class="no-padding">
                        <ul class="collapsible collapsible-accordion">
                            <li>
                                <a class="collapsible-header">
                                    @lang('menu.monitoring')
                                    <i class="mdi mdi-24px mdi-speedometer"></i>
                                    <i class="mdi mdi-24px mdi-chevron-down right"></i>
                                </a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li @if(Request::is('physical_sensors', 'physical_sensors/*')) class="active" @endif>
                                            <a href="{{ url('physical_sensors') }}" class="waves-effect waves-orange">
                                                <i class="mdi mdi-24px mdi-switch"></i>
                                                @choice('labels.physical_sensors', 2)
                                            </a>
                                        </li>
                                        <li @if(Request::is('logical_sensors', 'logical_sensors/*')) class="active" @endif>
                                            <a href="{{ url('logical_sensors') }}" class="waves-effect waves-orange">
                                                <i class="mdi mdi-24px mdi-pulse"></i>
                                                @choice('labels.logical_sensors', 2)
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
                                    <i class="mdi mdi-24px mdi-chart-bubble"></i>
                                    <i class="mdi mdi-24px mdi-chevron-down right"></i>
                                </a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li @if(Request::is('pumps', 'pumps/*')) class="active" @endif>
                                            <a href="{{ url('pumps') }}" class="waves-effect waves-orange">
                                                <i class="mdi mdi-24px mdi-water-pump"></i>
                                                @choice('labels.pumps', 2)
                                            </a>
                                        </li>
                                        <li @if(Request::is('valves', 'valves/*')) class="active" @endif>
                                            <a href="{{ url('valves') }}" class="waves-effect waves-orange">
                                                <i class="mdi mdi-24px mdi-pipe-disconnected"></i>
                                                @choice('labels.valves', 2)
                                            </a>
                                        </li>
                                        @foreach(\App\CustomComponentType::orderBy('name_plural')->get() as $gct)
                                            <li @if(Request::is('custom_component_types/' . $gct->id, 'custom_component_types/' . $gct->id . '/*')) class="active" @endif>
                                                <a href="{{ url('custom_component_types/' . $gct->id) }}" class="waves-effect waves-orange">
                                                    <i class="mdi mdi-24px mdi-{{ $gct->icon }}"></i>
                                                    {{ $gct->name_plural }}
                                                </a>
                                            </li>
                                        @endforeach
                                        <li @if(Request::is('action_sequences', 'action_sequences/*')) class="active" @endif>
                                            <a href="{{ url('action_sequences') }}" class="waves-effect waves-orange">
                                                <i class="mdi mdi-24px mdi-playlist-play"></i>
                                                @choice('labels.action_sequences', 2)
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li><div class="divider"></div></li>

                    <li @if(Request::is('users/' . Auth::user()->id . '/edit')) class="active" @endif>
                        <a href="{{ url('users/' . Auth::user()->id . '/edit') }}" class="waves-effect waves-orange">
                            <i class="mdi mdi-24px mdi-tune"></i>
                            @choice('labels.settings', 2)
                        </a>
                    </li>

                    @if(Gate::allows('admin'))
                    <li class="no-padding">
                        <ul class="collapsible collapsible-accordion">
                            <li>
                                <a class="collapsible-header">
                                    @lang('menu.administration')
                                    <i class="mdi mdi-24px mdi-settings"></i>
                                    <i class="mdi mdi-24px mdi-chevron-down right"></i>
                                </a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li @if(Request::is('users', 'users/*')) class="active" @endif>
                                            <a href="{{ url('users') }}" class="waves-effect waves-orange">
                                                <i class="mdi mdi-24px mdi-account-multiple"></i>
                                                @lang('menu.users')
                                            </a>
                                        </li>
                                        <li @if(Request::is('categories')) class="active" @endif>
                                            <a href="{{ url('categories') }}" class="waves-effect waves-orange">
                                                <i class="mdi mdi-24px mdi-layers"></i>
                                                @lang('menu.categories')
                                            </a>
                                        </li>
                                        <li @if(Request::is('logs', 'logs/*')) class="active" @endif>
                                            <a href="{{ url('logs') }}" class="waves-effect waves-orange">
                                                <i class="mdi mdi-24px mdi-history"></i>
                                                @lang('menu.ciliatus_logs')
                                            </a>
                                        </li>
                                        <li @if(Request::is('system_logs', 'system_logs/*')) class="active" @endif>
                                            <a href="{{ url('system_logs') }}" class="waves-effect waves-orange">
                                                <i class="mdi mdi-24px mdi-history"></i>
                                                @lang('menu.system_logs')
                                            </a>
                                        </li>
                                        <li @if(Request::is('system', 'system/*')) class="active" @endif>
                                            <a href="{{ url('system/status') }}" class="waves-effect waves-orange">
                                                <i class="mdi mdi-24px mdi-earth"></i>
                                                @lang('menu.system_status')
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://github.com/ciliatus/ciliatus/issues" class="waves-effect waves-orange">
                                                <i class="mdi mdi-24px mdi-bug"></i>
                                                @lang('labels.bugtracker')
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li><div class="divider"></div></li>

                    <li>
                        <a href="https://ciliatus.io/docs/{{ config('app.version_doc') }}" class="waves-effect waves-orange">
                            <i class="mdi mdi-24px mdi-book-open-variant"></i>
                            @lang('labels.users_guide')
                        </a>
                    </li>
                    @endif

                    <li>
                        @if(App\ActionSequence::stopped())
                            <a href="/action_sequences/resume_all" class="waves-effect waves-red green-text">
                                <i class="mdi mdi-24px mdi-power green-text"></i>
                                @lang('buttons.emergency_resume')
                            </a>
                        @else
                            <a href="/action_sequences/stop_all" class="waves-effect waves-red red-text">
                                <i class="mdi mdi-24px mdi-power red-text"></i>
                                @lang('buttons.emergency_stop')
                            </a>
                        @endif
                    </li>

                    <li><div class="divider"></div></li>

                    <li>
                        <a href="{{ url('auth/logout') }}" class="waves-effect waves-orange">
                            <i class="mdi mdi-24px mdi-logout"></i>
                            @lang('labels.logout')
                        </a>
                    </li>

                    <br /><br /><br />
                </ul>

            <main style="height: 100%;">

                <div id="content" style="height: 100%;">
                    @yield('content')
                </div>
            </main>

        </div>

        <!-- Google Charts -->
        <script type="text/javascript" src="{{ url('/js/vendors/google-charts.min.js?v=201804291751') }}"></script>
        <!-- Materialize.js -->
        <script type="text/javascript" src="{{ url('/js/vendors/materialize.min.js?v=201804291751') }}"></script>
        <!-- Laravel-Echo -->
        <script type="text/javascript" src="{{ url('/js/vendors/echo.min.js?v=201804291751') }}"></script>
        <!-- ciliatus -->
        <script type="text/javascript" src="{{ url('/js/app.min.js?v=201804291751') }}"></script>
        <!-- Vue -->
        <script type="text/javascript" src="{{ url('/js/vendors/vue.min.js?v=201804291751') }}"></script>
        <!-- Dygraph -->
        <script type="text/javascript" src="{{ url('/js/vendors/dygraph.min.js?v=201804291751') }}"></script>

        @yield('scripts')

        <script>
            $(document).ready(function() {
                window.runPage();

                var search_ciliatus_data = {
                    @foreach(App\System::getCachedAnimalsAndTerraria() as $obj)
                        "{{ $obj->display_name }}": "{{ $obj->url }}",
                    @endforeach
                };

                var search_ciliatus_dom = $('#search-ciliatus');

                search_ciliatus_dom.autocomplete({
                    data: {
                        @foreach(App\System::getCachedAnimalsAndTerraria() as $obj)
                            "{{ $obj->display_name }}": null,
                        @endforeach
                    },
                    onAutocomplete: function(item) {
                        window.location.replace(search_ciliatus_data[item]);
                    },
                    limit: 20, // The max amount of results that can be shown at once. Default: Infinity.
                });

                search_ciliatus_dom.focus();
                search_ciliatus_dom.select();
            });
        </script>

    </body>
</html>