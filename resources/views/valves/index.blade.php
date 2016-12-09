@extends('master')

@section('breadcrumbs')
    <a href="/valves" class="breadcrumb">@choice('components.valves', 2)</a>
@stop


@section('content')
    <div class="container">
        <valves-widget container-classes="row" wrapper-classes="col s12 m6 l4"></valves-widget>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green" href="/valves/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop