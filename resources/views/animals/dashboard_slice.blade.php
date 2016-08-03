@foreach ($animals as $a)
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 dashboard-box" id="animal-{{ $a->id }}">
        <div class="x_panel">

            <div class="x_title">
                <h2>{{ $a->display_name }}</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('animals/' . $a->id . '/edit') }}">@lang('menu.edit')</a>
                            </li>
                            <li>
                                <a href="{{ url('animals/' . $a->id . '/delete') }}">@lang('menu.delete')</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="row">
                    <div class="col-xs-12">
                        <div>
                            <strong>@lang('labels.gender'): </strong>
                            @if ($a->gender == 'male')
                                <i class="fa fa-mars"></i>
                            @elseif ($a->gender == 'female')
                                <i class="fa fa-venus"></i>
                            @else
                                <i class="fa fa-genderless"></i>
                            @endif
                            <br />

                            <strong>@lang('labels.birth'):</strong>
                            @if ($a->birth_date)
                                ({{ $a->getAge()['value'] }} {{ $a->getAge()['unit'] }})
                            @endif
                            <br />

                            <strong>@lang('labels.common_name'): </strong><span>{{ $a->common_name }}</span><br />
                            <strong>@lang('labels.latin_name'): </strong><span>{{ $a->lat_name }}</span><br />
                            <strong>@choice('components.terraria', 1): </strong><span><a href="{{ url('terraria/' . $a->terrarium->id) }}">{{ $a->terrarium->name }}</a></span>
                        </div>

                    </div>
                </div>
                <div class="row weather-days">
                    <div class="col-sm-12">
                        <div class="daily-weather">
                            <h2 class="day">@choice('components.terraria', 1)</h2>
                            <h3 class="terrarium-widget-temp">{{ $a->terrarium->getState() }}</h3>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@endforeach