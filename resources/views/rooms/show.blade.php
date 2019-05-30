@extends('master')

@section('breadcrumbs')
    <a href="/rooms" class="breadcrumb hide-on-small-and-down">@choice('labels.rooms', 2)</a>
    <a href="/rooms/{{ $room->id }}" class="breadcrumb hide-on-small-and-down">{{ $room->display_name }}</a>
@stop

@section('content')
    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s3"><a class="active" href="#tab_overview">@lang('labels.overview')</a></li>
            <li class="tab col s3"><a href="#tab_terraria">@choice('labels.terraria', 2)</a></li>
            <li class="tab col s3"><a href="#tab_infrastructure">@lang('labels.infrastructure')</a></li>
        </ul>
    </div>

    <div id="tab_overview" class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12 m5 l4">
                    <room-widget :refresh-timeout-seconds="60" room-id="{{ $room->id }}"
                                     :subscribe-add="false" :subscribe-delete="false"
                                     container-classes="row" wrapper-classes="col s12"></room-widget>

                    <logical_sensor_thresholds-widget :refresh-timeout-seconds="60"
                                                      source-filter="filter[physical_sensor.belongsTo_type]=Room&filter[physical_sensor.belongsTo_id]={{ $room->id }}"></logical_sensor_thresholds-widget>

                </div>

                <div class="col s12 m7 l8">
                    <div class="card">
                        <div class="card-header">
                            <i class="mdi mdi-18px mdi-trending-up"></i>
                            @lang('labels.temp_and_hum_history')
                        </div>
                        <div class="card-content">
                            <dygraph-graph show-filter-field="read_at" :show-filter-form="true"
                                           filter-column="read_at"
                                           :columns-axis="{'temperature_celsius': 1, 'humidity_percent': 2}"
                                           labels-div-id="sensorreadings-labels" time-axis-label="@lang('labels.read_at')"
                                           column-id-field="logical_sensor_id" column-name-field="logical_sensor_name"
                                           source="{{ url('api/v1/rooms/' . $room->id . '/sensorreadings') }}"></dygraph-graph>

                            <div id="sensorreadings-labels" class="dygraph-legend-div"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large orange darken-4">
                <i class="mdi mdi-18px mdi-pencil"></i>
            </a>
            <ul>
                <li><a class="btn-floating orange tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.edit')"href="/rooms/{{ $room->id }}/edit"><i class="mdi mdi-24px mdi-pencil"></i></a></li>
                <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/rooms/{{ $room->id }}/delete"><i class="mdi mdi-24px mdi-delete"></i></a></li>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/rooms/create"><i class="mdi mdi-24px mdi-plus"></i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_terraria">
        <div class="container">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <i class="mdi mdi-18px mdi-paw"></i>
                        @choice('labels.terraria', 2)
                    </div>
                    <div class="card-content">
                        <terraria-widget source-filter="filter[room_id]={{ $room->id }}"
                                         :subscribe-add="false" :subscribe-delete="false"
                                         container-classes="row" wrapper-classes="col s12 m6 l4"></terraria-widget>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="tab_infrastructure" class="col s12">
        <div class="container">

            <components-list-widget :refresh-timeout-seconds="60"
                                    source-api-url="rooms/{{ $room->id }}/infrastructure"></components-list-widget>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large orange darken-4">
                <i class="mdi mdi-18px mdi-pencil"></i>
            </a>
            <ul>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/physical_sensors/create"><i class="mdi mdi-24px mdi-plus"></i></a></li>
            </ul>
        </div>
    </div>
@stop