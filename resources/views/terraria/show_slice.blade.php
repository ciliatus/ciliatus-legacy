<div id="terrarium-{{ $terrarium->id }}" data-livedata="true" data-livedatainterval="60" data-livedatasource="{{ url('api/v1/terraria/' . $terrarium->id) }}" data-livedatacallback="terrariaDashboardCallback">
    <div class="x_panel">

        <div class="x_title">
            <h2><i class="material-icons">video_label</i><a href="{{ url('terraria/' . $terrarium->id) }}">{{ $terrarium->display_name }}</a></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="material-icons">expand_less</i></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">settings</i></a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ url('terraria/' . $terrarium->id . '/edit') }}"><i class="material-icons">mode_edit</i> @lang('menu.edit')</a>
                        </li>
                        <li>
                            <a href="{{ url('terraria/' . $terrarium->id . '/delete') }}"><i class="material-icons">delete</i> @lang('menu.delete')</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="row">
                <div class="col-xs-12">
                    @if($terrarium->animals->count() < 1)
                        <div>
                            <strong>@lang('labels.noanimals')</strong>
                        </div>
                    @else
                        @foreach ($terrarium->animals as $animal)
                            <div>
                                <i class="material-icons">pets</i>
                                <strong><a href="{{ url('animals/' . $animal->id) }}">{{ $animal->display_name }}</a></strong>
                                @if ($animal->gender == 'male')
                                    <i class="fa fa-mars"></i>
                                @elseif ($animal->gender == 'female')
                                    <i class="fa fa-venus"></i>
                                @else
                                    <i class="fa fa-genderless"></i>
                                @endif
                                @if (!is_null($animal->birth_date))
                                    ({{ $animal->getAge()['value'] }} {{ trans_choice('units.' . $animal->getAge()['unit'], $animal->getAge()['value']) }})
                                @endif
                                <span>{{ $animal->common_name }}</span> <span class="hidden-sm hidden-xs hidden-md hidden-lg"><i>{{ $animal->lat_name }}</i></span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="row weather-days">
                <div class="col-sm-6 col-xs-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="daily-weather">
                        <h2 class="day">@lang('labels.temperature')</h2>
                        <h3 class="terrarium-widget-temp"></h3>
                        <div class="widget-sparkline dashboard-widget-sparkline-temp">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xs-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="daily-weather">
                        <h2 class="day">@lang('labels.humidity')</h2>
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