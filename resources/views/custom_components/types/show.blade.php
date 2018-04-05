@extends('master')

@section('breadcrumbs')
    <a href="/custom_components" class="breadcrumb hide-on-small-and-down">@choice('labels.custom_components', 2)</a>
    <a href="/custom_components/{{ $custom_component_type->id }}" class="breadcrumb hide-on-small-and-down">{{ $custom_component_type->name_plural }}</a>
@stop


@section('content')
    <div class="container">
        <custom_components-list-widget :refresh-timeout-seconds="60"
                                        source-filter="filter[type.id]={{ $custom_component_type->id }}"
                                        wrapper-classes=""></custom_components-list-widget>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="mdi mdi-18px mdi-pencil"></i>
        </a>
        <ul>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/custom_components/create?preset[custom_component_type_id]={{ $custom_component_type->id }}"><i class="mdi mdi-24px mdi-plus"></i></a></li>
        </ul>
    </div>
@stop