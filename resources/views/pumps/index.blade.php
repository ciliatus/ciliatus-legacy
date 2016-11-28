@extends('master')

@section('breadcrumbs')
    <a href="/pumps" class="breadcrumb">@choice('components.pumps', 2)</a>
@stop


@section('content')
    <pumps-widget wrapper-classes="col s12 m6 l4"></pumps-widget>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green" href="/pumps/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop