@extends('master')

@section('breadcrumbs')
    <a href="/valves" class="breadcrumb hide-on-small-and-down">@choice('labels.valves', 2)</a>
@stop


@section('content')
    <div class="container">
        <valves-list-widget :refresh-timeout-seconds="60" :container-classes="['masonry-grid']" wrapper-classes=""></valves-list-widget>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="mdi mdi-18px mdi-pencil"></i>
        </a>
        <ul>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/valves/create"><i class="mdi mdi-24px mdi-plus"></i></a></li>
        </ul>
    </div>
@stop