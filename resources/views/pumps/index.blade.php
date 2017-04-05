@extends('master')

@section('breadcrumbs')
    <a href="/pumps" class="breadcrumb hide-on-small-and-down">@choice('components.pumps', 2)</a>
@stop


@section('content')
    <div class="container">
        <pumps-list-widget :refresh-timeout-seconds="60" container-classes="" wrapper-classes=""></pumps-list-widget>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green" href="/pumps/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop