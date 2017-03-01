@extends('master')

@section('breadcrumbs')
    <a href="/controlunits" class="breadcrumb">@choice('components.controlunits', 2)</a>
    <a href="/controlunits/{{ $controlunit->id }}" class="breadcrumb">{{ $controlunit->name }}</a>
@stop

@section('content')
    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s3"><a class="active" href="#tab_overview">@lang('labels.overview')</a></li>
            <li class="tab col s3"><a class="active" href="#tab_physical-sensors">@choice('components.physical_sensors', 2)</a></li>
            <li class="tab col s3"><a class="active" href="#tab_pumps">@choice('components.pumps', 2)</a></li>
            <li class="tab col s3"><a class="active" href="#tab_valves">@choice('components.valves', 2)</a></li>
            <li class="tab col s3"><a class="active" href="#tab_configuration">@lang('labels.configuration')</a></li>
        </ul>
    </div>

    <div id="tab_overview" class="col s12">
        <div class="container">
            <controlunits-widget :refresh-timeout-seconds="60" controlunit-id="{{ $controlunit->id }}"
                                 container-classes="row" wrapper-classes="col s12 m6 l4"
                                 :subscribe-add="false"  :subscribe-delete="false"></controlunits-widget>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large teal">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating orange" href="/controlunits/{{ $controlunit->id }}/edit"><i class="material-icons">edit</i></a></li>
                <li><a class="btn-floating red" href="/controlunits/{{ $controlunit->id }}/delete"><i class="material-icons">delete</i></a></li>
                <li><a class="btn-floating green" href="/controlunits/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_physical-sensors" class="col s12">
        <div class="container">
            <physical_sensors-list-widget :refresh-timeout-seconds="60"
                                :subscribe-add="false"
                                source-filter="filter[controlunit_id]={{ $controlunit->id }}"></physical_sensors-list-widget>
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

    <div id="tab_pumps" class="col s12">
        <div class="container">
            <pumps-list-widget :refresh-timeout-seconds="60"
                                :subscribe-add="false"
                                source-filter="filter[controlunit_id]={{ $controlunit->id }}"></pumps-list-widget>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large teal">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green" href="/pumps/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_valves" class="col s12">
        <div class="container">
            <valves-list-widget :refresh-timeout-seconds="60"
                                :subscribe-add="false"
                                source-filter="filter[controlunit_id]={{ $controlunit->id }}"></valves-list-widget>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large teal">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green" href="/valves/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_configuration" class="col s12">
        <div class="container">
            <div class="card">
                <div class="card-content">
                    <pre>{{ $controlunit->generateConfig() }} </pre>
                </div>
            </div>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large teal">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green" href="/configuration/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>
@stop