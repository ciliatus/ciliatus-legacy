@extends('master')

@section('breadcrumbs')
    <a href="/terraria" class="breadcrumb hide-on-small-and-down">@choice('labels.terraria', 2)</a>
    <a href="/terraria/{{ $terrarium->id }}" class="breadcrumb hide-on-small-and-down">{{ $terrarium->display_name }}</a>
@stop

@section('content')
    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s3"><a class="active" href="#tab_overview">@lang('labels.overview')</a></li>
            <li class="tab col s3"><a href="#tab_infrastructure">@lang('labels.infrastructure')</a></li>
            <li class="tab col s3"><a href="#tab_biography">@lang('labels.biography')</a></li>
            <li class="tab col s3"><a href="#tab_files">@choice('labels.files', 2)</a></li>
        </ul>
    </div>

    <div id="tab_overview" class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12 m5 l4">
                    <terraria-widget :refresh-timeout-seconds="60" terrarium-id="{{ $terrarium->id }}"
                                     :subscribe-add="false" :subscribe-delete="false"
                                     container-classes="row" wrapper-classes="col s12"></terraria-widget>

                    <action_sequences-widget :refresh-timeout-seconds="60" source-filter="filter[terrarium_id]={{ $terrarium->id }}"
                                             terrarium-id="{{ $terrarium->id }}"></action_sequences-widget>

                    <logical_sensor_thresholds-widget :refresh-timeout-seconds="60"
                                                      source-filter="filter[physical_sensor.belongsTo_type]=Terrarium&filter[physical_sensor.belongsTo_id]={{ $terrarium->id }}"></logical_sensor_thresholds-widget>

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
                                           source="{{ url('api/v1/terraria/' . $terrarium->id . '/sensorreadings') }}"></dygraph-graph>

                            <div id="sensorreadings-labels" class="dygraph-legend-div"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <i class="mdi mdi-18px mdi-paw"></i>
                            @choice('labels.animals', 2)
                        </div>
                        <div class="card-content">
                            <animals-widget source-filter="filter[terrarium_id]={{ $terrarium->id }}"
                                            :subscribe-add="false" :subscribe-delete="false"
                                            container-classes="masonry-grid row" wrapper-classes="col s12 m6 l6"></animals-widget>
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
                <li><a class="btn-floating orange tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.edit')"href="/terraria/{{ $terrarium->id }}/edit"><i class="mdi mdi-24px mdi-pencil"></i></a></li>
                <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/terraria/{{ $terrarium->id }}/delete"><i class="mdi mdi-24px mdi-delete"></i></a></li>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/terraria/create"><i class="mdi mdi-24px mdi-plus"></i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_infrastructure" class="col s12">
        <div class="container">

            <components-list-widget :refresh-timeout-seconds="60"
                                    source-api-url="terraria/{{ $terrarium->id }}/infrastructure"></components-list-widget>

            <!--
            <physical_sensors-widget :refresh-timeout-seconds="60" source-filter="filter[belongsTo_type]=Terrarium&filter[belongsTo_id]={{ $terrarium->id }}"
                                     container-classes="row" wrapper-classes="col s12 m6 l4"
                                     :subscribe-add="true" :subscribe-delete="true"></physical_sensors-widget>

            <valves-widget :refresh-timeout-seconds="60" source-filter="filter[terrarium_id]={{ $terrarium->id }}"
                           container-classes="row" wrapper-classes="col s12 m6 l4"
                           :subscribe-add="true" :subscribe-delete="true"></valves-widget>

            <custom_components-widget :refresh-timeout-seconds="60" source-filter="filter[belongsTo_type]=Terrarium&filter[belongsTo_id]={{ $terrarium->id }}"
                           container-classes="row" wrapper-classes="col s12 m6 l4"
                           :subscribe-add="true" :subscribe-delete="true"></custom_components-widget>
                           -->
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

    <div id="tab_biography" class="col s12">
        <div class="container">
            <biography_entries-widget belongs-to-type="Terrarium"
                                      belongs-to-id="{{ $terrarium->id }}"
                                      wrapper-classes="row"></biography_entries-widget>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large orange darken-4">
                <i class="mdi mdi-18px mdi-pencil"></i>
            </a>
            <ul>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/biography_entries/create?preset[belongsTo_type]=Terrarium&preset[belongsTo_id]={{ $terrarium->id }}"><i class="mdi mdi-24px mdi-plus"></i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_files" class="col s12">
        <div class="container">
            <files-list-widget
                    background-selector-class-name="Terrarium"
                    background-selector-id="{{ $terrarium->id }}"
                    source-url="terraria/{{ $terrarium->id }}/files"
                    subscribe-add="false">
            </files-list-widget>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large orange darken-4">
                <i class="mdi mdi-18px mdi-pencil"></i>
            </a>
            <ul>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/files/create?preset[belongsTo_type]=Terrarium&preset[belongsTo_id]={{ $terrarium->id }}"><i class="mdi mdi-24px mdi-plus"></i></a></li>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.link')" href="/files/associate/Terrarium/{{ $terrarium->id }}"><i class="mdi mdi-24px mdi-link"></i></a></li>
            </ul>
        </div>
    </div>
@stop