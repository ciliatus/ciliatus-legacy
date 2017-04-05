@extends('master')

@section('breadcrumbs')
    <a href="/generic_components" class="breadcrumb hide-on-small-and-down">@choice('components.generic_components', 2)</a>
    <a href="/generic_components/{{ $generic_component_type->id }}" class="breadcrumb hide-on-small-and-down">{{ $generic_component_type->name_plural }}</a>
@stop


@section('content')
    <div class="container">
        <generic_components-list-widget :refresh-timeout-seconds="60"
                                   default-type-filter="{{ $generic_component_type->name_singular }}"
                                   container-classes="" wrapper-classes=""></generic_components-list-widget>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green" href="/generic_components/create?preset[generic_component_type_id]={{ $generic_component_type->id }}"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop