@extends('master')

@section('breadcrumbs')
    <a href="/generic_components" class="breadcrumb">@choice('components.generic_components', 2)</a>
    <a href="/generic_components/types" class="breadcrumb">@choice('components.generic_component_types', 2)</a>
    <a href="#" class="breadcrumb">@lang('buttons.add')</a>
@stop

@section('content')
    <div class="container">
        <generic_component_type_create-form container-classes="row"
                                            wrapper-classes="col s12 m12 l8"></generic_component_type_create-form>
    </div>
@stop