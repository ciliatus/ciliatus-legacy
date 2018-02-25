@extends('master')

@section('breadcrumbs')
<a href="/physical_sensors" class="breadcrumb hide-on-small-and-down">@choice('labels.physical_sensors', 2)</a>
<a href="/physical_sensors/{{ $physical_sensor->id }}" class="breadcrumb hide-on-small-and-down">{{ $physical_sensor->name }}</a>
<a href="/physical_sensors/{{ $physical_sensor->id }}/edit" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <div class="card">
                    <form action="{{ url('api/v1/physical_sensors/' . $physical_sensor->id) }}" data-method="PUT">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $physical_sensor->name }}</span>
                            </span>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.name')" name="name" value="{{ $physical_sensor->name }}">
                                    <label for="name">@lang('labels.name')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.model')" name="model" value="{{ $physical_sensor->model }}"
                                           class="autocomplete" id="model-autocomplete" autocomplete="off">
                                    <label for="model">@lang('labels.model')</label>

                                    <script>
                                        /*
                                         * TODO: find alternative to using a timeout
                                         */
                                        $(document).ready(function () {
                                            setTimeout(function()
                                            {
                                                $('#model-autocomplete').autocomplete({
                                                    data: {
                                                        @foreach ($models as $model)
                                                        '{{ $model }}': null,
                                                        @endforeach
                                                    },
                                                    limit: 20,
                                                });

                                            }, 500)
                                        });
                                    </script>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="controlunit">
                                        <option></option>
                                        @foreach ($controlunits as $c)
                                            <option value="{{ $c->id }}" @if($physical_sensor->controlunit_id == $c->id)selected="selected"@endif>{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="valves">@choice('labels.controlunits', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="belongsTo">
                                        <option></option>
                                        @foreach ($belongTo_Options as $t=>$objects)
                                            <optgroup label="@choice('labels.' . strtolower($t), 2)">
                                                @foreach ($objects as $o)
                                                    <option value="{{ $t }}|{{ $o->id }}"
                                                            @if($physical_sensor->belongsTo_id == $o->id && $physical_sensor->belongsTo_type = $t)
                                                            selected
                                                            @endif>@if(is_null($o->display_name)) {{ $o->name }} @else {{ $o->display_name }} @endif</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <label for="valves">@lang('labels.belongsTo')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <label for="active">@lang('labels.active')</label>
                                    <div class="switch">
                                        <label>
                                            @lang('labels.on')
                                            <input name="active" type="hidden" value="off">
                                            <input name="active" type="checkbox" value="on"
                                                   @if($physical_sensor->active()) checked @endif>
                                            <span class="lever"></span>
                                            @lang('labels.off')
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="material-icons left">save</i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="col s12 m6 l6">
                <bus-type-edit-form form-uri="{{ url('api/v1/physical_sensors/' . $physical_sensor->id) }}"
                                    physical-sensor-id="{{ $physical_sensor->id }}"
                                    bus-type="{{ $physical_sensor->property('ControlunitConnectivity', 'bus_type', true) }}"
                                    gpio-pin="{{ $physical_sensor->property('ControlunitConnectivity', 'gpio_pin', true) }}"
                                    :gpio-default-high="{{ ($physical_sensor->property('ControlunitConnectivity', 'gpio_default_high', true) ? 'true' : 'false') }}"
                                    i2c-address="{{ $physical_sensor->property('ControlunitConnectivity', 'i2c_address', true) }}"
                                    i2c-multiplexer-address="{{ $physical_sensor->property('ControlunitConnectivity', 'i2c_multiplexer_address', true) }}"
                                    i2c-multiplexer-port="{{ $physical_sensor->property('ControlunitConnectivity', 'i2c_multiplexer_port', true) }}">
                </bus-type-edit-form>
            </div>
        </div>
    </div>
    
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/physical_sensors/{{ $physical_sensor->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/physical_sensors/{{ $physical_sensor->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/physical_sensors/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop