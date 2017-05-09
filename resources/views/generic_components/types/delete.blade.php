@extends('master')

@section('breadcrumbs')
    <a href="/generic_component_types" class="breadcrumb hide-on-small-and-down">@choice('components.generic_component_types', 2)</a>
    <a href="/generic_component_types/{{ $generic_component_type->id }}" class="breadcrumb hide-on-small-and-down">{{ $generic_component_type->name }}</a>
    <a href="/generic_component_types/{{ $generic_component_type->id }}/delete" class="breadcrumb hide-on-small-and-down">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/generic_component_types/' . $generic_component_type->id) }}"
                          data-method="DELETE" data-redirect-success="{{ url('generic_component_types') }}">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $generic_component_type->name }}</span>
                            </span>

                            <p>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly placeholder="ID" name="id" value="{{ $generic_component_type->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <i class="material-icons prefix">{{ $generic_component_type->icon }}</i>
                                    <input type="text" readonly name="name" value="{{ $generic_component_type->name_singular }} / {{ $generic_component_type->name_plural }}">
                                    <label for="name">@lang('labels.name')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <div class="card-panel orange darken-3">
                                        @lang('tooltips.generic_components.type_delete_warning')
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light red" type="submit">@lang('buttons.delete')
                                        <i class="material-icons left">delete</i>
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