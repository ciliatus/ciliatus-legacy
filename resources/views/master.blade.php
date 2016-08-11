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
    {!! Html::style('vendors/bootstrap/dist/css/bootstrap.min.css') !!}
    {!! Html::style('css/bootstrap_xl.css') !!}
    <!-- Font Awesome -->
    {!! Html::style('vendors/font-awesome/css/font-awesome.min.css') !!}
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    {!! Html::style('vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') !!}
    <!-- Switchery -->
    {!! Html::style('vendors/switchery/dist/switchery.min.css') !!}
    <!-- Custom Theme Style -->
    @if(Auth::user())
        @if(Auth::user()->setting('permanent_nightmode_enabled') == 'on')
            {!! Html::style('build/css/custom_dark.css') !!}
        @elseif(Auth::user()->setting('auto_nightmode_enabled') == 'on' && Auth::user()->night())
            {!! Html::style('build/css/custom_dark.css') !!}
        @else
            {!! Html::style('build/css/custom.css') !!}
        @endif
    @else
        {!! Html::style('build/css/custom.css') !!}
    @endif

    <!-- PNotify -->
    {!! Html::style('vendors/pnotify/dist/pnotify.css') !!}
    {!! Html::style('vendors/pnotify/dist/pnotify.buttons.css') !!}
    {!! Html::style('vendors/pnotify/dist/pnotify.nonblock.css') !!}
    <!-- weather icons -->
    {!! Html::style('vendors/weather-icons/css/weather-icons.min.css') !!}
<!-- Datatables -->
    {!! Html::style('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') !!}
    {!! Html::style('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') !!}
    {!! Html::style('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') !!}
    {!! Html::style('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') !!}
    {!! Html::style('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') !!}
    <!-- jQuery -->
    {!! Html::script('vendors/jquery/dist/jquery.min.js') !!}
    <!-- jQuery UI -->
    {!! Html::style('vendors/jquery-ui/jquery-ui.min.css') !!}
    {!! Html::script('vendors/jquery-ui/jquery-ui.min.js') !!}

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
              <li>
                <a href="/"><i class="material-icons">dashboard</i> <span>@choice('menu.dashboard', 1)</span></a>
              </li>
            </ul>
          </div>

          <div class="menu_section">
            <h3>@lang('menu.general')</h3>
            <ul class="nav side-menu">
                <li><a href="{{ url('terraria') }}"><i class="material-icons">video_label</i> <span>@choice('components.terraria', 2)</span></a></li>
                <li><a href="{{ url('animals') }}"><i class="material-icons">pets</i> <span>@choice('components.animals', 2)</span></a></li>
                <li><a><i class="material-icons">device_hub</i> <span>@lang('menu.infrastructure')</span> <span class="material-icons pull-right">keyboard_arrow_down</span></a>
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
              <li><a><i class="material-icons">add</i> <span>@lang('menu.create')</span> <span class="material-icons pull-right">keyboard_arrow_down</span></a>
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
              <li><a href="{{ url('logs') }}"><i class="material-icons">list</i> <span>@choice('components.log', 2)</span></a></li>
            </ul>
          </div>

          <div class="menu_section">
              <h3>@lang('menu.help')</h3>
              <ul class="nav side-menu">
                  <li><a href="https://github.com/dasprot/ciliatus/issues"><i class="material-icons">bug_report</i> <span>@lang('labels.bugtracker')</span></a></li>
                  <li><a href="https://github.com/dasprot/ciliatus/wiki"><i class="material-icons">help</i> <span>@lang('labels.doku')</span></a></li>
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
{!! Html::script('vendors/bootstrap/dist/js/bootstrap.min.js') !!}
<!-- jQuery Sparklines -->
{!! Html::script('vendors/jquery-sparkline/dist/jquery.sparkline.min.js') !!}
<!-- bootstrap-progressbar -->
{!! Html::script('vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') !!}
<!-- Skycons -->
{!! Html::script('vendors/skycons/skycons.js') !!}
<!-- Switchery -->
{!! Html::script('vendors/switchery/dist/switchery.min.js') !!}
<!-- Dygraph -->
{!! Html::script('vendors/dygraph/dygraph-combined.min.js') !!}
<!-- PNotify -->
{!! Html::script('vendors/pnotify/dist/pnotify.js') !!}
{!! Html::script('vendors/pnotify/dist/pnotify.buttons.js') !!}
{!! Html::script('vendors/pnotify/dist/pnotify.nonblock.js') !!}
<!-- Datatables -->
{!! Html::script('vendors/datatables.net/js/jquery.dataTables.min.js') !!}
<!-- Custom Theme Scripts -->
{!! Html::script('build/js/custom.js') !!}

{!! Html::script('js/app.js') !!}
@if(Auth::user())
    @if(Auth::user()->setting('permanent_nightmode_enabled') == 'on')
        {!! Html::script('js/app_style_dark.js') !!}
    @elseif(Auth::user()->setting('auto_nightmode_enabled') == 'on' && Auth::user()->night())
        {!! Html::script('js/app_style_dark.js') !!}
    @else
        {!! Html::script('js/app_style.js') !!}
    @endif
@else
    {!! Html::script('js/app_style.js') !!}
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