@extends('master')

@section('breadcrumbs')
    <a href="/logical_sensors" class="breadcrumb">@choice('components.logical_sensors', 2)</a>
    <a href="/logical_sensors/{{ $logical_sensor->id }}" class="breadcrumb">{{ $logical_sensor->name }}</a>
@stop

@section('content')
    <!-- left col -->
    <div class="col s12 m5 l4 no-padding">
        <div class="col s12 m12 l12">
            <logical_sensors-widget logical_sensor-id="{{ $logical_sensor->id }}" :subscribe-add="false" :subscribe-delete="false"></logical_sensors-widget>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating orange" href="/logical_sensors/{{ $logical_sensor->id }}/edit"><i class="material-icons">edit</i></a></li>
            <li><a class="btn-floating red" href="/logical_sensors/{{ $logical_sensor->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/logical_sensors/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop