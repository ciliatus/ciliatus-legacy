@foreach ($logical_sensors as $ls)
    <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 dashboard-box" id="logical_sensor-{{ $ls->id }}">
        <div class="x_panel">

            <div class="x_title">
                <h2><i class="material-icons">memory</i> <a href="{{ url('logical_sensors/' . $ls->id) }}">{{ $ls->name }}</a></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="material-icons">expand_less</i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">build</i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('logical_sensors/' . $ls->id . '/edit') }}"><i class="material-icons">mode_edit</i> @lang('menu.edit')</a>
                            </li>
                            <li>
                                <a href="{{ url('logical_sensors/' . $ls->id . '/delete') }}"><i class="material-icons">delete</i> @lang('menu.delete')</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="row">
                    <div class="col-xs-12">
                        @if (!is_null($ls->physical_sensor))
                        <strong>@choice('components.physical_sensors', 1):</strong> <a href="{{ url('physical_sensors/' . $ls->physical_sensor->id) }}">{{ $ls->physical_sensor->name }}</a>
                        @endif
                    </div>
                </div>
                <div class="row weather-days">
                        <div class="col-sm-12">
                            <div class="daily-weather">
                                <h2 class="day">@lang('labels.'. $ls->type)</h2>
                                <h3 class="terrarium-widget-temp">{{ $ls->valueReadable() }}</h3>
                            </div>
                        </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@endforeach