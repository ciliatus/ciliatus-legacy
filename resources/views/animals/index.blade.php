@extends('master')

@section('breadcrumbs')
    <a href="/animals" class="breadcrumb">@choice('components.animals', 2)</a>
@stop


@section('content')
    <animals-widget container-classes="row" wrapper-classes="col s12 m6 l4"></animals-widget>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green" href="/animals/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop