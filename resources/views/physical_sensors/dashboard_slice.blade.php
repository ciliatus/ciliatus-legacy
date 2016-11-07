@foreach ($physical_sensors as $ps)
    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 dashboard-box" id="physical_sensor-{{ $ps->id }}">
        <div class="x_panel">

            <div class="x_title">
                <h2><i class="material-icons">memory</i> <a href="{{ url('physical_sensors/' . $ps->id) }}">{{ $ps->name }}</a></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="material-icons">expand_less</i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">settings</i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('physical_sensors/' . $ps->id . '/edit') }}"><i class="material-icons">mode_edit</i> @lang('menu.edit')</a>
                            </li>
                            <li>
                                <a href="{{ url('physical_sensors/' . $ps->id . '/delete') }}"><i class="material-icons">delete</i> @lang('menu.delete')</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="row">
                    <div class="col-xs-12">
                        <strong>@choice('components.controlunits', 1):</strong> <a href="{{ url('controlunits/' . $ps->controlunit->id) }}">{{ $ps->controlunit->name }}</a>
                    </div>
                    @if(!is_null($ps->belongsTo_object()))
                    <div class="col-xs-12">
                        <strong>@choice('components.' . $ps->belongsTo_type, 1):</strong> <a href="{{ url($ps->belongsTo_object()->url()) }}">{{ $ps->belongsTo_object()->display_name }}</a>
                    </div>
                    @endif
                </div>
                <div class="row weather-days">
                    @foreach ($ps->logical_sensors as $ls)
                        <div class="col-sm-6">
                            <div class="daily-weather">
                                <h2 class="day"><a href="{{ url('logical_sensors/' . $ls->id) }}">{{ $ls->name }}</a></h2>
                                <h3 class="terrarium-widget-temp">{{ $ls->valueReadable() }}</h3>
                            </div>
                        </div>
                    @endforeach
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
@endforeach