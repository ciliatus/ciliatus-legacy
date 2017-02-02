@extends('master')

@section('breadcrumbs')
    <a href="/actions" class="breadcrumb">@choice('components.actions', 2)</a>
    <a href="/actions/{{ $action->id }}" class="breadcrumb">{{ $action->name }}</a>
    <a href="/actions/{{ $action->id }}/delete" class="breadcrumb">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/actions/' . $action->id) }}"
                          data-method="DELETE" data-redirect-success="/action_sequences/{{ $action->sequence->id }}/edit">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $action->name }}</span>
                            </span>

                            <p>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly placeholder="ID" name="id" value="{{ $action->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <i class="material-icons">{{ $action->target_object()->icon() }}</i> <a href="{{ $action->target_object()->url() }}">{{ $action->target_object()->name }}</a>
                                    <i class="material-icons">keyboard_arrow_right</i>
                                    {{ $action->desired_state }} <i>{{ $action->duration_minutes }} @choice('units.minutes', $action->duration_minutes)</i>
                                    @if (!is_null($action->wait_for_started_action_object()))
                                        @lang('labels.starts_after') [{{ $action->wait_for_started_action_object()->sequence_sort_id }}]
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light red" type="submit">@lang('buttons.delete')
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
@stop