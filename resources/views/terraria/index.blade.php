@extends('master')

@section('breadcrumbs')
    <a href="/terraria" class="breadcrumb">@choice('components.terraria', 2)</a>
@stop

@section('content')
    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s3"><a class="active" href="#tab_dashboard">@lang('labels.overview')</a></li>
        </ul>
    </div>

    <div id="tab_dashboard" class="col s12">
        <div class="container">
            <terraria-widget :refresh-timeout-seconds="60" source-filter="history_minutes=0"
                             container-classes="row" wrapper-classes="col s12 m6 l4"></terraria-widget>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green" href="/terraria/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop