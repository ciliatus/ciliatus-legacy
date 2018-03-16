@extends('master')

@section('breadcrumbs')
<a href="/valves" class="breadcrumb hide-on-small-and-down">@choice('labels.valves', 2)</a>
<a href="/valves/{{ $valve->id }}" class="breadcrumb hide-on-small-and-down">{{ $valve->name }}</a>
<a href="/valves/{{ $valve->id }}/edit" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/valves/' . $valve->id) }}" data-method="PUT"
                          >
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $valve->name }}</span>
                            </span>

                            <p>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" readonly="readonly" placeholder="ID" name="id" value="{{ $valve->id }}">
                                        <label for="id">ID</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" placeholder="@lang('labels.name')" name="name" value="{{ $valve->name }}">
                                        <label for="name">@lang('labels.name')</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" placeholder="@lang('labels.model')" name="model" value="{{ $valve->model }}"
                                               id="valve-model" class="autocomplete">
                                        <label for="name">@lang('labels.model')</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <select name="controlunit">
                                            <option></option>
                                            @foreach ($controlunits as $cu)
                                                <option value="{{ $cu->id }}" @if($valve->controlunit_id == $cu->id)selected="selected"@endif>{{ $cu->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="valves">@choice('labels.controlunits', 1)</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <select name="pump">
                                            <option></option>
                                            @foreach ($pumps as $p)
                                                <option value="{{ $p->id }}" @if($valve->pump_id == $p->id)selected="selected"@endif>{{ $p->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="valves">@choice('labels.pumps', 1)</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <select name="terrarium">
                                            <option></option>
                                            @foreach ($terraria as $t)
                                                <option value="{{ $t->id }}" @if($valve->terrarium_id == $t->id)selected="selected"@endif>{{ $t->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="valves">@choice('labels.terraria', 1)</label>
                                    </div>
                                </div>
                            </p>

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

            <div class="col s12 m6 l6">
                <bus-type-edit-form form-uri="{{ url('api/v1/valves/' . $valve->id) }}"
                                    physical-sensor-id="{{ $valve->id }}"
                                    bus-type="{{ $valve->property('ControlunitConnectivity', 'bus_type', true) }}"
                                    gpio-pin="{{ $valve->property('ControlunitConnectivity', 'gpio_pin', true) }}"
                                    :gpio-default-high="{{ ($valve->property('ControlunitConnectivity', 'gpio_default_high', true) ? 'true' : 'false') }}"
                                    i2c-address="{{ $valve->property('ControlunitConnectivity', 'i2c_address', true) }}"
                                    i2c-multiplexer-address="{{ $valve->property('ControlunitConnectivity', 'i2c_multiplexer_address', true) }}"
                                    i2c-multiplexer-port="{{ $valve->property('ControlunitConnectivity', 'i2c_multiplexer_port', true) }}">
                </bus-type-edit-form>
            </div>
        </div>
    </div>
    
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="mdi mdi-18px mdi-pencil"></i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/valves/{{ $valve->id }}"><i class="mdi mdi-18px mdi-information-outline"></i></a></li>
            <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/valves/{{ $valve->id }}/delete"><i class="mdi mdi-24px mdi-delete"></i></a></li>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/valves/create"><i class="mdi mdi-24px mdi-plus"></i></a></li>
        </ul>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#valve-model').autocomplete({
                data: {
                    @foreach ($models as $m)
                    "{{ $m }}": null,
                    @endforeach
                },
                limit: 20,
                minLength: 1
            });
        });
    </script>
@stop