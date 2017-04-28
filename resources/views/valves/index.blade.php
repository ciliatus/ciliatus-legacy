@extends('master')

@section('breadcrumbs')
    <a href="/valves" class="breadcrumb hide-on-small-and-down">@choice('components.valves', 2)</a>
@stop


@section('content')
    <div class="container">
        <valves-list-widget :refresh-timeout-seconds="60" container-classes="" wrapper-classes=""></valves-list-widget>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green" href="/valves/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop