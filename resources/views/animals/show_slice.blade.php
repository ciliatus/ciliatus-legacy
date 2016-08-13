<div id="animal-{{ $a->id }}">
    <div class="x_panel @if(!is_null($animal->terrarium)) @if(!$animal->terrarium->stateOk()) x_panel-danger @endif @endif">

        <div class="x_title">
            <h2><i class="material-icons">pets</i> <a href="{{ url('animals/' . $animal->id) }}">{{ $animal->display_name }}</a>
                @if ($animal->gender == 'male')
                    <i class="fa fa-mars"></i>
                @elseif ($animal->gender == 'female')
                    <i class="fa fa-venus"></i>
                @else
                    <i class="fa fa-genderless"></i>
                @endif</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="material-icons">expand_less</i></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">settings</i></a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ url('animals/' . $animal->id . '/edit') }}"><i class="material-icons">mode_edit</i> @lang('menu.edit')</a>
                        </li>
                        <li>
                            <a href="{{ url('animals/' . $animal->id . '/delete') }}"><i class="material-icons">delete</i> @lang('menu.delete')</a>
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
                        @if (!is_null($animal->birth_date))
                            <strong>*</strong>
                            {{ $animal->birth_date->toDateString() }} @if(is_null($animal->death_date))({{ $animal->getAge()['value'] }} {{ trans_choice('units.' . $animal->getAge()['unit'], $animal->getAge()['value']) }})@endif
                        @endif

                        @if (!is_null($animal->death_date))
                            <strong>†</strong>
                            {{ $animal->death_date->toDateString() }} ({{ $animal->getAge()['value'] }} {{ trans_choice('units.' . $animal->getAge()['unit'], $animal->getAge()['value']) }})
                        @endif
                        <br />

                        <strong>@lang('labels.common_name'): </strong><span>{{ $animal->common_name }}</span><br />
                        <strong>@lang('labels.latin_name'): </strong><span>{{ $animal->lat_name }}</span><br />
                        @if(!is_null($animal->terrarium))
                            <strong>@choice('components.terraria', 1): </strong><span><a href="{{ url('terraria/' . $animal->terrarium->id) }}">{{ $animal->terrarium->display_name }}</a></span>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>