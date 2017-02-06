@extends('master')

@section('breadcrumbs')
    <a href="/physical_sensors" class="breadcrumb">@choice('components.physical_sensors', 2)</a>
    <a href="/physical_sensors/{{ $physical_sensor->id }}" class="breadcrumb">{{ $physical_sensor->name }}</a>
@stop

@section('content')
    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s3"><a class="active" href="#tab_overview">@lang('labels.overview')</a></li>
            <li class="tab col s3"><a class="active" href="#tab_logical_sensors">@choice('components.logical_sensors', 2)</a></li>
            <li class="tab col s3"><a class="active" href="#tab_belongsTo">@lang('labels.belongsTo')</a></li>
        </ul>
    </div>

    <div id="tab_overview" class="col s12">
        <div class="container">
            <div class="row">
                <physical_sensors-widget :refresh-timeout-seconds="60" physical_sensor-id="{{ $physical_sensor->id }}"
                                         container-classes="col s12 m6 l4" wrapper-classes=""
                                         :subscribe-add="false" :subscribe-delete="false"></physical_sensors-widget>
            </div>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large teal">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating orange" href="/physical_sensors/{{ $physical_sensor->id }}/edit"><i class="material-icons">edit</i></a></li>
                <li><a class="btn-floating red" href="/physical_sensors/{{ $physical_sensor->id }}/delete"><i class="material-icons">delete</i></a></li>
                <li><a class="btn-floating green" href="/physical_sensors/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_belongsTo" class="col s12">
        <div class="container">
            <div class="row">
                <controlunits-widget :refresh-timeout-seconds="60" controlunit-id="{{ $physical_sensor->controlunit_id }}"
                                     container-classes="col s12 m6 l4" wrapper-classes=""
                                     :subscribe-add="false" :subscribe-delete="false"></controlunits-widget>
            </div>
        </div>
    </div>

    <div id="tab_logical_sensors" class="col s12">
        <div class="container">
            <logical_sensors-widget :refresh-timeout-seconds="60" source-filter="filter[physical_sensor_id]={{ $physical_sensor->id }}"
                                    container-classes="row" wrapper-classes="col s12 m6 l4"
                                    :subscribe-add="false" :subscribe-delete="false"></logical_sensors-widget>
        </div>
    </div>
@stop