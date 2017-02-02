@extends('master')

@section('breadcrumbs')
    <a href="/terraria" class="breadcrumb">@choice('components.terraria', 2)</a>
    <a href="/terraria/{{ $terrarium->id }}" class="breadcrumb">{{ $terrarium->display_name }}</a>
@stop

@section('content')
    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s3"><a class="active" href="#tab_overview">@lang('labels.overview')</a></li>
            @if (!is_null($terrarium->animals))
                <li class="tab col s3"><a href="#tab_animals">@choice('components.animals', 2)</a></li>
            @endif
            <li class="tab col s3"><a href="#tab_files">@choice('components.files', 2)</a></li>
            <li class="tab col s3"><a href="#tab_infrastructure">@lang('labels.infrastructure')</a></li>
            <li class="tab col s3"><a href="#tab_biography">@lang('labels.biography')</a></li>
            <li class="tab col s3"><a target="_self" href="{{ url('terraria/' . $terrarium->id . '/edit') }}">@lang('buttons.edit')</a></li>
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
                                             terrarium-id="{{ $terrarium->id }}"
                                             container-classes="row" wrapper-classes="col s12"></action_sequences-widget>
                </div>

                <div class="col s12 m7 l8">
                    <div class="card">
                        <div class="card-content teal lighten-1 white-text">
                            @lang('labels.temp_and_hum_history')
                        </div>
                        <div class="card-content">
                            <dygraph-graph show-filter-field="created_at" :show-filter-form="true"
                                           source="{{ url('api/v1/terraria/' . $terrarium->id . '/sensorreadings?graph=true') }}"></dygraph-graph>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large teal">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating orange" href="/terraria/{{ $terrarium->id }}/edit"><i class="material-icons">edit</i></a></li>
                <li><a class="btn-floating red" href="/terraria/{{ $terrarium->id }}/delete"><i class="material-icons">delete</i></a></li>
                <li><a class="btn-floating green" href="/terraria/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    @if (!is_null($terrarium->animals))
        <div id="tab_animals" class="col s12">
            <div class="container">
                <animals-widget source-filter="filter[terrarium_id]={{ $terrarium->id }}"
                                :subscribe-add="false" :subscribe-delete="false"
                                container-classes="row" wrapper-classes="col s12 m6 l4"></animals-widget>
            </div>
        </div>
    @endif

    <div id="tab_files" class="col s12">
        <div class="container">
            <files-widget source-filter="filter[belongsTo_type]=Terrarium&filter[belongsTo_id]={{ $terrarium->id }}"
                          belongs-to_type="Terrarium" belongs-to_id="{{ $terrarium->id }}"
                          container-classes="row" wrapper-classes="col s12"></files-widget>
        </div>
    </div>

    <div id="tab_infrastructure" class="col s12">
        <div class="container">
            <physical_sensors-widget :refresh-timeout-seconds="60" source-filter="filter[belongsTo_type]=Terrarium&filter[belongsTo_id]={{ $terrarium->id }}"
                                     container-classes="row" wrapper-classes="col s12 m6 l4"
                                     :subscribe-add="true" :subscribe-delete="true"></physical_sensors-widget>

            <valves-widget :refresh-timeout-seconds="60" source-filter="filter[terrarium_id]={{ $terrarium->id }}"
                           container-classes="row" wrapper-classes="col s12 m6 l4"
                           :subscribe-add="true" :subscribe-delete="true"></valves-widget>

            <generic_components-widget :refresh-timeout-seconds="60" source-filter="filter[belongsTo_type]=Terrarium&filter[belongsTo_id]={{ $terrarium->id }}"
                           container-classes="row" wrapper-classes="col s12 m6 l4"
                           :subscribe-add="true" :subscribe-delete="true"></generic_components-widget>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large teal">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green" href="/physical_sensors/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_biography" class="col s12">
        <div class="container">
            <biography_entries-widget :refresh-timeout-seconds="60"
                                      belongs-to-type="Terrarium" belongs-to-id="{{ $terrarium->id }}"
                                      container-classes="container"></biography_entries-widget>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large teal">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green" href="/biography_entries/create?preset[belongsTo_type]=Terrarium&preset[belongsTo_id]={{ $terrarium->id }}"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('ul.tabs').tabs();
        });
    </script>
@stop