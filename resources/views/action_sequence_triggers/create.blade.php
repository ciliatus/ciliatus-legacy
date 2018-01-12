@extends('master')

@section('breadcrumbs')
    <a href="/action_sequence_triggers" class="breadcrumb hide-on-small-and-down">@choice('components.action_sequence_triggers', 2)</a>
    <a href="/action_sequence_triggers/create" class="breadcrumb hide-on-small-and-down">@lang('buttons.create')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/action_sequence_triggers') }}" data-method="POST"
                          data-redirect-success="auto">
                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="action_sequence">
                                        @foreach ($action_sequences as $as)
                                            <option value="{{ $as->id }}"
                                                @if(isset($preset['action_sequence']) && $preset['action_sequence'] == $as->id) selected @endif >@if(is_null($as->display_name)){{ $as->name }}@else{{ $as->display_name }}@endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="action_sequence">@choice('components.action_sequences', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12 m6 l6">
                                    <input class="timepicker" placeholder="@lang('labels.timeframe_start')"
                                           name="timeframe_start" data-default="08:00:00" value="08:00:00">
                                    <label for="timeframe_start">@lang('labels.timeframe_start')</label>
                                </div>
                                <div class="input-field col s12 m6 l6">
                                    <input class="timepicker" placeholder="@lang('labels.timeframe_end')"
                                           name="timeframe_end" data-default="20:00:00" value="20:00:00">
                                    <label for="timeframe_end">@lang('labels.timeframe_end')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="logical_sensor">
                                        @foreach ($logical_sensors as $ls)
                                            <option value="{{ $ls->id }}">{{ $ls->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="action_sequence">@choice('components.logical_sensors', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12 m3 l2">
                                    <select name="reference_value_comparison_type">
                                        <option value="lesser"><</option>
                                        <option value="greater">></option>
                                        <option value="equal">=</option>
                                    </select>
                                    <label for="action_sequence"> </label>
                                </div>
                                <div class="input-field col s12 m9 l10">
                                    <input type="text" name="reference_value" placeholder="@lang('labels.reference_value')">
                                    <label for="reference_value">
                                        @lang('labels.reference_value')
                                        <a href="#!" class="material-icons black-text tooltipped" data-position="top"
                                           data-delay="50" data-html="true" data-tooltip="<div style='max-width: 300px'>@lang('tooltips.reference_value')</div>">info_outline</a>
                                    </label>
                                </div>
                            </div>


                            <div class="row">
                                <div class="input-field col s12 m7 l8">
                                    <input type="text" name="reference_value_duration_threshold_minutes" placeholder="@lang('labels.reference_value_duration_threshold_minutes')">
                                    <label for="reference_value_duration_threshold_minutes">
                                        @lang('labels.reference_value_duration_threshold_minutes')
                                        <a href="#!" class="material-icons black-text tooltipped" data-position="top"
                                           data-delay="50" data-html="true" data-tooltip="<div style='max-width: 300px'>@lang('tooltips.reference_value_duration_threshold_minutes')</div>">info_outline</a>
                                    </label>
                                </div>
                                <div class="input-field col s12 m5 l4">
                                    <input type="text" name="minimum_timeout_minutes" placeholder="@lang('labels.minimum_timeout_minutes')">
                                    <label for="minimum_timeout_minutes">
                                        @lang('labels.minimum_timeout_minutes')
                                        <a href="#!" class="material-icons black-text tooltipped" data-position="top"
                                           data-delay="50" data-html="true" data-tooltip="<div style='max-width: 300px'>@lang('tooltips.minimum_timeout_minutes')</div>">info_outline</a>
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
