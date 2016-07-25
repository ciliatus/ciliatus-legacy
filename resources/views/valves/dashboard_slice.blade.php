@foreach ($valves as $v)
    <div class="col-md-4 col-sm-6 col-lg-3 col-xs-12 dashboard-box" id="valve-{{ $v->id }}">
        <div class="x_panel">

            <div class="x_title">
                <h2>{{ $v->name }} <small>Valve</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('valves/' . $v->id . '/edit') }}">Edit</a>
                            </li>
                            <li>
                                <a href="{{ url('valves/' . $v->id . '/delete') }}">Delete</a>
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
                        <strong>Control Unit:</strong> <a href="{{ url('controlunits/' . $v->controlunit->id) }}">{{ $v->controlunit->name }}</a><br />
                        <strong>Pump:</strong> <a href="{{ url('pumps/' . $v->pump->id) }}">{{ $v->pump->name }}</a><br />
                        <strong>Terrarium:</strong> <a href="{{ url('terraria/' . $v->terrarium->id) }}">{{ $v->terrarium->name }}</a><br />
                    </div>
                </div>
                <div class="row weather-days">
                    <div class="col-sm-12">
                        <div class="daily-weather">
                            <h2 class="day">State</h2>
                            <h3 class="terrarium-widget-temp">{{ $v->state }}</h3>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@endforeach