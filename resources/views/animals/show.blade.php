@extends('master')

@section('breadcrumbs')
    <a href="/animals" class="breadcrumb">@choice('components.animals', 2)</a>
    <a href="/animals/{{ $animal->id }}" class="breadcrumb">{{ $animal->display_name }}</a>
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
            <li class="tab col s3"><a href="#tab_caresheets">@choice('components.caresheets', 2)</a></li>
            <li class="tab col s3"><a target="_self" href="{{ url('animals/' . $animal->id . '/edit') }}">@lang('buttons.edit')</a></li>
        </ul>
    </div>
    <div id="tab_overview" class="col s12">
        <div class="container">
            <div class="row">
                <animals-widget animal-id="{{ $animal->id }}"
                                         :subscribe-add="false" :subscribe-delete="false"
                                         container-classes="col s12 m6 l4" wrapper-classes=""></animals-widget>

                <terraria-widget terrarium-id="{{ $animal->terrarium_id }}"
                                         :subscribe-add="false" :subscribe-delete="false"
                                         container-classes="col s12 m6 l4" wrapper-classes=""></terraria-widget>

                <files-widget source-filter="?filter[belongsTo_type]=Animal&filter[belongsTo_id]={{ $animal->id }}"
                              belongs-to_type="Animal" belongs-to_id="{{ $animal->id }}"
                              container-classes="col s12 m6 l4" wrapper-classes=""></files-widget>
            </div>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large teal">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating orange" href="/animals/{{ $animal->id }}/edit"><i class="material-icons">edit</i></a></li>
                <li><a class="btn-floating red" href="/animals/{{ $animal->id }}/delete"><i class="material-icons">delete</i></a></li>
                <li><a class="btn-floating green" href="/animals/create"><i class="material-icons">add</i></a></li>
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
                    <animal_feedings-widget animal-id="{{ $animal->id }}"></animal_feedings-widget>
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
                        <div class="card-content teal lighten-1 white-text">
                            <span>@lang('labels.weighprogression')</span>
                        </div>
                        <div class="card-content">
                            <google-graph type="line" event-type="AnimalWeighingUpdated"
                                          vertical-axis-title="@lang('labels.weight')" horizontal-axis-title="@lang('labels.date')"
                                          source="{{ url('api/v1/animals/' . $animal->id .'/weighings?graph=true') }}"
                                          :show-filter-form="true" filter-column="created_at"
                                          filter-from-date="{{ Carbon\Carbon::now()->subMonths(3)->toDateString() }}"
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
                            <div class="card-content teal lighten-1 white-text">
                                @lang('labels.temp_and_hum_history')
                            </div>
                            <div class="card-content">
                                <dygraph-graph :show-filter-form="true" filter-column="created_at"
                                               filter-from-date="{{ Carbon\Carbon::now()->subDays(7)->toDateString() }}"
                                               source="{{ url('api/v1/terraria/' . $animal->terrarium_id . '/sensorreadings?graph=true') }}"></dygraph-graph>
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
            <a class="btn-floating btn-large teal">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green" href="/biography_entries/create?preset[belongsTo_type]=Animal&preset[belongsTo_id]={{ $animal->id }}"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_caresheets" class="col s12">
        <div class="container">
            <caresheets-widget :refresh-timeout-seconds="60"
                                      belongs-to-id="{{ $animal->id }}"
                                      container-classes="container"></caresheets-widget>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large teal">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green" href="/animals/caresheets/create?preset[belongsTo_type]=Animal&preset[belongsTo_id]={{ $animal->id }}"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('ul.tabs').tabs();
        });
    </script>
@stop