@extends('master')

@section('breadcrumbs')
<a href="/logical_sensors" class="breadcrumb">@choice('components.logical_sensors', 2)</a>
<a href="/logical_sensors/{{ $logical_sensor->id }}" class="breadcrumb">{{ $logical_sensor->display_name }}</a>
<a href="/logical_sensors/{{ $logical_sensor->id }}/edit" class="breadcrumb">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="col s12 m12 l6">
        <div class="card">
            <form name="f_edit_terra" action="{{ url('api/v1/logical_sensors/' . $logical_sensor->id) }}" data-method="PUT"
                  data-redirect-success="{{ url('terraria/' . $logical_sensor->id) }}">
                <div class="card-content">

                    <span class="card-title activator grey-text text-darken-4 truncate">
                        <span>{{ $logical_sensor->display_name }}</span>
                    </span>

                    <p>
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" readonly="readonly" placeholder="ID" name="id" value="{{ $logical_sensor->id }}">
                                <label for="id">ID</label>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" placeholder="@lang('labels.name')" name="name" value="{{ $logical_sensor->name }}">
                                <label for="name">@lang('labels.name')</label>
                            </div>
                        </div>
                    </p>

                </div>

                <div class="card-action">

                    <div class="row">
                        <div class="input-field col s12">
                            <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
    
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/logical_sensors/{{ $logical_sensor->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red" href="/logical_sensors/{{ $logical_sensor->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/logical_sensors/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop