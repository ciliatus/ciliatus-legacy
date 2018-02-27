@extends('master')

@section('breadcrumbs')
    <a href="/users" class="breadcrumb hide-on-small-and-down">@lang('menu.users')</a>
@stop


@section('content')
    <div class="container">
        <users-list-widget wrapper-classes="col s12"></users-list-widget>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/users/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop