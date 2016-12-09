@extends('master')

@section('breadcrumbs')
    <a href="/physical_sensors" class="breadcrumb">@choice('components.physical_sensors', 2)</a>
@stop


@section('content')
    <div class="container">
        <physical_sensors-widget container-classes="row" wrapper-classes="col s12 m6 l4"></physical_sensors-widget>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green" href="/physical_sensors/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop