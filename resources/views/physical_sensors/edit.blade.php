@extends('master')

@section('breadcrumbs')
<a href="/physical_sensors" class="breadcrumb">@choice('components.physical_sensors', 2)</a>
<a href="/physical_sensors/{{ $physical_sensor->id }}" class="breadcrumb">{{ $physical_sensor->name }}</a>
<a href="/physical_sensors/{{ $physical_sensor->id }}/edit" class="breadcrumb">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="col s12 m12 l6">
        <div class="card">
            <form action="{{ url('api/v1/physical_sensors/' . $physical_sensor->id) }}" data-method="PUT"
                  data-redirect-success="{{ url('physical_sensors/' . $physical_sensor->id) }}">
                <div class="card-content">

                    <span class="card-title activator grey-text text-darken-4 truncate">
                        <span>{{ $physical_sensor->name }}</span>
                    </span>

                    <p>
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" readonly="readonly" placeholder="ID" name="id" value="{{ $physical_sensor->id }}">
                                <label for="id">ID</label>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" placeholder="@lang('labels.name')" name="name" value="{{ $physical_sensor->name }}">
                                <label for="name">@lang('labels.name')</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <select name="controlunit">
                                    <option></option>
                                    @foreach ($controlunits as $c)
                                        <option value="{{ $c->id }}" @if($physical_sensor->controlunit_id == $c->id)selected="selected"@endif>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                <label for="valves">@choice('components.controlunits', 1)</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <select name="terrarium">
                                    <option></option>
                                    @foreach ($terraria as $t)
                                        <option value="{{ $t->id }}" @if($physical_sensor->belongsTo_id == $t->id && $physical_sensor->belongsTo_type == 'terrarium')selected="selected"@endif>{{ $t->name }}</option>
                                    @endforeach
                                </select>
                                <label for="valves">@choice('components.terraria', 1)</label>
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
            <li><a class="btn-floating teal" href="/physical_sensors/{{ $physical_sensor->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red" href="/physical_sensors/{{ $physical_sensor->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/physical_sensors/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop