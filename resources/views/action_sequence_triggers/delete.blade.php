@extends('master')

@section('breadcrumbs')
    <a href="/action_sequence_triggers" class="breadcrumb hide-on-small-and-down">@choice('components.action_sequence_triggers', 2)</a>
    <a href="/action_sequence_triggers/{{ $action_sequence_trigger->id }}" class="breadcrumb hide-on-small-and-down">{{ $action_sequence_trigger->name }}</a>
    <a href="/action_sequence_triggers/{{ $action_sequence_trigger->id }}/delete" class="breadcrumb hide-on-small-and-down">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/action_sequence_triggers/' . $action_sequence_trigger->id) }}"
                          data-method="DELETE" data-redirect-success="auto">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $action_sequence_trigger->name }}</span>
                            </span>

                            <p>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly placeholder="ID" name="id" value="{{ $action_sequence_trigger->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.name')" readonly name="name" value="{{ $action_sequence_trigger->name }}">
                                    <label for="name">@lang('labels.name')</label>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light red" type="submit">@lang('buttons.delete')
                                        <i class="material-icons right">delete</i>
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