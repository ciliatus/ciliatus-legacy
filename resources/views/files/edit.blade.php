@extends('master')

@section('breadcrumbs')
<a href="/files" class="breadcrumb">@choice('components.files', 2)</a>
<a href="/files/{{ $file->id }}" class="breadcrumb">{{ $file->display_name }}</a>
<a href="/files/{{ $file->id }}/edit" class="breadcrumb">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/files/' . $file->id) }}" data-method="PUT"
                          data-redirect-success="{{ url('files/' . $file->id) }}">
                        <div class="card-content">

                            <span class="card-title activator grey-text text-darken-4 truncate">
                                <span>{{ $file->display_name }}</span>
                            </span>

                            <p>
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
                                    <div class="input-field col s12">
                                        <div class="switch">
                                            <label>
                                                @lang('labels.off')
                                                <input name="use_as_background" type="checkbox" @if($file->property('is_default_background') == true) checked @endif>
                                                <span class="lever"></span>
                                                @lang('labels.on') @lang('labels.use_as_background')
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </p>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="material-icons right">send</i>
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
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/files/{{ $file->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red" href="/files/{{ $file->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/files/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop