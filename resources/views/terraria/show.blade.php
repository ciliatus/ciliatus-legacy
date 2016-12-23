@extends('master')

@section('breadcrumbs')
    <a href="/terraria" class="breadcrumb">@choice('components.terraria', 2)</a>
    <a href="/terraria/{{ $terrarium->id }}" class="breadcrumb">{{ $terrarium->display_name }}</a>
@stop

@section('content')
    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s3"><a class="active" href="#tab_overview">@lang('labels.overview')</a></li>
            <li class="tab col s3"><a href="#tab_details">@lang('labels.details')</a></li>
            @if (!is_null($terrarium->animals))
                <li class="tab col s3"><a href="#tab_animals">@choice('components.animals', 2)</a></li>
            @endif
            <li class="tab col s3"><a target="_self" href="{{ url('terraria/' . $terrarium->id . '/edit') }}">@lang('buttons.edit')</a></li>
        </ul>
    </div>
    <div id="tab_overview" class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12 m5 l4">
                    <terraria-widget terrarium-id="{{ $terrarium->id }}" :subscribe-add="false" :subscribe-delete="false"
                                     container-classes="row" wrapper-classes="col s12"></terraria-widget>
                </div>

                <div class="col s12 m7 l8">
                    <div class="card">
                        <div class="card-content teal lighten-1 white-text">
                            @lang('labels.temp_and_hum_history')
                        </div>
                        <div class="card-content">
                            <dygraph-graph show-filter-field="created_at"
                                           source="{{ url('api/v1/terraria/' . $terrarium->id . '/sensorreadings?graph=true') }}"></dygraph-graph>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="tab_details" class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12 m12 l4">
                    <action_sequences-widget source-filter="?filter[terrarium_id]={{ $terrarium->id }}"
                                             terrarium-id="{{ $terrarium->id }}"
                                             container-classes="row" wrapper-classes="col s12"></action_sequences-widget>
                </div>

                <div class="col s12 m12 l4">
                    <files-widget source-filter="?filter[belongsTo_type]=Terrarium&filter[belongsTo_id]={{ $terrarium->id }}"
                                  belongs-to_type="Terrarium" belongs-to_id="{{ $terrarium->id }}"
                                  container-classes="row" wrapper-classes="col s12"></files-widget>
                </div>
            </div>
        </div>
    </div>

    @if (!is_null($terrarium->animals))
    <div id="tab_animals" class="col s12">
        <div class="container">
            <div class="row">
                @foreach ($terrarium->animals as $animal)
                    <div class="col s12 m6 l4">
                        <animals-widget animal-id="{{ $animal->id }}" :subscribe-add="false" :subscribe-delete="false"
                                        container-classes="row" wrapper-classes="col s12"></animals-widget>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif


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

    <script>
        ($(function() {
            $(document).ready(function(){
                $('ul.tabs').tabs();
            });
        }));
    </script>
@stop