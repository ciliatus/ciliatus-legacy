@extends('master')

@section('breadcrumbs')
    <a href="/pumps" class="breadcrumb hide-on-small-and-down">@choice('labels.pumps', 2)</a>
    <a href="/pumps/{{ $pump->id }}" class="breadcrumb hide-on-small-and-down">{{ $pump->name }}</a>
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
                    <pump-widget pump-id="{{ $pump->id }}"> </pump-widget>
                </div>

                <div class="col s12 m7 l8">
                    <controlunits-widget :refresh-timeout-seconds="60" controlunit-id="{{ $pump->controlunit_id }}"
                                         :subscribe-add="false" :subscribe-delete="false"
                                         container-classes="row" wrapper-classes="col s12"></controlunits-widget>

                    <div class="card">
                        <div class="card-header">
                            <i class="material-icons">transform</i>
                            @choice('labels.valves', 2)
                        </div>
                        <div class="card-content">
                            <valves-list-widget :refresh-timeout-seconds="60" :subscribe-add="false"
                                                source-filter="filter[pump_id]={{ $pump->id }}"
                                                hide-cols="['pump']"
                                                container-classes="col s12" wrapper-classes=""></valves-list-widget>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fixed-action-btn">
                <a class="btn-floating btn-large orange darken-4">
                    <i class="large material-icons">mode_edit</i>
                </a>
                <ul>
                    <li><a class="btn-floating orange tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.edit')"href="/pumps/{{ $pump->id }}/edit"><i class="material-icons">edit</i></a></li>
                    <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/pumps/{{ $pump->id }}/delete"><i class="material-icons">delete</i></a></li>
                    <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/pumps/create"><i class="material-icons">add</i></a></li>
                </ul>
            </div>
        </div>
    </div>
@stop