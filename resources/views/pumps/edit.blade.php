@extends('master')

@section('breadcrumbs')
<a href="/pumps" class="breadcrumb hide-on-small-and-down">@choice('components.pumps', 2)</a>
<a href="/pumps/{{ $pump->id }}" class="breadcrumb hide-on-small-and-down">{{ $pump->name }}</a>
<a href="/pumps/{{ $pump->id }}/edit" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/pumps/' . $pump->id) }}" data-method="PUT"
                          >
                        <div class="card-content">
    
                            <span class="card-title activator truncate">
                                <span>{{ $pump->name }}</span>
                            </span>
    
                            <p>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" readonly="readonly" placeholder="ID" name="id" value="{{ $pump->id }}">
                                        <label for="id">ID</label>
                                    </div>
                                </div>
    
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" placeholder="@lang('labels.name')" name="name" value="{{ $pump->name }}">
                                        <label for="name">@lang('labels.name')</label>
                                    </div>
                                </div>
    
                                <div class="row">
                                    <div class="input-field col s12">
                                        <select name="controlunit">
                                            <option></option>
                                            @foreach ($controlunits as $cu)
                                                <option value="{{ $cu->id }}" @if($pump->controlunit_id == $cu->id)selected="selected"@endif>{{ $cu->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="valves">@choice('components.controlunits', 1)</label>
                                    </div>
                                </div>
                            </p>
    
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
                <bus-type-edit-form form-uri="{{ url('api/v1/pumps/' . $pump->id) }}"
                                    physical-sensor-id="{{ $pump->id }}"
                                    bus-type="{{ $pump->property('ControlunitConnectivity', 'bus_type', true) }}"
                                    gpio-pin="{{ $pump->property('ControlunitConnectivity', 'gpio_pin', true) }}"
                                    :gpio-default-high="{{ ($valve->property('ControlunitConnectivity', 'gpio_default_high', true) ? 'true' : 'false') }}"
                                    i2c-address="{{ $pump->property('ControlunitConnectivity', 'i2c_address', true) }}"
                                    i2c-multiplexer-address="{{ $pump->property('ControlunitConnectivity', 'i2c_multiplexer_address', true) }}"
                                    i2c-multiplexer-port="{{ $pump->property('ControlunitConnectivity', 'i2c_multiplexer_port', true) }}">
                </bus-type-edit-form>
            </div>
        </div>
    </div>
    
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/pumps/{{ $pump->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red" href="/pumps/{{ $pump->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/pumps/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop