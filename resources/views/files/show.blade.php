@extends('master')

@section('breadcrumbs')
    <a href="/files" class="breadcrumb">@choice('components.files', 2)</a>
    <a href="/files/{{ $file->id }}" class="breadcrumb">{{ $file->display_name }}</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <files-show-widget file-id="{{ $file->id }}"
                               :subscribe-add="false" :subscribe-delete="false"
                               container-classes="row"></files-show-widget>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating orange" href="/files/{{ $file->id }}/edit"><i class="material-icons">edit</i></a></li>
            <li><a class="btn-floating red" href="/files/{{ $file->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/files/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop