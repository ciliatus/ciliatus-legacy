@extends('master')

@section('breadcrumbs')
    <a href="/action_sequences" class="breadcrumb hide-on-small-and-down">@choice('labels.action_sequence', 2)</a>
    <a href="/action_sequences/{{ $action->sequence->id }}/edit" class="breadcrumb hide-on-small-and-down">{{ $action->sequence->name }}</a>
    <a href="/actions/{{ $action->id }}/edit" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit') @choice('labels.actions', 1)</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/actions/' . $action->id) }}" data-method="PUT"
                          data-redirect-success="{{ url('action_sequences/' . $action->sequence->id . '/edit') }}">
                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="action_sequence" disabled>
                                        <option></option>
                                        @foreach ($action_sequences as $sequence)
                                            <option value="{{ $sequence->id }}" @if($action->sequence->id == $sequence->id) selected @endif>{{ $sequence->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="action_sequence">@choice('labels.action_sequence', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="component" id="component_select">
                                        <option></option>
                                        @foreach ($components as $type)
                                            <optgroup label="{{ $type['display_name'] }}">
                                                @foreach ($type['objects'] as $o)
                                                    <option value="{{ $type['tech_name'] }}|{{ $o['id'] }}"
                                                            data-states="{{ json_encode($o['states']) }}"
                                                            @if($type['tech_name'] == $action->target_type && $o['id'] == $action->target_id) selected @endif>{{ $o['name'] }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <label for="component">@lang('labels.component')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="desired_state" id="state_select">
                                    </select>
                                    <label for="state">@lang('labels.state')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" name="duration_minutes" value="{{ $action->duration_minutes }}">
                                    <label for="duration">@lang('labels.duration')/@choice('units.minutes', 2)</label>
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
            <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/actions/{{ $action->id }}/delete"><i class="mdi mdi-24px mdi-delete"></i></a></li>
        </ul>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#component_select').change(function(e) {
                $('#state_select option').remove();
                var selected_state = '{{ $action->desired_state }}';
                $('#component_select').find(':selected').first().data('states').forEach(function(el) {
                    if (selected_state === el) {
                        $('#state_select').append('<option value="' + el + '" selected>' + el + '</option>');
                    }
                    else {
                        $('#state_select').append('<option value="' + el + '">' + el + '</option>');
                    }
                });
                $('#state_select').formSelect();
            });

            $('#component_select').change();
        });
    </script>
@stop