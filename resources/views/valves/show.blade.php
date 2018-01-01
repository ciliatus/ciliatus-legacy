@extends('master')

@section('breadcrumbs')
    <a href="/valves" class="breadcrumb hide-on-small-and-down">@choice('components.valves', 2)</a>
    <a href="/valves/{{ $valve->id }}" class="breadcrumb hide-on-small-and-down">{{ $valve->name }}</a>
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
                    <valves-widget :refresh-timeout-seconds="60" valve-id="{{ $valve->id }}"
                                   :subscribe-add="false" :subscribe-delete="false"></valves-widget>
                </div>

                <div class="col s12 m6 l4">
                    <terraria-widget :refresh-timeout-seconds="60" terrarium-id="{{ $valve->terrarium_id }}"
                                     :subscribe-add="false" :subscribe-delete="false"
                                     container-classes="row" wrapper-classes="col s12"></terraria-widget>

                    <pumps-widget :refresh-timeout-seconds="60" pump-id="{{ $valve->pump_id }}"
                                  :subscribe-add="false" :subscribe-delete="false"
                                  container-classes="row" wrapper-classes="col s12"></pumps-widget>

                    <controlunits-widget :refresh-timeout-seconds="60" controlunit-id="{{ $valve->controlunit_id }}"
                                         :subscribe-add="false" :subscribe-delete="false"
                                         container-classes="row" wrapper-classes="col s12"></controlunits-widget>
                </div>
            </div>

            <div class="fixed-action-btn">
                <a class="btn-floating btn-large orange darken-4">
                    <i class="large material-icons">mode_edit</i>
                </a>
                <ul>
                    <li><a class="btn-floating orange tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.edit')"href="/valves/{{ $valve->id }}/edit"><i class="material-icons">edit</i></a></li>
                    <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/valves/{{ $valve->id }}/delete"><i class="material-icons">delete</i></a></li>
                    <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/valves/create"><i class="material-icons">add</i></a></li>
                </ul>
            </div>
        </div>
    </div>
@stop