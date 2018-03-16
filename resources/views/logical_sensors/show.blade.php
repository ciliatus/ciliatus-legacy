@extends('master')

@section('breadcrumbs')
    <a href="/logical_sensors" class="breadcrumb hide-on-small-and-down">@choice('labels.logical_sensors', 2)</a>
    <a href="/logical_sensors/{{ $logical_sensor->id }}" class="breadcrumb hide-on-small-and-down">{{ $logical_sensor->name }}</a>
@stop

@section('content')
    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s3"><a class="active" href="#tab_overview">@lang('labels.overview')</a></li>
        </ul>
    </div>

    <div id="tab_overview" class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12 m5 l4">
                    <div class="row">
                        <logical_sensor-widget logical-sensor-id="{{ $logical_sensor->id }}"
                                               wrapper-classes="col s12"></logical_sensor-widget>
                    </div>

                    <div class="row">
                        <logical_sensor_thresholds-widget :refresh-timeout-seconds="60"
                                                          source-filter="filter[id]={{ $logical_sensor->id }}"
                                                          wrapper-classes="col s12"></logical_sensor_thresholds-widget>
                    </div>
                </div>

                <div class="col s12 m5 l4">
                    <div class="row">
                        <physical_sensor-widget physical-sensor-id="{{ $logical_sensor->physical_sensor_id }}"
                                                wrapper-classes="col s12"></physical_sensor-widget>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large orange darken-4">
                <i class="mdi mdi-18px mdi-pencil"></i>
            </a>
            <ul>
                <li><a class="btn-floating orange tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.edit')"href="/logical_sensors/{{ $logical_sensor->id }}/edit"><i class="mdi mdi-24px mdi-pencil"></i></a></li>
                <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/logical_sensors/{{ $logical_sensor->id }}/delete"><i class="mdi mdi-24px mdi-delete"></i></a></li>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/logical_sensors/create"><i class="mdi mdi-24px mdi-plus"></i></a></li>
            </ul>
        </div>
    </div>
@stop