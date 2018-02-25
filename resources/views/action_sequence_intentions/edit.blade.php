@extends('master')

@section('breadcrumbs')
    <a href="/action_sequence_intentions" class="breadcrumb hide-on-small-and-down">@choice('labels.action_sequence_intentions', 2)</a>
    <a href="/action_sequence_intentions/{{ $action_sequence_intention->id }}" class="breadcrumb hide-on-small-and-down">{{ $action_sequence_intention->name }}</a>
    <a href="/action_sequence_intentions/{{ $action_sequence_intention->id }}/edit" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/action_sequence_intentions/' . $action_sequence_intention->id) }}" data-method="PUT"
                          >
                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="action_sequence" disabled>
                                        @foreach ($action_sequences as $as)
                                            <option value="{{ $as->id }}"
                                                    @if($action_sequence_intention->sequence->id == $as->id) selected @endif >@if(is_null($as->display_name)){{ $as->name }}@else{{ $as->display_name }}@endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="action_sequence">@choice('labels.action_sequences', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12 m6 l6">
                                    <input class="timepicker" placeholder="@lang('labels.timeframe_start')"
                                           name="timeframe_start" data-default="{{ $action_sequence_intention->timeframe_start }}"
                                           value="{{ $action_sequence_intention->timeframe_start }}">
                                    <label for="timeframe_start">@lang('labels.timeframe_start')</label>
                                </div>
                                <div class="input-field col s12 m6 l6">
                                    <input class="timepicker" placeholder="@lang('labels.timeframe_end')"
                                           name="timeframe_end" data-default="{{ $action_sequence_intention->timeframe_end }}"
                                           value="{{ $action_sequence_intention->timeframe_end }}">
                                    <label for="timeframe_end">@lang('labels.timeframe_end')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12 m6 l6 tooltipped" data-position="top"
                                     data-delay="50" data-html="true" data-tooltip="<div style='max-width: 300px'>@lang('tooltips.intention_increase_decrease')</div>">
                                    <select name="intention">
                                        <option value="increase" @if($action_sequence_intention->intention == 'increase') selected @endif>@lang('labels.increase')</option>
                                        <option value="decrease" @if($action_sequence_intention->intention == 'decrease') selected @endif>@lang('labels.decrease')</option>
                                    </select>
                                    <label for="intention">
                                        @lang('labels.intention')
                                    </label>
                                </div>
                                <div class="input-field col s12 m6 l6">
                                    <select name="type">
                                        @foreach ($sensorreading_types as $srt)
                                            <option value="{{ $srt }}" @if($action_sequence_intention->type == $srt) selected @endif>@lang('labels.' . $srt)</option>
                                        @endforeach
                                    </select>
                                    <label for="type">@choice('labels.logical_sensors', 1)</label>
                                </div>
                            </div>


                            <div class="row">
                                <div class="input-field col s12 tooltipped" data-position="top"
                                     data-delay="50" data-html="true" data-tooltip="<div style='max-width: 300px'>@lang('tooltips.minimum_timeout_minutes')</div>">
                                    <input type="text" name="minimum_timeout_minutes"
                                           placeholder="@lang('tooltips.minimum_timeout_minutes')"
                                           value="{{ $action_sequence_intention->minimum_timeout_minutes }}">
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
