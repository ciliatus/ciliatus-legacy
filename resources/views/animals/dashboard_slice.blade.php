@foreach ($animals as $a)
    <div class="col-md-3 col-sm-4 col-lg-2 col-xs-12 dashboard-box" id="animal-{{ $a->id }}">
        <div class="x_panel">

            <div class="x_title">
                <h2>{{ $a->display_name }} <small>Animal</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('animals/' . $a->id . '/edit') }}">Edit</a>
                            </li>
                            <li>
                                <a href="{{ url('animals/' . $a->id . '/delete') }}">Delete</a>
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
                        <div>
                            <strong>Gender: </strong>
                            @if ($a->gender == 'male')
                                <i class="fa fa-mars"></i>
                            @elseif ($a->gender == 'female')
                                <i class="fa fa-venus"></i>
                            @else
                                <i class="fa fa-genderless"></i>
                            @endif
                            <br />

                            <strong>Birth:</strong>
                            @if ($a->birth_date)
                                ({{ $a->getAge()['value'] }} {{ $a->getAge()['unit'] }})
                            @endif
                            <br />

                            <strong>Common name: </strong><span>{{ $a->common_name }}</span><br />
                            <strong>Latin name: </strong><span>{{ $a->lat_name }}</span><br />
                            <strong>Terrarium: </strong><span><a href="{{ url('terraria/' . $a->terrarium->id) }}">{{ $a->terrarium->name }}</a></span>
                        </div>

                    </div>
                </div>
                <div class="row weather-days">
                    <div class="col-sm-12">
                        <div class="daily-weather">
                            <h2 class="day">Terrarium</h2>
                            <h3 class="terrarium-widget-temp">{{ $a->terrarium->getState() }}</h3>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@endforeach