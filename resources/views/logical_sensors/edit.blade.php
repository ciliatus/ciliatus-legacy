@extends('master')

@section('breadcrumbs')
<a href="/logical_sensors" class="breadcrumb">@choice('components.logical_sensors', 2)</a>
<a href="/logical_sensors/{{ $logical_sensor->id }}" class="breadcrumb">{{ $logical_sensor->name }}</a>
<a href="/logical_sensors/{{ $logical_sensor->id }}/edit" class="breadcrumb">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/logical_sensors/' . $logical_sensor->id) }}" data-method="PUT"
                          data-redirect-success="{{ url('logical_sensors/' . $logical_sensor->id) }}">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $logical_sensor->name }}</span>
                            </span>

                            <p>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" readonly="readonly" placeholder="ID" name="id" value="{{ $logical_sensor->id }}">
                                        <label for="id">ID</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" placeholder="@lang('labels.name')" name="name" value="{{ $logical_sensor->name }}">
                                        <label for="name">@lang('labels.name')</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <select name="type">
                                            <option></option>
                                            <option value="humidity_percent" @if($logical_sensor->type == 'humidity_percent')selected="selected"@endif>@lang('labels.humidity') %</option>
                                            <option value="temperature_celsius" @if($logical_sensor->type == 'temperature_celsius')selected="selected"@endif>@lang('labels.temperature') °C</option>
                                        </select>
                                        <label for="valves">@lang('labels.type')</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <select name="physical_sensor">
                                            <option></option>
                                            @foreach ($physical_sensors as $ps)
                                                <option value="{{ $ps->id }}" @if($logical_sensor->physical_sensor_id == $ps->id)selected="selected"@endif>{{ $ps->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="valves">@choice('components.physical_sensors', 1)</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12 m6 l6">
                                        <input type="text" placeholder="@lang('labels.rawlimitlo')" name="lowerlimit" value="{{ $logical_sensor->rawvalue_lowerlimit }}">
                                        <label for="name">@lang('labels.rawlimitlo')</label>
                                    </div>
                                    <div class="input-field col s12 m6 l6">
                                        <input type="text" placeholder="@lang('labels.rawlimithi')" name="upperlimit" value="{{ $logical_sensor->rawvalue_upperlimit }}">
                                        <label for="name">@lang('labels.rawlimithi')</label>
                                    </div>
                                </div>

                            </p>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">

                        <span class="card-title activator truncate">
                            <span>@choice('components.logical_sensor_thresholds', 2)</span>
                        </span>

                        <p>

                            <div class="row">
                                @foreach($logical_sensor->thresholds()->orderBy('starts_at')->get() as $t)
                                    <div class="input-field col s12">

                                        @lang('labels.starts_at') {{ $t->starts_at }}:

                                        <strong>
                                            @if(is_null($t->rawvalue_lowerlimit) && !is_null($t->rawvalue_upperlimit))
                                                max {{ $t->rawvalue_upperlimit }}
                                            @elseif(!is_null($t->rawvalue_lowerlimit) && is_null($t->rawvalue_upperlimit))
                                                min {{ $t->rawvalue_lowerlimit }}
                                            @elseif(is_null($t->rawvalue_lowerlimit) && is_null($t->rawvalue_upperlimit))
                                            @else
                                                {{ $t->rawvalue_lowerlimit }} - {{ $t->rawvalue_upperlimit }}
                                            @endif
                                        </strong>

                                        <a class="dropdown-button btn btn-small" href="#" data-activates="dropdown-edit-logical_sensor_thresholds_{{ $t->id }}" style="margin-left: 20px">
                                            @lang('labels.actions') <i class="material-icons">keyboard_arrow_down</i>
                                        </a>

                                        <ul id="dropdown-edit-logical_sensor_thresholds_{{ $t->id }}" class="dropdown-content">
                                            <li>
                                                <a href="{{ url('logical_sensor_thresholds/' . $t->id . '/edit') }}">
                                                    @lang('buttons.edit')
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ url('logical_sensor_thresholds/' . $t->id . '/delete') }}">
                                                    @lang('buttons.delete')
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                @endforeach

                                <a class="btn-floating btn-large waves-effect waves-light green right" href="/logical_sensor_thresholds/create?preset[belongsTo_type]=LogicalSensor&preset[belongsTo_id]={{ $logical_sensor->id }}">
                                    <i class="material-icons">add</i>
                                </a>

                            </div>

                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>


    
    <div class="fixed-action-btn">

        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>

        <ul>
            <li><a class="btn-floating teal" href="/logical_sensors/{{ $logical_sensor->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red" href="/logical_sensors/{{ $logical_sensor->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/logical_sensors/create"><i class="material-icons">add</i></a></li>
        </ul>

    </div>
@stop