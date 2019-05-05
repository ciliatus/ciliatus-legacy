@extends('master')

@section('breadcrumbs')
    <a href="/physical_sensors" class="breadcrumb hide-on-small-and-down">@choice('labels.physical_sensors', 2)</a>
@stop


@section('content')
    <div class="container">
        <physical_sensors-list-widget :refresh-timeout-seconds="60" :container-classes="[]" wrapper-classes=""></physical_sensors-list-widget>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="mdi mdi-18px mdi-pencil"></i>
        </a>
        <ul>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/physical_sensors/create"><i class="mdi mdi-24px mdi-plus"></i></a></li>
        </ul>
    </div>
@stop