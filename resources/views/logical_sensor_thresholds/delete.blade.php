@extends('master')

@section('breadcrumbs')
    <a href="/logical_sensor_thresholds" class="breadcrumb">@choice('components.logical_sensor_thresholds', 2)</a>
    <a href="/logical_sensor_thresholds/{{ $logical_sensor_threshold->id }}" class="breadcrumb">{{ $logical_sensor_threshold->name }}</a>
    <a href="/logical_sensor_thresholds/{{ $logical_sensor_threshold->id }}/delete" class="breadcrumb">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id) }}"
                          data-method="DELETE" data-redirect-success="auto">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $logical_sensor_threshold->name }}</span>
                            </span>

                            <p>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly placeholder="ID" name="id" value="{{ $logical_sensor_threshold->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.name')" readonly name="display_name" value="{{ $logical_sensor_threshold->name }}">
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