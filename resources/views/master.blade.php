<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ciliatus</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ url('vendors/bootstrap/dist/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('vendors/bootstrap/dist/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('css/bootstrap_xl.css') }} " />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('vendors/font-awesome/css/font-awesome.min.css') }}" />
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link rel="stylesheet" href="{{ url('vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" />
    <!-- Switchery -->
    <link rel="stylesheet" href="{{ url('vendors/switchery/dist/switchery.min.css') }}" />
    <!-- Custom Theme Style -->
    @if(Auth::user())
        @if(Auth::user()->setting('permanent_nightmode_enabled') == 'on')
            <link rel="stylesheet" href="{{ url('build/css/custom_dark.css') }}" />
        @elseif(Auth::user()->setting('auto_nightmode_enabled') == 'on' && Auth::user()->night())
            <link rel="stylesheet" href="{{ url('build/css/custom_dark.css') }}" />
        @else
            <link rel="stylesheet" href="{{ url('build/css/custom.css') }}" />
        @endif
    @else
        <link rel="stylesheet" href="{{ url('build/css/custom.css') }}" />
    @endif

    <!-- PNotify -->
    <link rel="stylesheet" href="{{ url('vendors/pnotify/dist/pnotify.css') }}" />
    <link rel="stylesheet" href="{{ url('vendors/pnotify/dist/pnotify.buttons.css') }}" />
    <link rel="stylesheet" href="{{ url('vendors/pnotify/dist/pnotify.nonblock.css') }}" />
    <!-- weather icons -->
    <link rel="stylesheet" href="{{ url('vendors/weather-icons/css/weather-icons.min.css') }}" />
<!-- Datatables -->
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" />
    <!-- jQuery -->
    <script src="{{ url('vendors/jquery/dist/jquery.min.js') }}"></script>
    <!-- jQuery UI -->
    <link rel="stylesheet" href="{{ url('vendors/jquery-ui/jquery-ui.min.css') }}" />
    <script src="{{ url('vendors/jquery-ui/jquery-ui.min.js') }}"></script>

</head>

<body class="nav-md">
<div class="container body">
  <div class="main_container">
      <div class="hidden-xl hidden-lg hidden-md floating-menu-button pull-right">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
      </div>
    <div class="col-md-3 left_col">
      <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
          <a href="/" class="site_title"><i class="fa fa-circle-thin"></i> <span>Ciliatus</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile">
          <div class="profile_pic">
            <img src="{{ url('images/user.png') }}" class="img-circle profile_img">
          </div>
          <div class="profile_info">
            <span>@lang('menu.welcome'),</span>
            <h2><a href="{{ url('users/' . \Auth::user()->id . '/edit') }}">{{ \Auth::user()->name }}</a></h2>
          </div>
        </div>

        <!-- /menu profile quick info -->

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu" style="margin-top: 100px;">
          <div class="menu_section">
            <h3>Dashboard</h3>
            <ul class="nav side-menu">
              <li><a href="/"><i class="material-icons">dashboard</i> <span>@choice('menu.dashboard', 1)</span></a></li>
              <li><a href="{{ url('users/' . \Auth::user()->id . '/edit') }}"><i class="material-icons">settings</i> <span>@choice('labels.settings', 2)</span></a></li>
            </ul>
          </div>

          <div class="menu_section">
            <h3>@lang('menu.general')</h3>
            <ul class="nav side-menu">
                <li><a href="{{ url('terraria') }}"><i class="material-icons">video_label</i> <span>@choice('components.terraria', 2)</span></a></li>
                <li><a href="{{ url('animals') }}"><i class="material-icons">pets</i> <span>@choice('components.animals', 2)</span></a></li>
                <li><a><i class="material-icons">device_hub</i> <span>@lang('menu.infrastructure')</span> <span class="hidden-xs material-icons pull-right">keyboard_arrow_down</span></a>
                    <ul class="nav child_menu">
                        <li><a href="{{ url('controlunits') }}"><i class="material-icons">developer_board</i> <span>@choice('components.controlunits', 2)</span></a></li>
                        <li><a href="{{ url('pumps') }}"><i class="material-icons">rotate_right</i> <span>@choice('components.pumps', 2)</span></a></li>
                        <li><a href="{{ url('valves') }}"><i class="material-icons">transform</i> <span>@choice('components.valves', 2)</span></a></li>
                        <li><a href="{{ url('physical_sensors') }}"><i class="material-icons">memory</i> <span>@choice('components.physical_sensors', 2)</span></a></li>
                        <li><a href="{{ url('logical_sensors') }}"><i class="material-icons">memory</i> <span>@choice('components.logical_sensors', 2)</span></a></li>
                     </ul>
                </li>
            </ul>
          </div>

          <div class="menu_section">
            <h3>@lang('menu.administration')</h3>
            <ul class="nav side-menu">
              <li><a><i class="material-icons">add</i> <span>@lang('menu.create')</span> <span class="hidden-xs material-icons pull-right">keyboard_arrow_down</span></a>
                <ul class="nav child_menu">
                    <li><a href="{{ url('terraria/create') }}"><i class="material-icons">video_label</i> <span>@choice('components.terraria', 1)</span></a></li>
                    <li><a href="{{ url('animals/create') }}"><i class="material-icons">pets</i> <span>@choice('components.animals', 1)</span></a></li>
                    <li><a href="{{ url('controlunits/create') }}"><i class="material-icons">developer_board</i> <span>@choice('components.controlunits', 1)</span></a></li>
                    <li><a href="{{ url('pumps/create') }}"><i class="material-icons">rotate_right</i> <span>@choice('components.pumps', 1)</span></a></li>
                    <li><a href="{{ url('valves/create') }}"><i class="material-icons">transform</i> <span>@choice('components.valves', 1)</span></a></li>
                    <li><a href="{{ url('physical_sensors/create') }}"><i class="material-icons">memory</i> <span>@choice('components.physical_sensors', 1)</span></a></li>
                    <li><a href="{{ url('logical_sensors/create') }}"><i class="material-icons">memory</i> <span>@choice('components.logical_sensors', 1)</span></a></li>
                </ul>
              </li>
              <li><a href="{{ url('admin') }}"><i class="material-icons">settings_applications</i> <span>@choice('components.admin_panel', 2)</span></a></li>
              <li><a href="{{ url('logs') }}"><i class="material-icons">list</i> <span>@choice('components.log', 2)</span></a></li>
              <li><a href="{{ url('laravel-logs') }}"><i class="material-icons">list</i> <span>Laravel Logs</span></a></li>
            </ul>
          </div>

          <div class="menu_section">
              <h3>@lang('menu.help')</h3>
              <ul class="nav side-menu">
                  <li><a href="https://github.com/matthenning/ciliatus/issues"><i class="material-icons">bug_report</i> <span>@lang('labels.bugtracker')</span></a></li>
                  <li><a href="https://github.com/matthenning/ciliatus/wiki"><i class="material-icons">help</i> <span>@lang('labels.doku')</span></a></li>
              </ul>
          </div>

        </div>
        <!-- /sidebar menu -->

      </div>
    </div>

    <!-- page content -->
    <div class="right_col" role="main">
      @yield('content')
    </div>
    <!-- /page content -->
  </div>
</div>

<!-- Bootstrap -->
<script src="{{ url('vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- jQuery Sparklines -->
<script src="{{ url('vendors/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<!-- bootstrap-progressbar -->
<script src="{{ url('vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
<!-- Skycons -->
<script src="{{ url('vendors/skycons/skycons.js') }}"></script>
<!-- Switchery -->
<script src="{{ url('vendors/switchery/dist/switchery.min.js') }}"></script>
<!-- Dygraph -->
<script src="{{ url('vendors/dygraph/dygraph-combined.min.js') }}"></script>
<!-- PNotify -->
<script src="{{ url('vendors/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ url('vendors/pnotify/dist/pnotify.buttons.js') }}"></script>
<script src="{{ url('vendors/pnotify/dist/pnotify.nonblock.js') }}"></script>
<!-- Datatables -->
<script src="{{ url('vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<!-- Custom Theme Scripts -->
<script src="{{ url('build/js/custom.js') }}"></script>

<script src="{{ url('js/app.js') }}"></script>
@if(Auth::user())
    @if(Auth::user()->setting('permanent_nightmode_enabled') == 'on')
        <script src="{{ url('js/app_style_dark.js') }}"></script>
    @elseif(Auth::user()->setting('auto_nightmode_enabled') == 'on' && Auth::user()->night())
        <script src="{{ url('js/app_style_dark.js') }}"></script>
    @else
        <script src="{{ url('js/app_style.js') }}"></script>
    @endif
@else
    <script src="{{ url('js/app_style.js') }}"></script>
@endif

<!-- Skycons -->
<script>
  var icons = new Skycons({
            "color": "#73879C"
          }),
          list = [
            "clear-day", "clear-night", "partly-cloudy-day",
            "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
            "fog"
          ],
          i;

  for (i = list.length; i--;)
    icons.set(list[i], list[i]);

  icons.play();
</script>
<!-- /Skycons -->
</body>
</html>