@extends('master')

@section('breadcrumbs')
<a href="/logical_sensor_thresholds" class="breadcrumb">@choice('components.logical_sensor_thresholds', 2)</a>
<a href="/logical_sensor_thresholds/{{ $logical_sensor_threshold->id }}" class="breadcrumb">{{ $logical_sensor_threshold->name }}</a>
<a href="/logical_sensor_thresholds/{{ $logical_sensor_threshold->id }}/edit" class="breadcrumb">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id) }}" data-method="PUT"
                          data-redirect-success="auto">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $logical_sensor_threshold->name }}</span>

                            </span>

                            <p>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" readonly="readonly" placeholder="ID" name="id" value="{{ $logical_sensor_threshold->id }}">
                                        <label for="id">ID</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <select name="belongsTo">
                                            <option></option>
                                            @foreach ($belongTo_Options as $t=>$objects)
                                                <optgroup label="@choice('components.' . strtolower($t), 2)">
                                                    @foreach ($objects as $o)
                                                        <option value="{{ $t }}|{{ $o->id }}"
                                                            @if($logical_sensor_threshold->logical_sensor_id == $o->id)
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
                                    <div class="input-field col s12 m6 l6">
                                        <input type="text" placeholder="@lang('labels.rawlimitlo')" name="lowerlimit"
                                               value="{{ $logical_sensor_threshold->rawvalue_lowerlimit }}">
                                        <label for="name">@lang('labels.rawlimitlo')</label>
                                    </div>
                                    <div class="input-field col s12 m6 l6">
                                        <input type="text" placeholder="@lang('labels.rawlimithi')" name="upperlimit"
                                               value="{{ $logical_sensor_threshold->rawvalue_upperlimit }}">
                                        <label for="name">@lang('labels.rawlimithi')</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input class="timepicker" placeholder="@lang('labels.starts_at')" name="starts_at"
                                               data-default="{{ $logical_sensor_threshold->starts_at }}" value="{{ $logical_sensor_threshold->starts_at }}">
                                        <label for="name">@lang('labels.starts_at')</label>
                                    </div>
                                </div>

                                <script>
                                    $(document).ready(function() {
                                        $('.timepicker').pickatime({
                                            twelvehour: false
                                        });
                                    });
                                </script>
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
        </div>
    </div>
    
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/logical_sensor_thresholds/{{ $logical_sensor_threshold->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red" href="/logical_sensor_thresholds/{{ $logical_sensor_threshold->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/logical_sensor_thresholds/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop