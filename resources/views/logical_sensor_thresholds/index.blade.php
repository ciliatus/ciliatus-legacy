@extends('master')

@section('breadcrumbs')
    <a href="/logical_sensor_thresholds" class="breadcrumb">@choice('components.logical_sensor_thresholds', 2)</a>
@stop


@section('content')
    <logical_sensor_thresholds-widget wrapper-classes="col s12 m6 l4"></logical_sensor_thresholds-widget>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green" href="/logical_sensor_thresholds/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop