@extends('master')

@section('breadcrumbs')
    <a href="/physical_sensors" class="breadcrumb hide-on-small-and-down">@choice('labels.physical_sensors', 2)</a>
    <a href="/physical_sensors/{{ $physical_sensor->id }}" class="breadcrumb hide-on-small-and-down">{{ $physical_sensor->name }}</a>
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
                        <physical_sensor-widget physical-sensor-id="{{ $physical_sensor->id }}"
                                                wrapper-classes="col s12"></physical_sensor-widget>
                    </div>
                </div>

                <div class="col s12 m7 l8">
                    @if(!is_null($physical_sensor->controlunit))
                    <div class="row">
                        <controlunit-widget controlunit-id="{{ $physical_sensor->controlunit_id }}"
                                            wrapper-classes="col s12"></controlunit-widget>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col s12">
                            <div class="card">
                                <div class="card-header">
                                    <i class="material-icons">memory</i>
                                    @choice('labels.logical_sensors', 2)
                                </div>
                                <div class="card-content">
                                    <logical_sensors-list-widget :refresh-timeout-seconds="60" source-filter="filter[physical_sensor_id]={{ $physical_sensor->id }}"
                                                                 :hide-cols="['physical_sensor']" :subscribe-add="false"></logical_sensors-list-widget>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large orange darken-4">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating orange tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.edit')"href="/physical_sensors/{{ $physical_sensor->id }}/edit"><i class="material-icons">edit</i></a></li>
                <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/physical_sensors/{{ $physical_sensor->id }}/delete"><i class="material-icons">delete</i></a></li>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/physical_sensors/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

@stop