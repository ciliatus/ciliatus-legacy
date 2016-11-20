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

        @include('graphs_vue')

        <ul id="slide-out" class="side-nav">
            <li>
                <div class="userView">
                    <div class="background">
                        <img src="images/office.jpg">
                    </div>
                    <a href="#!user"><img class="circle" src="images/yuna.jpg"></a>
                    <a href="#!name"><span class="white-text name">John Doe</span></a>
                    <a href="#!email"><span class="white-text email">jdandturk@gmail.com</span></a>
                </div>
            </li>

            <li><a href="/terraria">@lang('menu.terraria')</a></li>
            <li><a href="/animals">@lang('menu.animals')</a></li>

            <li>
                <ul class="collapsible collapsible-accordion">
                    <li class="bold">
                        <a class="collapsible-header waves-effect waves-teal">@lang('menu.components') <i class="collapsible-header-icon material-icons">keyboard_arrow_down</i></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="{{ url('controlunits') }}">@choice('components.controlunits', 2)</a></li>
                                <li><a href="{{ url('pumps') }}">@choice('components.pumps', 2)</a></li>
                                <li><a href="{{ url('valves') }}">@choice('components.valves', 2)</a></li>
                                <li><a href="{{ url('physical_sensors') }}">@choice('components.physical_sensors', 2)</a></li>
                                <li><a href="{{ url('logical_sensors') }}">@choice('components.logical_sensors', 2)</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>

        <div class="row" style="margin: 0">
            <nav>
                <div class="nav-wrapper">
                    <ul class="left">
                        <li><a href="#" data-activates="slide-out" class="button-side-nav-collapse"><i class="material-icons">menu</i></a></li>
                    </ul>

                    <ul id="nav-mobile" class="right hide-on-med-and-down">
                        <li><a href="/terraria">@lang('menu.terraria')</a></li>
                        <li><a href="/animals">@lang('menu.animals')</a></li>
                        <li><a href="/auth/logout">@lang('menu.logout')</a></li>
                    </ul>
                </div>
            </nav>
        </div>

        <div class="progress" id="global-loading-bar" style="width: 100%; margin: 0">
            <div class="indeterminate"></div>
        </div>

        <div class="row">

        </div>


        @include('animals.vue.show')
        @include('terraria.vue.show')
        @include('files.vue.show')

        <div class="container" id="content">
            <div class="row">
                @yield('content')
            </div>
        </div>

        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

        <!-- Laravel-Echo -->
        <script src="{{ url('js/echo.js') }}"></script>
        <!-- Vue -->
        <script src="{{ url('js/vue.js') }}"></script>

        <!-- Materialize.css -->
        <script src="{{ url('js/materialize.min.js') }}"></script>


        <script>
            $(".button-side-nav-collapse").sideNav();
        </script>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/dygraph/1.1.1/dygraph-combined.js"></script>
    </body>
</html>