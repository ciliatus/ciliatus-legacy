@extends('master')

@section('breadcrumbs')
    <a href="/action_sequences" class="breadcrumb hide-on-small-and-down">@choice('components.action_sequences', 2)</a>
@stop


@section('content')
    <div class="container">
        <action_sequences-list-widget :refresh-timeout-seconds="60" container-classes="" wrapper-classes=""></action_sequences-list-widget>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/action_sequences/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop