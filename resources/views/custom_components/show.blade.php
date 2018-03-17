@extends('master')

@section('breadcrumbs')
    <a href="/custom_components" class="breadcrumb hide-on-small-and-down">@choice('labels.custom_components', 2)</a>
    <a href="/custom_component_types/{{ $custom_component->type->id }}" class="breadcrumb hide-on-small-and-down">{{ $custom_component->type->name_plural }}</a>
    <a href="/custom_components/{{ $custom_component->id }}" class="breadcrumb hide-on-small-and-down">{{ $custom_component->name }}</a>
@stop


@section('content')
    <div class="container">
        <div class="row">
            <custom_components-widget generic-component-id="{{ $custom_component->id }}"
                                       wrapper-classes="col s12 m5 l4"></custom_components-widget>

            @if(!is_null($custom_component->controlunit))
                <controlunit-widget controlunit-id="{{ $custom_component->controlunit_id }}"
                                    wrapper-classes="col s12 m7 l8"></controlunit-widget>
            @endif
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="mdi mdi-18px mdi-pencil"></i>
        </a>
        <ul>
            <li><a class="btn-floating orange tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.edit')"href="/custom_components/{{ $custom_component->id }}/edit"><i class="mdi mdi-24px mdi-pencil"></i></a></li>
            <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/custom_components/{{ $custom_component->id }}/delete"><i class="mdi mdi-24px mdi-delete"></i></a></li>
        </ul>
    </div>
@stop