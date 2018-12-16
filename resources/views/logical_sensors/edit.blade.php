@extends('master')

@section('breadcrumbs')
<a href="/logical_sensors" class="breadcrumb hide-on-small-and-down">@choice('labels.logical_sensors', 2)</a>
<a href="/logical_sensors/{{ $logical_sensor->id }}" class="breadcrumb hide-on-small-and-down">{{ $logical_sensor->name }}</a>
<a href="/logical_sensors/{{ $logical_sensor->id }}/edit" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/logical_sensors/' . $logical_sensor->id) }}" data-method="PUT">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $logical_sensor->name }}</span>
                            </span>

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
                                    <label for="valves">@choice('labels.physical_sensors', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <strong class="tooltipped" data-delay="50" data-html="true"
                                            :data-tooltip="'<div style=\'max-width: 300px\'>' + $t('tooltips.logical_sensor_rawvalue_limit') + '</div>'">
                                        @lang('labels.scope')
                                        <i class="mdi mdi-18px mdi-information-outline"></i>
                                    </strong>
                                </div>
                                <div class="input-field col s12 m6 l6">
                                    <input type="text" placeholder="@lang('labels.rawlimitlo')" name="adjusted_value_lowerlimit" value="{{ $logical_sensor->adjusted_value_lowerlimit }}">
                                    <label for="name">@lang('labels.rawlimitlo')</label>
                                </div>
                                <div class="input-field col s12 m6 l6">
                                    <input type="text" placeholder="@lang('labels.rawlimithi')" name="adjusted_value_upperlimit" value="{{ $logical_sensor->adjusted_value_upperlimit }}">
                                    <label for="name">@lang('labels.rawlimithi')</label>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="mdi mdi-18px mdi-floppy left"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-header">
                        <span>
                            <i class="mdi mdi-18px mdi-format-vertical-align-center"></i>
                            @choice('labels.logical_sensor_thresholds', 2)
                        </span>
                    </div>

                    <div class="card-content">
                        @if($logical_sensor->thresholds()->count() > 0)
                            @foreach($logical_sensor->thresholds()->orderBy('starts_at')->get() as $t)
                            <div class="row row-no-margin">
                                <i class="mdi mdi-18px mdi-format-vertical-align-center"></i>
                                {{ $t->starts_at }}:

                                @if(!is_null($t->adjusted_value_lowerlimit))
                                    <span>@lang("labels.min_short"): {{ $t->adjusted_value_lowerlimit }}</span>
                                @endif
                                @if(!is_null($t->adjusted_value_upperlimit))
                                    <span>@lang("labels.max_short"): {{ $t->adjusted_value_upperlimit }}</span>
                                @endif

                                <span class="right">
                                    <a href="{{ url('logical_sensor_thresholds/' . $t->id . '/edit') }}">
                                        <i class="mdi mdi-18px mdi-pencil"></i>
                                    </a>
                                </span>
                            </div>
                            @endforeach
                        @else
                        <div class="row row-no-margin">
                            @lang("tooltips.no_data")
                        </div>
                        @endif
                    </div>


                    <div class="card-action">
                        <a href="/logical_sensor_thresholds/create?preset[belongsTo_type]=LogicalSensor&preset[belongsTo_id]={{ $logical_sensor->id }}">
                            @lang('buttons.add')
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span>
                            <i class="mdi mdi-18px mdi-content-copy"></i>
                            @lang('labels.copy_thresholds')
                        </span>
                    </div>

                    <form action="{{ url('api/v1/logical_sensor_thresholds/' . $logical_sensor->id . '/copy') }}" data-method="POST">
                        <div class="card-content">
                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="logical_sensor_target">
                                        @foreach ($copy_target_ls as $ls)
                                            <option value="{{ $ls->id }}">{{ $ls->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="valves">@lang('labels.target') @choice('labels.logical_sensors', 1)</label>
                                </div>
                            </div>


                            <div class="card-panel deep-orange darken-2 white-text">@lang('tooltips.copy_thresholds_warning')</div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="mdi mdi-18px mdi-floppy left"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="card">
                    <form action="{{ url('api/v1/logical_sensors/' . $logical_sensor->id) }}" data-method="PUT">
                        <div class="card-header">
                            <span class="activator truncate">
                                <span>
                                    <i class="mdi mdi-18px mdi-silverware-variant"></i>
                                    @lang('labels.properties')
                                </span>
                            </span>
                        </div>
                        <div class="card-content">
                            <div class="row">
                                <div class="input-field col s12 tooltipped" data-position="top"
                                     data-delay="50" data-html="true" data-tooltip="<div style='max-width: 300px'>@lang('tooltips.adjust_adjusted_value')</div>">
                                    <input type="text" placeholder="@lang('labels.adjust_rawvalue')" name="LogicalSensorAccuracy::adjust_rawvalue" value="{{ $logical_sensor->property('LogicalSensorAccuracy', 'adjust_rawvalue', true) }}">
                                    <label for="name">
                                        @lang('labels.adjust_rawvalue')
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="mdi mdi-18px mdi-floppy left"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




    
    <div class="fixed-action-btn">

        <a class="btn-floating btn-large orange darken-4">
            <i class="mdi mdi-18px mdi-pencil"></i>
        </a>

        <ul>
            <li><a class="btn-floating teal" href="/logical_sensors/{{ $logical_sensor->id }}"><i class="mdi mdi-18px mdi-information-outline"></i></a></li>
            <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/logical_sensors/{{ $logical_sensor->id }}/delete"><i class="mdi mdi-24px mdi-delete"></i></a></li>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/logical_sensors/create"><i class="mdi mdi-24px mdi-plus"></i></a></li>
        </ul>

    </div>
@stop