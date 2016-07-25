@foreach ($terraria as $t)
    <div class="col-md-4 col-sm-6 col-lg-3 col-xs-12 dashboard-box" id="terrarium-{{ $t->id }}" data-livedata="true" data-livedatainterval="60" data-livedatasource="{{ url('api/v1/terraria/' . $t->id) }}" data-livedatatype="parse_terrarium_dashboard">
        <div class="x_panel">

            <div class="x_title">
                <h2>{{ $t->friendly_name }} <small>Terrarium</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('terraria/' . $t->id . '/edit') }}">Edit</a>
                            </li>
                            <li>
                                <a href="{{ url('terraria/' . $t->id . '/delete') }}">Delete</a>
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
                        @if($t->animals->count() < 1)
                            <div>
                                <strong>No animals</strong>
                            </div>
                        @else
                            @foreach ($t->animals as $animal)
                            <div>
                                <strong>{{ $animal->display_name }}</strong>
                                @if ($animal->gender == 'male')
                                    <i class="fa fa-mars"></i>
                                @elseif ($animal->gender == 'female')
                                    <i class="fa fa-venus"></i>
                                @else
                                    <i class="fa fa-genderless"></i>
                                @endif
                                @if ($animal->birth_date)
                                    ({{ $animal->getAge()['value'] }} {{ $animal->getAge()['unit'] }})
                                @endif
                                <span>{{ $animal->common_name }}</span> <span><i>{{ $animal->lat_name }}</i></span>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <strong>Valve: </strong> <span class="text-info">closed</span>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <strong>Pump: </strong> <span class="text-info">stopped</span>
                    </div>
                </div>

                <div class="row weather-days">
                    <div class="col-sm-6">
                        <div class="daily-weather">
                            <h2 class="day">Temperature</h2>
                            <h3 class="terrarium-widget-temp"></h3>
                            <div class="widget-sparkline dashboard-widget-sparkline-temp">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="daily-weather">
                            <h2 class="day">Humidity</h2>
                            <h3 class="terrarium-widget-humidity"></h3>
                            <div class="widget-sparkline dashboard-widget-sparkline-humidity">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </div>
@endforeach