@extends('master')

@section('breadcrumbs')
    <a href="/action_sequence_schedules" class="breadcrumb">@choice('components.action_sequence_schedules', 2)</a>
    <a href="/action_sequence_schedules/create" class="breadcrumb">@lang('buttons.create')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/action_sequence_schedules') }}" data-method="POST"
                          data-redirect-success="auto">
                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="action_sequence">
                                        <optgroup label="@choice('components.action_sequences', 1)">
                                        @foreach ($action_sequences as $as)
                                            <option value="{{ $as->id }}"
                                                @if(isset($preset['action_sequence']) && $preset['action_sequence'] == $as->id) selected @endif >@if(is_null($as->display_name)){{ $as->name }}@else{{ $as->display_name }}@endif
                                            </option>
                                        @endforeach
                                        </optgroup>
                                    </select>
                                    <label for="action_sequence">@lang('labels.action_sequences')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input class="timepicker" placeholder="@lang('labels.starts_at')" name="starts_at" data-default="00:00:00">
                                    <label for="starts_at">@lang('labels.starts_at')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <div class="switch">
                                        <label>
                                            @lang('labels.off')
                                            <input name="runonce" type="checkbox">
                                            <span class="lever"></span>
                                            @lang('labels.on') @lang('labels.runonce')
                                        </label>
                                    </div>
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
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.next')
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
