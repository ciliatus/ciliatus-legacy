@extends('master')

@section('breadcrumbs')
    <a href="/logical_sensor_thresholds" class="breadcrumb">@choice('components.logical_sensor_thresholds', 2)</a>
    <a href="/logical_sensor_thresholds/{{ $logical_sensor_threshold->id }}" class="breadcrumb">{{ $logical_sensor_threshold->name }}</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <logical_sensor_thresholds-widget :refresh-timeout-seconds="60" logical_sensor_threshold-id="{{ $logical_sensor_threshold->id }}"
                                              :subscribe-add="false" :subscribe-delete="false"></logical_sensor_thresholds-widget>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating orange" href="/logical_sensor_thresholds/{{ $logical_sensor_threshold->id }}/edit"><i class="material-icons">edit</i></a></li>
            <li><a class="btn-floating red" href="/logical_sensor_thresholds/{{ $logical_sensor_threshold->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/logical_sensor_thresholds/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop