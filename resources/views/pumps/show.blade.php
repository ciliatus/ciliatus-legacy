@extends('master')

@section('breadcrumbs')
    <a href="/pumps" class="breadcrumb">@choice('components.pumps', 2)</a>
    <a href="/pumps/{{ $pump->id }}" class="breadcrumb">{{ $pump->display_name }}</a>
@stop

@section('content')
    <!-- left col -->
    <div class="col s12 m5 l4 no-padding">
        <div class="col s12 m12 l12">
            <pumps-widget pump-id="{{ $pump->id }}" :subscribe-add="false" :subscribe-delete="false"></pumps-widget>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating orange" href="/pumps/{{ $pump->id }}/edit"><i class="material-icons">edit</i></a></li>
            <li><a class="btn-floating red" href="/pumps/{{ $pump->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/pumps/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop