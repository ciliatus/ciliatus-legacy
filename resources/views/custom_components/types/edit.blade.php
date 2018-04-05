@extends('master')

@section('breadcrumbs')
    <a href="/custom_components" class="breadcrumb hide-on-small-and-down">@choice('labels.custom_components', 2)</a>
    <a href="/custom_components/types" class="breadcrumb hide-on-small-and-down">@choice('labels.custom_component_types', 2)</a>
    <a href="#!" class="breadcrumb hide-on-small-and-down">@lang('buttons.add')</a>
@stop

@section('content')
    <div class="container">
        <custom_component_type_create-form
                container-classes="row" wrapper-classes="col s12 m12 l10"
                :custom-component-type="{{ json_encode($custom_component_type) }}"
                :properties="{{ json_encode(array_column($custom_component_type->properties->toArray(), 'name')) }}"
                :states="{{ json_encode(array_column($custom_component_type->states->toArray(), 'name')) }}"
                :intentions="{{ json_encode($custom_component_type->intentions->toArray()) }}"
                :sensorreading-types="{{ json_encode($sensorreading_types) }}"
                default-running-state="{{ $custom_component_type->getDefaultRunningState() }}">
        </custom_component_type_create-form>
    </div>
@stop