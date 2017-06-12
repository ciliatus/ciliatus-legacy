@extends('master')

@section('breadcrumbs')
    <a href="/biography_entries" class="breadcrumb hide-on-small-and-down">@choice('components.biography_entries', 2)</a>
    <a href="/biography_entries/{{ $entry->id }}" class="breadcrumb hide-on-small-and-down">/{{ $entry->name }}</a>
    <a href="/biography_entries/{{ $entry->id }}/edit" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/biography_entries/' . $entry->id) }}" data-method="PUT" >
                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="belongsTo" disabled>
                                        <option></option>
                                        @foreach ($belongTo_Options as $t=>$objects)
                                            <optgroup label="@choice('components.' . strtolower($t), 2)">
                                                @foreach ($objects as $o)
                                                    <option value="{{ $t }}|{{ $o->id }}"
                                                            @if($entry->belongsTo_type == $t && $entry->belongsTo_id == $o->id)
                                                            selected
                                                            @endif>@if(is_null($o->display_name)) {{ $o->name }} @else {{ $o->display_name }} @endif</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <label for="belongsTo">@lang('labels.belongsTo')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="category">
                                        <option></option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->name }}"
                                            @if(!is_null($category))
                                            @if($category->name == $cat->name)
                                            selected
                                            @endif
                                            @endif>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="category">@lang('labels.bio_categories')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.title')" name="title" value="{{ $entry->name }}">
                                    <label for="name">@lang('labels.title')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <textarea placeholder="@lang('labels.text')" rows="15"
                                              name="text" class="materialize-textarea">{{ preg_replace('/\<br(\s*)?\/?\>/i', "\n", $entry->value) }}</textarea>
                                    <label for="text">@lang('labels.text')</label>
                                </div>
                            </div>

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

            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title activator truncate">
                            <span>@choice('components.files', 2)</span>
                        </span>
                    </div>
                    <div class="card-content">
                        @if(isset($entry->files))
                        @foreach($entry->files as $file)
                            <div style="width: 100%">
                                <form action="{{ url('api/v1/files/associate/BiographyEntryEvent/' . $entry->id . '/' . $file->id) }}"
                                      data-method="DELETE" data-redirect-success="/biography_entries/{{ $entry->id }}/edit">

                                    <a href="{{ $file->url() }}">{{ $file->display_name }}</a>
                                    <button class="btn btn-tiny waves-light right" type="submit"><i class="material-icons">delete</i></button>
                                </form>
                            </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="card-action">
                        <a href="/files/associate/BiographyEntryEvent/{{ $entry->id }}">@lang("buttons.add")</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/biography_entries/{{ $entry->id }}/delete"><i class="material-icons">delete</i></a></li>
        </ul>
    </div>
@stop