@foreach ($logical_sensor_thresholds as $ls)
    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 dashboard-box" id="logical_sensor_threshold-{{ $ls->id }}">
        <div class="x_panel">

            <div class="x_title">
                <h2><a href="{{ url('logical_sensor_thresholds/' . $ls->id) }}">{{ $ls->name }}</a></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('logical_sensor_thresholds/' . $ls->id . '/edit') }}">@lang('menu.edit')</a>
                            </li>
                            <li>
                                <a href="{{ url('logical_sensor_thresholds/' . $ls->id . '/delete') }}">@lang('menu.delete')</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endforeach