@extends('master')

@section('breadcrumbs')
    <a href="/logical_sensor_thresholds" class="breadcrumb hide-on-small-and-down">@choice('labels.logical_sensor_thresholds', 2)</a>
@stop


@section('content')
    <div class="container">
        <logical_sensor_thresholds-widget :refresh-timeout-seconds="60" wrapper-classes="col s12 m6 l4"></logical_sensor_thresholds-widget>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/logical_sensor_thresholds/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop