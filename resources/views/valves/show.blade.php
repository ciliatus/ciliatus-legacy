@extends('master')

@section('breadcrumbs')
    <a href="/valves" class="breadcrumb hide-on-small-and-down">@choice('components.valves', 2)</a>
    <a href="/valves/{{ $valve->id }}" class="breadcrumb hide-on-small-and-down">{{ $valve->name }}</a>
@stop

@section('content')
    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s3"><a class="active" href="#tab_overview">@lang('labels.overview')</a></li>
            <li class="tab col s3"><a class="active" href="#tab_belongsTo">@lang('labels.belongsTo')</a></li>
        </ul>
    </div>

    <div id="tab_overview" class="col s12">
        <div class="container">
            <valves-widget :refresh-timeout-seconds="60" valve-id="{{ $valve->id }}"
                           container-classes="row" wrapper-classes="col s12 m6 l4"
                           :subscribe-add="false" :subscribe-delete="false"></valves-widget>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large teal">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating orange" href="/valves/{{ $valve->id }}/edit"><i class="material-icons">edit</i></a></li>
                <li><a class="btn-floating red" href="/valves/{{ $valve->id }}/delete"><i class="material-icons">delete</i></a></li>
                <li><a class="btn-floating green" href="/valves/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_belongsTo" class="col s12">
        <div class="container">
            <div class="row">
                <terraria-widget :refresh-timeout-seconds="60" terrarium-id="{{ $valve->terrarium_id }}"
                                 container-classes="col s12 m6 l4" wrapper-classes=""
                                 :subscribe-add="false" :subscribe-delete="false"></terraria-widget>
                <pumps-widget :refresh-timeout-seconds="60" pump-id="{{ $valve->pump_id }}"
                                     container-classes="col s12 m6 l4" wrapper-classes=""
                                     :subscribe-add="false" :subscribe-delete="false"></pumps-widget>

                <controlunits-widget :refresh-timeout-seconds="60" controlunit-id="{{ $valve->controlunit_id }}"
                                     container-classes="col s12 m6 l4" wrapper-classes=""
                                     :subscribe-add="false" :subscribe-delete="false"></controlunits-widget>
            </div>
        </div>
    </div>
@stop