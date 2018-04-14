@extends('master')

@section('breadcrumbs')
    <a href="/action_sequence_schedules" class="breadcrumb hide-on-small-and-down">@choice('labels.action_sequence_schedules', 2)</a>
    <a href="{{ url('action_sequence_schedules/' . $action_sequence_schedule->id) }}" class="breadcrumb hide-on-small-and-down">{{ $action_sequence_schedule->name }}</a>
    <a href="{{ url('action_sequence_schedules/' . $action_sequence_schedule->id . '/edit') }}" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/action_sequence_schedules/' . $action_sequence_schedule->id) }}" data-method="PUT"
                          >
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $action_sequence_schedule->name }}</span>
                            </span>

                            <input type="text" name="id" value="{{ $action_sequence_schedule->id }}" hidden>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="action_sequence">
                                        <optgroup label="@choice('labels.action_sequences', 1)">
                                            @foreach ($action_sequences as $as)
                                                <option value="{{ $as->id }}"
                                                        @if($action_sequence_schedule->action_sequence_id == $as->id) selected @endif >@if(is_null($as->display_name)){{ $as->name }}@else{{ $as->display_name }}@endif
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    <label for="action_sequence">@choice('labels.action_sequences', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input placeholder="@lang('labels.name')" name="name" type="text" value="{{ $action_sequence_schedule->name }}">
                                    <label for="name">@lang('labels.name')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input class="timepicker" placeholder="@lang('labels.starts_at')" name="starts_at"
                                           value="{{ $action_sequence_schedule->starts_at }}" data-default="{{ $action_sequence_schedule->starts_at }}">
                                    <label for="starts_at">@lang('labels.starts_at')</label>
                                </div>
                            </div>

                            <div class="row">
                                @foreach($weekdays as $num=>$weekday)
                                <div class="col s12">
                                    <input type="checkbox"
                                           id="weekday-{{ $num }}"
                                           name="weekday_{{ $num }}"
                                           @if($action_sequence_schedule->runsOnWeekday($num)) checked @endif />
                                    <label for="weekday-{{ $num }}">@lang('weekdays.' . $weekday)</label>
                                </div>
                                @endforeach
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