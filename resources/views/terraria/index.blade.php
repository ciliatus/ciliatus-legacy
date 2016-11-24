@extends('master')

@section('breadcrumbs')
    <a href="/terraria" class="breadcrumb">@choice('components.terraria', 2)</a>
@stop

@section('content')
    <terraria-widget wrapper-classes="col s12 m6 l4"></terraria-widget>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green" href="/terraria/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop