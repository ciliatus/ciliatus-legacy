@extends('master')

@section('breadcrumbs')
    <a href="/generic_components" class="breadcrumb hide-on-small-and-down">@choice('components.generic_components', 2)</a>
    <a href="/generic_component_types/{{ $generic_component->type->id }}" class="breadcrumb hide-on-small-and-down">{{ $generic_component->type->name_plural }}</a>
    <a href="/generic_components/{{ $generic_component->id }}" class="breadcrumb hide-on-small-and-down">{{ $generic_component->name }}</a>
@stop


@section('content')
    <div class="container">
        <generic_components-widget :refresh-timeout-seconds="60"
                                   generic-component-id="{{ $generic_component->id }}"
                                   :subscribe-add="false" :subscribe-delete="false"
                                   container-classes="row" wrapper-classes="col s12 m5 l4"></generic_components-widget>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating orange" href="/generic_components/{{ $generic_component->id }}/edit"><i class="material-icons">edit</i></a></li>
            <li><a class="btn-floating red" href="/generic_components/{{ $generic_component->id }}/delete"><i class="material-icons">delete</i></a></li>
        </ul>
    </div>
@stop