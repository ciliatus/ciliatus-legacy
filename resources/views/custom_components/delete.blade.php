@extends('master')

@section('breadcrumbs')
    <a href="/custom_components" class="breadcrumb hide-on-small-and-down">@choice('labels.custom_components', 2)</a>
    <a href="/custom_component_types/{{ $custom_component->type->id }}" class="breadcrumb hide-on-small-and-down">{{ $custom_component->type->name_plural }}</a>
    <a href="/custom_components/{{ $custom_component->id }}" class="breadcrumb hide-on-small-and-down">{{ $custom_component->name }}</a>
    <a href="/custom_components/{{ $custom_component->id }}/delete" class="breadcrumb hide-on-small-and-down">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/custom_components/' . $custom_component->id) }}"
                          data-method="DELETE" data-redirect-success="{{ url('custom_component_types/' . $custom_component->type->id) }}">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $custom_component->name }}</span>
                            </span>

                            <p>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly placeholder="ID" name="id" value="{{ $custom_component->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <i class="mdi mdi-18px mdi-{{ $custom_component->type->icon }} prefix"></i>
                                    <input type="text" readonly name="name" value="{{ $custom_component->name }}">
                                    <label for="name">@lang('labels.name')</label>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light red" type="submit">@lang('buttons.delete')
                                        <i class="mdi mdi-18px mdi-delete left"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop