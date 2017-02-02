@extends('master')

@section('breadcrumbs')
    <a href="/action_sequences" class="breadcrumb">@choice('components.action_sequences', 2)</a>
    <a href="{{ url('action_sequences/' . $action_sequence->id) }}" class="breadcrumb">{{ $action_sequence->name }}</a>
    <a href="{{ url('action_sequences/' . $action_sequence->id . '/edit') }}" class="breadcrumb">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/action_sequences/' . $action_sequence->id) }}" data-method="PUT"
                          data-redirect-success="auto">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $action_sequence->name }}</span>
                            </span>

                            <input type="text" name="id" value="{{ $action_sequence->id }}" hidden>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="terrarium">
                                        <optgroup label="@choice('components.terraria', 1)">
                                            @foreach ($terraria as $t)
                                                <option value="{{ $t->id }}"
                                                        @if($action_sequence->terrarium_id == $t->id) selected @endif >@if(is_null($t->display_name)){{ $t->name }}@else{{ $t->display_name }}@endif
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    <label for="valves">@lang('labels.terrarium')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.name')" name="name" value="{{ $action_sequence->name }}">
                                    <label for="display_name">@lang('labels.name')</label>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.next')
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-content teal lighten-1 white-text">
                        <span class="activator truncate">
                            <span><i class="material-icons">assignment</i> @choice('components.actions', 2)</span>
                        </span>
                    </div>

                    <div class="card-content">

                        <div class="row">
                            @foreach($action_sequence->actions as $a)
                                <div class="input-field col s12">
                                    [{{ $a->sequence_sort_id }}]
                                    <i class="material-icons">{{ $a->target_object()->icon() }}</i> <a href="{{ $a->target_object()->url() }}">{{ $a->target_object()->name }}</a>
                                    <i class="material-icons">keyboard_arrow_right</i>
                                    {{ $a->desired_state }} <i>{{ $a->duration_minutes }} @choice('units.minutes', $a->duration_minutes)</i>
                                    @if (!is_null($a->wait_for_started_action_object()))
                                        @lang('labels.starts_after') [{{ $a->wait_for_started_action_object()->sequence_sort_id }}]
                                    @endif

                                    <a class="dropdown-button btn btn-small btn-icon-only" href="#" data-activates="dropdown-edit-actions_{{ $a->id }}">
                                        <i class="material-icons">settings</i>
                                    </a>

                                    <ul id="dropdown-edit-actions_{{ $a->id }}" class="dropdown-content">
                                        <li>
                                            <a href="{{ url('actions/' . $a->id . '/edit') }}">
                                                @lang('buttons.edit')
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('actions/' . $a->id . '/delete') }}">
                                                @lang('buttons.delete')
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endforeach

                        </div>

                    </div>

                    <div class="card-action">
                        <a href="/actions/create?preset[action_sequence_id]={{ $action_sequence->id }}">
                            @lang('buttons.add')
                        </a>
                    </div>
                </div>
            </div>

            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content teal lighten-1 white-text">
                        <span class="activator truncate">
                            <span><i class="material-icons">schedule</i> @choice('components.action_sequence_schedules', 2)</span>
                        </span>
                    </div>

                    <div class="card-content">

                        <div class="row">
                            @foreach($action_sequence->schedules as $ass)
                                <div class="input-field col s12">
                                    <li>
                                        {{ $ass->starts_at }} @if (!$ass->runonce)<i>@lang('labels.daily')</i>@endif
                                        <a class="dropdown-button btn btn-small btn-icon-only" href="#" data-activates="dropdown-edit-action_sequence_schedules_{{ $ass->id }}">
                                            <i class="material-icons">settings</i>
                                        </a>

                                        <ul id="dropdown-edit-action_sequence_schedules_{{ $ass->id }}" class="dropdown-content">
                                            <li>
                                                <a href="{{ url('action_sequence_schedules/' . $ass->id . '/edit') }}">
                                                    @lang('buttons.edit')
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ url('action_sequence_schedules/' . $ass->id . '/delete') }}">
                                                    @lang('buttons.delete')
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </div>
                            @endforeach

                        </div>

                    </div>

                    <div class="card-action">
                        <a href="/action_sequence_schedules/create?preset[action_sequence]={{ $action_sequence->id }}">
                            @lang('buttons.add')
                        </a>
                    </div>

                </div>

                <div class="card">
                    <div class="card-content teal lighten-1 white-text">
                        <span class="activator truncate">
                            <span><i class="material-icons">flare</i> @choice('components.action_sequence_triggers', 2)</span>
                        </span>
                    </div>

                    <div class="card-content">

                        <div class="row">
                            @foreach($action_sequence->triggers as $trigger)
                                <div class="input-field col s12">
                                    <li>
                                        <span style="width: calc(100% - 60px); display: inline-block">
                                            <a href="/logical_sensors/{{ $trigger->logical_sensor->id }}">{{ $trigger->logical_sensor->name }}</a>
                                            @lang('units.' . $trigger->reference_value_comparison_type) {{ $trigger->reference_value }}

                                            @lang('labels.for') {{ $trigger->reference_value_duration_threshold_minutes }} @choice('units.minutes', $trigger->reference_value_duration_threshold_minutes)
                                            <i>{{ $trigger->timeframe_start }} - {{ $trigger->timeframe_end }}</i>
                                        </span>

                                        <a style="margin: 0" class="dropdown-button btn btn-small btn-icon-only" href="#" data-activates="dropdown-edit-action_sequence_triggers_{{ $trigger->id }}">
                                            <i class="material-icons">settings</i>
                                        </a>

                                        <ul id="dropdown-edit-action_sequence_triggers_{{ $trigger->id }}" class="dropdown-content">
                                            <li>
                                                <a href="{{ url('action_sequence_triggers/' . $trigger->id . '/edit') }}">
                                                    @lang('buttons.edit')
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ url('action_sequence_triggers/' . $trigger->id . '/delete') }}">
                                                    @lang('buttons.delete')
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </div>
                            @endforeach

                        </div>

                    </div>

                    <div class="card-action">
                        <a href="/action_sequence_triggers/create?preset[action_sequence]={{ $action_sequence->id }}">
                            @lang('buttons.add')
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/action_sequences/{{ $action_sequence->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red" href="/action_sequences/{{ $action_sequence->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/action_sequences/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop
