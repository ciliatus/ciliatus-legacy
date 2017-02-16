@extends('master')

@section('breadcrumbs')
    <a href="/physical_sensors" class="breadcrumb">@choice('components.physical_sensors', 2)</a>
@stop


@section('content')
    <div class="container">
        <physical_sensors-list-widget :refresh-timeout-seconds="60" container-classes="" wrapper-classes=""></physical_sensors-list-widget>
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