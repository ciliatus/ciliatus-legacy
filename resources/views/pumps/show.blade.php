@extends('master')

@section('breadcrumbs')
    <a href="/pumps" class="breadcrumb hide-on-small-and-down">@choice('components.pumps', 2)</a>
    <a href="/pumps/{{ $pump->id }}" class="breadcrumb hide-on-small-and-down">{{ $pump->name }}</a>
@stop

@section('content')
    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s3"><a class="active" href="#tab_overview">@lang('labels.overview')</a></li>
            <li class="tab col s3"><a class="active" href="#tab_valves">@choice('components.valves', 2)</a></li>
            <li class="tab col s3"><a class="active" href="#tab_belongsTo">@lang('labels.belongsTo')</a></li>
        </ul>
    </div>

    <div id="tab_overview" class="col s12">
        <div class="container">
            <pumps-widget :refresh-timeout-seconds="60" pump-id="{{ $pump->id }}"
                          container-classes="row" wrapper-classes="col s12 m6 l4"
                          :subscribe-add="false" :subscribe-delete="false"></pumps-widget>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large orange darken-4">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating orange" href="/pumps/{{ $pump->id }}/edit"><i class="material-icons">edit</i></a></li>
                <li><a class="btn-floating red" href="/pumps/{{ $pump->id }}/delete"><i class="material-icons">delete</i></a></li>
                <li><a class="btn-floating green" href="/pumps/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_belongsTo" class="col s12">
        <div class="container">
            <div class="row">
                <controlunits-widget :refresh-timeout-seconds="60" controlunit-id="{{ $pump->controlunit_id }}"
                                     container-classes="col s12 m6 l4" wrapper-classes=""
                                     :subscribe-add="false" :subscribe-delete="false"></controlunits-widget>
            </div>
        </div>
    </div>

    <div id="tab_valves" class="col s12">
        <div class="container">
            <valves-list-widget :refresh-timeout-seconds="60"
                                :subscribe-add="false"
                                source-filter="filter[pump_id]={{ $pump->id }}"></valves-list-widget>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large orange darken-4">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green" href="/valves/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>
@stop