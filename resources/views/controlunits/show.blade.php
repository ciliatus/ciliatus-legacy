@extends('master')

@section('breadcrumbs')
    <a href="/controlunits" class="breadcrumb">@choice('components.controlunits', 2)</a>
    <a href="/controlunits/{{ $controlunit->id }}" class="breadcrumb">{{ $controlunit->name }}</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <controlunits-widget controlunit-id="{{ $controlunit->id }}" :subscribe-add="false" :subscribe-delete="false"></controlunits-widget>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating orange" href="/controlunits/{{ $controlunit->id }}/edit"><i class="material-icons">edit</i></a></li>
            <li><a class="btn-floating red" href="/controlunits/{{ $controlunit->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/controlunits/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop