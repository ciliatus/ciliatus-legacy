@extends('master')

@section('breadcrumbs')
    <a href="/physical_sensors" class="breadcrumb">@choice('components.physical_sensors', 2)</a>
    <a href="/physical_sensors/{{ $physical_sensor->id }}" class="breadcrumb">{{ $physical_sensor->name }}</a>
    <a href="/physical_sensors/{{ $physical_sensor->id }}/delete" class="breadcrumb">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="col s12 m12 l6">
        <div class="card">
            <form name="f_edit_terra" action="{{ url('api/v1/physical_sensors/' . $physical_sensor->id) }}"
                  data-method="DELETE" data-redirect-success="{{ url('physical_sensors') }}">
                <div class="card-content">

                    <span class="card-title activator grey-text text-darken-4 truncate">
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
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@stop