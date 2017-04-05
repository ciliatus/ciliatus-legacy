@extends('master')

@section('breadcrumbs')
    <a href="/actions" class="breadcrumb hide-on-small-and-down">@choice('components.actions', 2)</a>
    <a href="/actions/create" class="breadcrumb hide-on-small-and-down">@lang('buttons.create')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/actions') }}" data-method="POST" data-redirect-success="auto">
                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="action_sequence">
                                        <option></option>
                                        @foreach ($action_sequences as $sequence)
                                            <option value="{{ $sequence->id }}" @if($preset['action_sequence_id'] == $sequence->id) selected @endif>{{ $sequence->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="action_sequence">@choice('components.action_sequence', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="component" id="component_select">
                                        <option></option>
                                        @foreach ($components as $type)
                                            <optgroup label="{{ $type['display_name'] }}">
                                                @foreach ($type['objects'] as $o)
                                                    <option value="{{ $type['tech_name'] }}|{{ $o['id'] }}" data-states="{{ json_encode($o['states']) }}">{{ $o['name'] }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <label for="component">@lang('labels.component')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="state" id="state_select">
                                    </select>
                                    <label for="state">@lang('labels.state')</label>
                                </div>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    $('#component_select').change(function(e) {
                                        $('#state_select option').remove();
                                        $('#component_select').find(':selected').first().data('states').forEach(function(el) {
                                            $('#state_select').append('<option value="' + el + '">' + el + '</option>');
                                        });
                                        $('#state_select').material_select();
                                    });

                                });
                            </script>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" name="duration" value="10">
                                    <label for="duration">@lang('labels.duration')/@choice('units.minutes', 2)</label>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="material-icons right">save</i>
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