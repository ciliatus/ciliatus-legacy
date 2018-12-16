@extends('master')

@section('breadcrumbs')
<a href="/logical_sensor_thresholds" class="breadcrumb hide-on-small-and-down">@choice('labels.logical_sensor_thresholds', 2)</a>
<a href="/logical_sensor_thresholds/{{ $logical_sensor_threshold->id }}" class="breadcrumb hide-on-small-and-down">{{ $logical_sensor_threshold->name }}</a>
<a href="/logical_sensor_thresholds/{{ $logical_sensor_threshold->id }}/edit" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id) }}" data-method="PUT"
                          >
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $logical_sensor_threshold->name }}</span>
                            </span>

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
                                            <optgroup label="@choice('labels.' . $t, 2)">
                                                @foreach ($objects as $o)
                                                    <option value="{{ $t }}|{{ $o->id }}"
                                                        @if($logical_sensor_threshold->logical_sensor_id == $o->id)
                                                            selected
                                                        @endif>@if(is_null($o->display_name)) {{ $o->name }} @else {{ $o->display_name }} @endif</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <label for="belongsTo">@lang('labels.belongsTo')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <strong class="tooltipped" data-delay="50" data-html="true"
                                            :data-tooltip="'<div style=\'max-width: 300px\'>' + $t('tooltips.logical_sensor_thresholds.limits') + '</div>'">
                                        @lang('labels.rawlimits')
                                        <i class="mdi mdi-18px mdi-information-outline"></i>
                                    </strong>
                                </div>
                                <div class="input-field col s12 m6 l6">
                                    <input type="text" placeholder="@lang('labels.rawlimitlo')" name="lowerlimit"
                                           value="{{ $logical_sensor_threshold->adjusted_value_lowerlimit }}">
                                    <label for="lowerlimit">@lang('labels.rawlimitlo')</label>
                                </div>
                                <div class="input-field col s12 m6 l6">
                                    <input type="text" placeholder="@lang('labels.rawlimithi')" name="upperlimit"
                                           value="{{ $logical_sensor_threshold->adjusted_value_upperlimit }}">
                                    <label for="upperlimit">@lang('labels.rawlimithi')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input class="timepicker" placeholder="@lang('labels.starts_at')" name="starts_at"
                                           data-default="{{ $logical_sensor_threshold->starts_at }}" value="{{ $logical_sensor_threshold->starts_at }}">
                                    <label for="starts_at">@lang('labels.starts_at')</label>
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
            <li><a class="btn-floating teal" href="/logical_sensor_thresholds/{{ $logical_sensor_threshold->id }}"><i class="mdi mdi-18px mdi-information-outline"></i></a></li>
            <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/logical_sensor_thresholds/{{ $logical_sensor_threshold->id }}/delete"><i class="mdi mdi-24px mdi-delete"></i></a></li>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/logical_sensor_thresholds/create"><i class="mdi mdi-24px mdi-plus"></i></a></li>
        </ul>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.timepicker').timepicker({
                twelveHour: false
            });
        });
    </script>
@stop