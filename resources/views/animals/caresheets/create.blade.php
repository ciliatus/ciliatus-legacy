@extends('master')

@section('breadcrumbs')
    <a href="/animals" class="breadcrumb hide-on-small-and-down">@choice('components.animals', 2)</a>
    <a href="/animals/caresheets/create" class="breadcrumb hide-on-small-and-down">@lang('buttons.create') @choice('components.caresheet', 1)</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/animals/caresheets') }}" data-method="POST" data-redirect-success="auto">
                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="belongsTo">
                                        <option></option>
                                        @foreach ($belongTo_Options as $t=>$objects)
                                            <optgroup label="@choice('components.' . strtolower($t), 2)">
                                                @foreach ($objects as $o)
                                                    <option value="{{ $t }}|{{ $o->id }}"
                                                            @if(isset($preset['belongsTo_type']) && isset($preset['belongsTo_id']))
                                                            @if($preset['belongsTo_type'] == $t && $preset['belongsTo_id'] == $o->id)
                                                            selected
                                                            @endif
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
                                    <i href="#" class="material-icons tooltipped" data-delay="50" data-html="true"
                                       data-tooltip="<div style='max-width: 300px'>@lang('tooltips.caresheet.sensor_history_days')</div>">info_outline</i>
                                </div>
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.caresheet.sensor_history_days')"
                                           name="sensor_history_days" value="{{ env('DEFAULT_CARESHEET_SENSOR_HISTORY_DAYS', 14) }}">
                                    <label for="sensor_history_days">@lang('labels.caresheet.sensor_history_days')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <i href="#" class="material-icons tooltipped" data-delay="50" data-html="true"
                                       data-tooltip="<div style='max-width: 300px'>@lang('tooltips.caresheet.data_history_days')</div>">info_outline</i>
                                </div>
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.caresheet.data_history_days')"
                                           name="data_history_days" value="{{ env('DEFAULT_CARESHEET_DATA_HISTORY_DAYS', 60) }}">
                                    <label for="data_history_days">@lang('labels.caresheet.data_history_days')</label>
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
        </div>
    </div>
@stop