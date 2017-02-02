@extends('master')

@section('breadcrumbs')
    <a href="/generic_components" class="breadcrumb">@choice('components.generic_components', 2)</a>
    <a href="/generic_components/types" class="breadcrumb">@choice('components.generic_component_types', 2)</a>
    <a href="#" class="breadcrumb">@lang('buttons.add')</a>
@stop

@section('content')
    <div class="container">
        <generic_component_type_create-form container-classes="row" wrapper-classes="col s12 m12 l8" id="{{ $generic_component_type->id }}"
                                            icon="{{ $generic_component_type->icon }}" state="{{ $generic_component_type->state }}"
                                            name-singular="{{ $generic_component_type->name_singular }}" name-plural="{{ $generic_component_type->name_plural }}"
                                            :properties="{{ json_encode(array_column($generic_component_type->properties->toArray(), 'name')) }}"
                                            :states="{{ json_encode(array_column($generic_component_type->states->toArray(), 'name')) }}"></generic_component_type_create-form>
    </div>
@stop