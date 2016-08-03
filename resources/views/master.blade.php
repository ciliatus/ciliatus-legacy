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
    {!! Html::style('build/css/custom.css') !!}
    <!-- PNotify -->
    {!! Html::style('vendors/pnotify/dist/pnotify.css') !!}
    {!! Html::style('vendors/pnotify/dist/pnotify.buttons.css') !!}
    {!! Html::style('vendors/pnotify/dist/pnotify.nonblock.css') !!}
    <!-- weather icons -->
    {!! Html::style('vendors/weather-icons/css/weather-icons.min.css') !!}
    <!-- jQuery -->
    {!! Html::script('vendors/jquery/dist/jquery.min.js') !!}
    <!-- jQuery UI -->
    {!! Html::style('vendors/jquery-ui/jquery-ui.min.css') !!}
    {!! Html::script('vendors/jquery-ui/jquery-ui.min.js') !!}

</head>

<body class="nav-md">
<div class="container body">
  <div class="main_container">
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
            <h2>{{ \Auth::user()->name }}</h2>
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
                  <li><a href="{{ url('controlunits') }}"><i class="fa fa-cogs"></i> @choice('components.controlunits', 2)</a></li>
                  <li><a href="{{ url('pumps') }}"><i class="fa fa-circle-o"></i> @choice('components.pumps', 2)</a></li>
                  <li><a href="{{ url('valves') }}"><i class="fa fa-circle-o"></i> @choice('components.valves', 2)</a></li>
                  <li><a href="{{ url('physical_sensors') }}"><i class="fa fa-circle-o"></i> @choice('components.physicalsensors', 2)</a></li>
                  <li><a href="{{ url('logical_sensors') }}"><i class="fa fa-circle-o"></i> @choice('components.logicalsensors', 2)</a></li>
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
                    <li><a href="{{ url('physical_sensors/create') }}">@choice('components.physicalsensors', 1)</a></li>
                    <li><a href="{{ url('logical_sensors/create') }}">@choice('components.logicalsensors', 1)</a></li>
                </ul>
              </li>
            </ul>
          </div>

        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
          <a data-toggle="tooltip" data-placement="top" title="Settings">
            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
          </a>
          <a data-toggle="tooltip" data-placement="top" title="FullScreen">
            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
          </a>
          <a data-toggle="tooltip" data-placement="top" title="Lock">
            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
          </a>
          <a data-toggle="tooltip" data-placement="top" title="Logout">
            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
          </a>
        </div>
        <!-- /menu footer buttons -->
      </div>
    </div>

    <!-- page content -->
    <div class="right_col" role="main">
      @yield('content')
    </div>
    <!-- /page content -->

    <!-- footer content -->
    <footer>
      <div class="pull-right">
        Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
      </div>
      <div class="clearfix"></div>
    </footer>
    <!-- /footer content -->
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
<!-- Custom Theme Scripts -->
{!! Html::script('build/js/custom.min.js') !!}

{!! Html::script('js/app.js') !!}

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