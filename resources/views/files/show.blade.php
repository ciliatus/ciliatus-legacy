@extends('master')

@section('breadcrumbs')
    <a href="/files" class="breadcrumb hide-on-small-and-down">@choice('labels.files', 2)</a>
    <a href="/files/{{ $file->id }}" class="breadcrumb hide-on-small-and-down">{{ $file->display_name }}</a>
@stop

@section('content')
    <div class="container">
        <files-show-widget file-id="{{ $file->id }}"
                           container-classes="row"></files-show-widget>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="mdi mdi-18px mdi-pencil"></i>
        </a>
        <ul>
            <li><a class="btn-floating orange tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.edit')"href="/files/{{ $file->id }}/edit"><i class="mdi mdi-24px mdi-pencil"></i></a></li>
            <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/files/{{ $file->id }}/delete"><i class="mdi mdi-24px mdi-delete"></i></a></li>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/files/create"><i class="mdi mdi-24px mdi-plus"></i></a></li>
        </ul>
    </div>
@stop