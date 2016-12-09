@extends('master')

@section('breadcrumbs')
    <a href="/users" class="breadcrumb">@lang('menu.users')</a>
@stop


@section('content')
    <div class="container">
        <users-widget wrapper-classes="col s12"></users-widget>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green" href="/users/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop