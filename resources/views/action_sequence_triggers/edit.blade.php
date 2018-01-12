@extends('master')

@section('breadcrumbs')
    <a href="/action_sequence_triggers" class="breadcrumb hide-on-small-and-down">@choice('components.action_sequence_triggers', 2)</a>
    <a href="/action_sequence_triggers/{{ $action_sequence_trigger->id }}" class="breadcrumb hide-on-small-and-down">{{ $action_sequence_trigger->name }}</a>
    <a href="/action_sequence_triggers/{{ $action_sequence_trigger->id }}/edit" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/action_sequence_triggers/' . $action_sequence_trigger->id) }}" data-method="PUT"
                          >
                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="action_sequence" disabled>
                                        @foreach ($action_sequences as $as)
                                            <option value="{{ $as->id }}"
                                                    @if($action_sequence_trigger->id == $as->id) selected @endif >@if(is_null($as->display_name)){{ $as->name }}@else{{ $as->display_name }}@endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="action_sequence">@choice('components.action_sequences', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12 m6 l6">
                                    <input class="timepicker" placeholder="@lang('labels.timeframe_start')"
                                           name="timeframe_start" data-default="{{ $action_sequence_trigger->timeframe_start }}"
                                           value="{{ $action_sequence_trigger->timeframe_start }}">
                                    <label for="timeframe_start">@lang('labels.timeframe_start')</label>
                                </div>
                                <div class="input-field col s12 m6 l6">
                                    <input class="timepicker" placeholder="@lang('labels.timeframe_end')"
                                           name="timeframe_end" data-default="{{ $action_sequence_trigger->timeframe_end }}"
                                           value="{{ $action_sequence_trigger->timeframe_end }}">
                                    <label for="timeframe_end">@lang('labels.timeframe_end')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="logical_sensor">
                                        @foreach ($logical_sensors as $ls)
                                            <option value="{{ $ls->id }}" @if($action_sequence_trigger->logical_sensor_id == $ls->id) selected @endif>{{ $ls->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="action_sequence">@choice('components.logical_sensors', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12 m3 l2">
                                    <select name="reference_value_comparison_type">
                                        <option value="lesser" @if($action_sequence_trigger->reference_value_comparison_type == 'lesser') selected @endif><</option>
                                        <option value="greater" @if($action_sequence_trigger->reference_value_comparison_type == 'greater') selected @endif>></option>
                                        <option value="equal" @if($action_sequence_trigger->reference_value_comparison_type == 'equal') selected @endif>=</option>
                                    </select>
                                    <label for="action_sequence"> </label>
                                </div>
                                <div class="input-field col s12 m9 l10 tooltipped" data-position="top"
                                     data-delay="50" data-html="true" data-tooltip="<div style='max-width: 300px'>@lang('tooltips.reference_value')</div>">
                                    <input type="text" name="reference_value" value="{{ $action_sequence_trigger->reference_value }}">
                                    <label for="reference_value">
                                        @lang('labels.reference_value')
                                    </label>
                                </div>
                            </div>


                            <div class="row">
                                <div class="input-field col s12 m7 l8 tooltipped" data-position="top"
                                     data-delay="50" data-html="true" data-tooltip="<div style='max-width: 300px'>@lang('tooltips.reference_value_duration_threshold_minutes')</div>">
                                    <input type="text" name="reference_value_duration_threshold_minutes"
                                           placeholder="@lang('tooltips.reference_value_duration_threshold_minutes')"
                                           value="{{ $action_sequence_trigger->reference_value_duration_threshold_minutes }}">
                                    <label for="reference_value_duration_threshold_minutes">
                                        @lang('labels.reference_value_duration_threshold_minutes')
                                    </label>
                                </div>
                                <div class="input-field col s12 m5 l4 tooltipped" data-position="top"
                                     data-delay="50" data-html="true" data-tooltip="<div style='max-width: 300px'>@lang('tooltips.minimum_timeout_minutes')</div>">
                                    <input type="text" name="minimum_timeout_minutes"
                                           placeholder="@lang('tooltips.minimum_timeout_minutes')"
                                           value="{{ $action_sequence_trigger->minimum_timeout_minutes }}">
                                    <label for="minimum_timeout_minutes">
                                        @lang('labels.minimum_timeout_minutes')
                                    </label>
                                </div>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    $('.timepicker').pickatime({
                                        twelvehour: false
                                    });
                                });
                            </script>

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
