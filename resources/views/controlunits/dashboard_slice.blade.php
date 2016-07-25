@foreach ($controlunits as $cu)
    <div class="col-md-4 col-sm-6 col-lg-3 col-xs-12 dashboard-box" id="controlunit-{{ $cu->id }}">
        <div class="x_panel">

            <div class="x_title">
                <h2>{{ $cu->name }} <small>Control Unit</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('controlunits/' . $cu->id . '/edit') }}">Edit</a>
                            </li>
                            <li>
                                <a href="{{ url('controlunits/' . $cu->id . '/delete') }}">Delete</a>
                            </li>
                        </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="row">
                    <div class="col-xs-12">

                    </div>
                </div>
                <div class="row weather-days">
                    <div class="col-sm-12">
                        <div class="daily-weather">
                            <h2 class="day">Heartbeat</h2>
                            <h3 class="terrarium-widget-temp">{{ $cu->heartbeatOk() }}</h3>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@endforeach