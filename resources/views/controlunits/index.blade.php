@extends('master')

@section('breadcrumbs')
    <a href="/controlunits" class="breadcrumb hide-on-small-and-down">@choice('labels.controlunits', 2)</a>
@stop


@section('content')
    <div class="container">
        <controlunits-list-widget></controlunits-list-widget>
    </div>

    <div class="fixed-action-btn">
        <a id="controlunits-floating-button"
           class="btn-floating btn-large orange darken-4 @if(App\Controlunit::count() < 1) pulse @endif">
            <i class="mdi mdi-18px mdi-pencil"></i>
        </a>

        @if(!Auth::user()->hasSeenFeatureDiscovery('floating_button'))
        <div class="tap-target" data-target="controlunits-floating-button">
            <div class="tap-target-content">
                <h5>@lang('tooltips.feature_discovery.floating_button.title')</h5>
                <p>@lang('tooltips.feature_discovery.floating_button.text')</p>
            </div>
        </div>
        @endif

        <ul>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/controlunits/create"><i class="mdi mdi-24px mdi-plus"></i></a></li>
        </ul>
    </div>
@stop