@extends('master')

@section('breadcrumbs')
    <a href="/action_sequences" class="breadcrumb">@choice('components.action_sequences', 2)</a>
    <a href="{{ url('action_sequences/' . $action_sequence->id) }}" class="breadcrumb">{{ $action_sequence->name }}</a>
    <a href="{{ url('action_sequences/' . $action_sequence->id . '/edit') }}" class="breadcrumb">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="col s12 m12 l6">
        <div class="card">
            <form action="{{ url('api/v1/action_sequences/' . $action_sequence->id) }}" data-method="PUT"
                  data-redirect-success="auto">
                <div class="card-content">

                    <span class="card-title activator grey-text text-darken-4 truncate">
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

                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" placeholder="@choice('units.minutes', 2)" name="duration_minutes" value="{{ $action_sequence->duration_minutes }}">
                            <label for="display_name">@lang('labels.duration') (@choice('units.minutes', 2))</label>
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
    </div>

    <div class="col s12 m12 l6">
        <div class="card">
            <div class="card-content">

                <span class="card-title activator grey-text text-darken-4 truncate">
                    <span>@choice('components.action_sequence_schedules', 2)</span>
                </span>

                <p>

                <div class="row">
                    @foreach($action_sequence->schedules as $ass)
                        <div class="input-field col s12">
                            <li>
                                {{ $ass->starts_at }} @if (!$ass->runonce)<i>@lang('labels.daily')</i>@endif
                                <a class="dropdown-button btn btn-small" href="#" data-activates="dropdown-edit-action_sequence_schedules_{{ $ass->id }}" style="margin-left: 20px">
                                    @lang('labels.actions') <i class="material-icons">keyboard_arrow_down</i>
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

                    <a class="btn-floating btn-large waves-effect waves-light green right" href="/action_sequence_schedules/create?preset[action_sequence]={{ $action_sequence->id }}">
                        <i class="material-icons">add</i>
                    </a>

                </div>

                </p>

            </div>
        </div>
    </div>
@stop
