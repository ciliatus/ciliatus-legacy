@extends('master')

@section('breadcrumbs')
    <a href="/generic_components" class="breadcrumb">@choice('components.generic_components', 2)</a>
    <a href="/generic_component_types/{{ $generic_component->type->id }}" class="breadcrumb">{{ $generic_component->type->name_plural }}</a>
    <a href="/generic_components/{{ $generic_component->id }}" class="breadcrumb">{{ $generic_component->name }}</a>
    <a href="/generic_components/{{ $generic_component->id }}/delete" class="breadcrumb">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/generic_components/' . $generic_component->id) }}"
                          data-method="DELETE" data-redirect-success="{{ url('generic_component_types/' . $generic_component->type->id) }}">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $generic_component->name }}</span>
                            </span>

                            <p>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly placeholder="ID" name="id" value="{{ $generic_component->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <i class="material-icons prefix">{{ $generic_component->type->icon }}</i>
                                    <input type="text" readonly name="name" value="{{ $generic_component->name }}">
                                    <label for="name">@lang('labels.name')</label>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light red" type="submit">@lang('buttons.delete')
                                        <i class="material-icons right">delete</i>
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