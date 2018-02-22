@extends('master')

@section('breadcrumbs')
    <a href="/animals" class="breadcrumb hide-on-small-and-down">@choice('labels.animals', 2)</a>
    <a href="/animals/{{ $animal->id }}" class="breadcrumb hide-on-small-and-down">{{ $animal->display_name }}</a>
@stop

@section('content')

    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s3"><a class="active" href="#tab_overview">@lang('labels.overview')</a></li>
            <li class="tab col s3"><a href="#tab_health_feeding">@lang('labels.feedings')</a></li>
            <li class="tab col s3"><a href="#tab_health_weight">@lang('labels.weight')</a></li>
            @if (!is_null($animal->terrarium))
                <li class="tab col s3"><a href="#tab_environment">@lang('labels.environment')</a></li>
            @endif
            <li class="tab col s3"><a href="#tab_biography">@lang('labels.biography')</a></li>
            <li class="tab col s3"><a href="#tab_caresheets">@choice('labels.caresheets', 2)</a></li>
            <li class="tab col s3"><a href="#tab_files">@choice('labels.files', 2)</a></li>
        </ul>
    </div>
    <div id="tab_overview" class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12 m5 l4">
                    <animals-widget animal-id="{{ $animal->id }}"
                                    :subscribe-add="false" :subscribe-delete="false"
                                    container-classes="row" wrapper-classes="col s12"></animals-widget>

                    @if (!is_null($animal->terrarium))
                    <terraria-widget terrarium-id="{{ $animal->terrarium_id }}"
                                     :subscribe-add="false" :subscribe-delete="false"
                                     container-classes="row" wrapper-classes="col s12"></terraria-widget>
                    @endif
                </div>
                <div class="col s12 m7 l8">
                    <div class="card">
                        <div class="card-header">
                            <i class="material-icons">timeline</i>
                            <span>@lang('labels.weighprogression')</span>
                        </div>
                        <div class="card-content">
                            <google-graph type="line" event-type="AnimalWeighingEventUpdated"
                                          vertical-axis-title="@lang('labels.weight')" horizontal-axis-title="@lang('labels.date')"
                                          source="{{ url('api/v1/animals/' . $animal->id .'/weighings?graph=true') }}"
                                          :show-filter-form="true" filter-column="created_at"
                                          filter-from-date="{{ Carbon\Carbon::now()->subMonths(env('ANIMAL_DEFAULT_WEIGHT_HISTORY_MONTHS', 12))->toDateString() }}"
                                          id="animal_weigh_overview_graph"
                                          :height="400"></google-graph>
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
                <li><a class="btn-floating orange tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.edit')"href="/animals/{{ $animal->id }}/edit"><i class="material-icons">edit</i></a></li>
                <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/animals/{{ $animal->id }}/delete"><i class="material-icons">delete</i></a></li>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/animals/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_health_feeding" class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l4">
                    <animal_feeding_schedules-widget animal-id="{{ $animal->id }}"></animal_feeding_schedules-widget>
                </div>
                <div class="col s12 m6 l4">
                    <animal_feedings-widget animal-id="{{ $animal->id }}" source-filter="limit=10"></animal_feedings-widget>
                </div>
            </div>
        </div>
    </div>

    <div id="tab_health_weight" class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l4">
                    <animal_weighing_schedules-widget animal-id="{{ $animal->id }}"></animal_weighing_schedules-widget>

                    <animal_weighings-widget animal-id="{{ $animal->id }}"></animal_weighings-widget>
                </div>

                <div class="col s12 m12 l8">
                    <div class="card">
                        <div class="card-header">
                            <i class="material-icons">timeline</i>
                            <span>@lang('labels.weighprogression')</span>
                        </div>
                        <div class="card-content">
                            <google-graph type="line" event-type="AnimalWeighingEventUpdated"
                                          vertical-axis-title="@lang('labels.weight')" horizontal-axis-title="@lang('labels.date')"
                                          source="{{ url('api/v1/animals/' . $animal->id .'/weighings?graph=true') }}"
                                          :show-filter-form="true" filter-column="created_at"
                                          filter-from-date="{{ Carbon\Carbon::now()->subMonths(env('ANIMAL_DEFAULT_WEIGHT_HISTORY_MONTHS', 12))->toDateString() }}"
                                          id="animal_weigh_details_graph"
                                          :height="400"></google-graph>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (!is_null($animal->terrarium))
        <div id="tab_environment" class="col s12">
            <div class="container">
                <div class="row">
                    <div class="col s12 m5 l4">
                        <terraria-widget terrarium-id="{{ $animal->terrarium->id }}"
                                         :subscribe-add="false" :subscribe-delete="false"
                                         container-classes="row" wrapper-classes="col s12"></terraria-widget>
                    </div>

                    <div class="col s12 m7 l8">
                        <div class="card">
                            <div class="card-header">
                                <i class="material-icons">timeline</i>
                                @lang('labels.temp_and_hum_history')
                            </div>
                            <div class="card-content">
                                <dygraph-graph show-filter-field="read_at" :show-filter-form="true"
                                               filter-column="read_at"
                                               labels-div-id="sensorreadings-labels" time-axis-label="@lang('labels.read_at')"
                                               column-id-field="logical_sensor_id" column-name-field="logical_sensor_name"
                                               source="{{ url('api/v1/terraria/' . $animal->terrarium->id . '/sensorreadings') }}"></dygraph-graph>

                                <div id="sensorreadings-labels" class="dygraph-legend-div"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div id="tab_biography" class="col s12">
        <div class="container">
            <biography_entries-widget :refresh-timeout-seconds="60"
                                      belongs-to-type="Animal" belongs-to-id="{{ $animal->id }}"
                                      container-classes="container"></biography_entries-widget>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large orange darken-4">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/biography_entries/create?preset[belongsTo_type]=Animal&preset[belongsTo_id]={{ $animal->id }}"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_caresheets" class="col s12">
        <div class="container">
            <caresheets-widget animal-id="{{ $animal->id }}"
                               wrapper-classes="row"></caresheets-widget>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large orange darken-4">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/animals/caresheets/create?preset[belongsTo_type]=Animal&preset[belongsTo_id]={{ $animal->id }}"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_files" class="col s12">
        <div class="container">
            <files-list-widget
                    background-selector-class-name="Animal"
                    background-selector-id="{{ $animal->id }}"
                    source-filter="filter[belongsTo_id]={{ $animal->id }}&filter[belongsTo_type=Animal"
                    subscribe-add="false">
            </files-list-widget>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large orange darken-4">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/files/create?preset[belongsTo_type]=Animal&preset[belongsTo_id]={{ $animal->id }}"><i class="material-icons">add</i></a></li>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.link')" href="/files/associate/Animal/{{ $animal->id }}"><i class="material-icons">link</i></a></li>
            </ul>
        </div>
    </div>
@stop