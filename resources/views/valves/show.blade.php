@extends('master')

@section('breadcrumbs')
    <a href="/valves" class="breadcrumb">@choice('components.valves', 2)</a>
    <a href="/valves/{{ $valve->id }}" class="breadcrumb">{{ $valve->name }}</a>
@stop

@section('content')
    <!-- left col -->
    <div class="col s12 m5 l4 no-padding">
        <div class="col s12 m12 l12">
            <valves-widget valve-id="{{ $valve->id }}" :subscribe-add="false" :subscribe-delete="false"></valves-widget>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating orange" href="/valves/{{ $valve->id }}/edit"><i class="material-icons">edit</i></a></li>
            <li><a class="btn-floating red" href="/valves/{{ $valve->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/valves/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop