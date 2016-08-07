@foreach ($animals as $a)
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 dashboard-box" id="animal-{{ $a->id }}">
        <div class="x_panel @if(!is_null($a->terrarium)) @if(!$a->terrarium->stateOk()) x_panel-danger @endif @endif">

            <div class="x_title">
                <h2><a href="{{ url('animals/' . $a->id) }}">{{ $a->display_name }}</a>
                    @if ($a->gender == 'male')
                        <i class="fa fa-mars"></i>
                    @elseif ($a->gender == 'female')
                        <i class="fa fa-venus"></i>
                    @else
                        <i class="fa fa-genderless"></i>
                    @endif</h2>
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
                            @if (!is_null($a->birth_date))
                            <strong>*</strong>
                            {{ $a->birth_date->toDateString() }} @if(is_null($a->death_date))({{ $a->getAge()['value'] }} {{ trans_choice('units.' . $a->getAge()['unit'], $a->getAge()['value']) }})@endif
                            @endif

                            @if (!is_null($a->death_date))
                            <strong>†</strong>
                            {{ $a->death_date->toDateString() }} ({{ $a->getAge()['value'] }} {{ trans_choice('units.' . $a->getAge()['unit'], $a->getAge()['value']) }})
                            @endif
                            <br />

                            <strong>@lang('labels.common_name'): </strong><span>{{ $a->common_name }}</span><br />
                            <strong>@lang('labels.latin_name'): </strong><span>{{ $a->lat_name }}</span><br />
                            @if(!is_null($a->terrarium))
                            <strong>@choice('components.terraria', 1): </strong><span><a href="{{ url('terraria/' . $a->terrarium->id) }}">{{ $a->terrarium->display_name }}</a></span>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach