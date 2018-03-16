@extends('master')

@section('breadcrumbs')
    <a href="/physical_sensors" class="breadcrumb hide-on-small-and-down">@choice('labels.physical_sensors', 2)</a>
    <a href="/physical_sensors/{{ $physical_sensor->id }}" class="breadcrumb hide-on-small-and-down">{{ $physical_sensor->name }}</a>
    <a href="/physical_sensors/{{ $physical_sensor->id }}/delete" class="breadcrumb hide-on-small-and-down">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/physical_sensors/' . $physical_sensor->id) }}"
                          data-method="DELETE" data-redirect-success="{{ url('physical_sensors') }}">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $physical_sensor->name }}</span>
                            </span>

                            <p>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly placeholder="ID" name="id" value="{{ $physical_sensor->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.name')" readonly name="display_name" value="{{ $physical_sensor->name }}">
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