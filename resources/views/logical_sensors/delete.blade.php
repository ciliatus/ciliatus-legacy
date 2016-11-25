@extends('master')

@section('breadcrumbs')
    <a href="/logical_sensors" class="breadcrumb">@choice('components.logical_sensors', 2)</a>
    <a href="/logical_sensors/{{ $logical_sensor->id }}" class="breadcrumb">{{ $logical_sensor->name }}</a>
    <a href="/logical_sensors/{{ $logical_sensor->id }}/delete" class="breadcrumb">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="col s12 m12 l6">
        <div class="card">
            <form name="f_edit_terra" action="{{ url('api/v1/logical_sensors/' . $logical_sensor->id) }}"
                  data-method="DELETE" data-redirect-success="{{ url('logical_sensors') }}">
                <div class="card-content">

                    <span class="card-title activator grey-text text-darken-4 truncate">
                        <span>{{ $logical_sensor->name }}</span>
                    </span>

                    <p>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" readonly placeholder="ID" name="id" value="{{ $logical_sensor->id }}">
                            <label for="id">ID</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" placeholder="@lang('labels.name')" readonly name="display_name" value="{{ $logical_sensor->name }}">
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