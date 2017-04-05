@extends('master')

@section('breadcrumbs')
    <a href="/logs" class="breadcrumb hide-on-small-and-down">@lang('menu.logs')</a>
@stop


@section('content')
    <logs-widget wrapper-classes="col s12"></logs-widget>
@stop