@extends('master')

@section('breadcrumbs')
    <a href="/files" class="breadcrumb hide-on-small-and-down">@choice('components.files', 2)</a>
@stop


@section('content')
    <div class="container">
        <files-widget wrapper-classes="col s12 m6 l4"></files-widget>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green" href="/files/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop