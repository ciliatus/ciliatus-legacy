@extends('master')

@section('breadcrumbs')
<a href="/files" class="breadcrumb hide-on-small-and-down">@choice('components.files', 2)</a>
<a href="/files/{{ $file->id }}" class="breadcrumb hide-on-small-and-down">{{ $file->display_name }}</a>
<a href="/files/{{ $file->id }}/edit" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/files/' . $file->id) }}" data-method="PUT">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $file->display_name }}</span>
                            </span>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly="readonly" placeholder="ID" name="id" value="{{ $file->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.display_name')" name="display_name" value="{{ $file->display_name }}">
                                    <label for="name">@lang('labels.display_name')</label>
                                </div>
                            </div>

                            @if(explode("/", $file->mimetype)[0] == 'image')
                            <div class="row">
                                <div class="col s12">
                                    <label for="use_as_background">@lang('labels.use_as_background')</label>
                                    <div class="switch">
                                        <label>
                                            @lang('labels.on')
                                            <input name="use_as_background" type="checkbox" @if($file->property('generic', 'is_default_background') == true) checked @endif>
                                            <span class="lever"></span>
                                            @lang('labels.off')
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="material-icons left">save</i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/files/{{ $file->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/files/{{ $file->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/files/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop