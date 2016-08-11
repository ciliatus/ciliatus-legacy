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
                <a href="/"><i class="fa fa-home"></i> @choice('menu.dashboard', 1) </a>
              </li>
            </ul>
          </div>

          <div class="menu_section">
            <h3>@lang('menu.general')</h3>
            <ul class="nav side-menu">
                <li><a href="{{ url('terraria') }}"><i class="fa fa-columns"></i> @choice('components.terraria', 2)</a></li>
                <li><a href="{{ url('animals') }}"><i class="fa fa-paw"></i> @choice('components.animals', 2)</a></li>
                <li><a><i class="fa fa-cubes"></i> @lang('menu.infrastructure') <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="{{ url('controlunits') }}">@choice('components.controlunits', 2)</a></li>
                        <li><a href="{{ url('pumps') }}">@choice('components.pumps', 2)</a></li>
                        <li><a href="{{ url('valves') }}">@choice('components.valves', 2)</a></li>
                        <li><a href="{{ url('physical_sensors') }}">@choice('components.physical_sensors', 2)</a></li>
                        <li><a href="{{ url('logical_sensors') }}">@choice('components.logical_sensors', 2)</a></li>
                     </ul>
                </li>
            </ul>
          </div>

          <div class="menu_section">
            <h3>@lang('menu.administration')</h3>
            <ul class="nav side-menu">
              <li><a><i class="fa fa-asterisk"></i> @lang('menu.create') <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{ url('terraria/create') }}">@choice('components.terraria', 1)</a></li>
                    <li><a href="{{ url('animals/create') }}">@choice('components.animals', 1)</a></li>
                    <li><a href="{{ url('controlunits/create') }}">@choice('components.controlunits', 1)</a></li>
                    <li><a href="{{ url('pumps/create') }}">@choice('components.pumps', 1)</a></li>
                    <li><a href="{{ url('valves/create') }}">@choice('components.valves', 1)</a></li>
                    <li><a href="{{ url('physical_sensors/create') }}">@choice('components.physical_sensors', 1)</a></li>
                    <li><a href="{{ url('logical_sensors/create') }}">@choice('components.logical_sensors', 1)</a></li>
                    <li><a href="{{ url('logical_sensor_thresholds/create') }}">@choice('components.logical_sensor_thresholds', 1)</a></li>
                </ul>
              </li>
              <li><a href="{{ url('logs') }}"><i class="fa fa-list-ul"></i> @choice('components.log', 2)</a></li>
            </ul>
          </div>

          <div class="menu_section">
              <h3>@lang('menu.help')</h3>
              <ul class="nav side-menu">
                  <li><a href="https://github.com/dasprot/ciliatus/issues"><i class="fa fa-bug"></i> @lang('labels.bugtracker')</a></li>
                  <li><a href="https://github.com/dasprot/ciliatus/wiki"><i class="fa fa-wikipedia-w"></i> @lang('labels.wiki')</a></li>
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