@extends('master')

@section('breadcrumbs')
    <a href="/generic_components" class="breadcrumb">@choice('components.generic_components', 2)</a>
    <a href="/generic_components/{{ $generic_component_type->id }}" class="breadcrumb">{{ $generic_component_type->name_plural }}</a>
@stop


@section('content')
    <div class="container">
        <generic_components-widget :refresh-timeout-seconds="60"
                                   source-filter="filter[generic_component_type_id]={{ $generic_component_type->id }}"
                                   container-classes="row" wrapper-classes="col s12 m5 l4"></generic_components-widget>
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