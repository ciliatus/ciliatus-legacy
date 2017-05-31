@extends('master')

@section('breadcrumbs')
    <a href="/files" class="breadcrumb hide-on-small-and-down">@choice('components.files', 2)</a>
    <a href="/files/{{ $file->id }}" class="breadcrumb hide-on-small-and-down">{{ $file->display_name }}</a>
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
        <a class="btn-floating btn-large orange darken-4">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating orange tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.edit')"href="/files/{{ $file->id }}/edit"><i class="material-icons">edit</i></a></li>
            <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/files/{{ $file->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/files/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop