@extends('master')

@section('breadcrumbs')
    <a href="/logical_sensor_thresholds" class="breadcrumb">@choice('components.logical_sensor_thresholds', 2)</a>
    <a href="/logical_sensor_thresholds/create" class="breadcrumb">@lang('buttons.create')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/logical_sensor_thresholds') }}" data-method="POST" data-redirect-success="auto">
                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="belongsTo">
                                        <option></option>
                                        @foreach ($belongTo_Options as $t=>$objects)
                                            <optgroup label="@choice('components.' . strtolower($t), 2)">
                                                @foreach ($objects as $o)
                                                    <option value="{{ $t }}|{{ $o->id }}"
                                                            @if(isset($preset['belongsTo_type']) && isset($preset['belongsTo_id']))
                                                            @if($preset['belongsTo_type'] == $t && $preset['belongsTo_id'] == $o->id)
                                                            selected
                                                            @endif
                                                            @endif>@if(is_null($o->display_name)) {{ $o->name }} @else {{ $o->display_name }} @endif</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <label for="valves">@lang('labels.belongsTo')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12 m6 l6">
                                    <input type="text" placeholder="@lang('labels.rawlimitlo')" name="lowerlimit" value="">
                                    <label for="name">@lang('labels.rawlimitlo')</label>
                                </div>
                                <div class="input-field col s12 m6 l6">
                                    <input type="text" placeholder="@lang('labels.rawlimithi')" name="upperlimit" value="">
                                    <label for="name">@lang('labels.rawlimithi')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input class="timepicker" placeholder="@lang('labels.starts_at')" name="starts_at" data-default="00:00:00">
                                    <label for="name">@lang('labels.starts_at')</label>
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