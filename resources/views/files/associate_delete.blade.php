@extends('master')

@section('breadcrumbs')
    <a href="/files" class="breadcrumb hide-on-small-and-down">@choice('components.files', 2)</a>
    <a href="/files/{{ $file->id }}" class="breadcrumb hide-on-small-and-down">{{ $file->name }}</a>
    <a href="/files/{{ $file->id }}/delete" class="breadcrumb hide-on-small-and-down">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m5 l5">
                <div class="card">
                    <div class="card-content">

                        <span class="card-title activator truncate">
                            <span>{{ $file->display_name }}</span>
                        </span>

                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" readonly placeholder="ID" name="file_id" value="{{ $file->id }}">
                                <label for="file_id">ID</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" placeholder="@lang('labels.display_name')" readonly name="file_display_name" value="{{ $file->display_name }}">
                                <label for="file_display_name">@lang('labels.display_name')</label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col s12 m2 l2">
                <div class="center-align">
                    <i class="medium material-icons">link</i>
                </div>
            </div>

            <div class="col s12 m5 l5">
                <div class="card">
                    <div class="card-content">

                        <span class="card-title activator truncate">
                            <span>@if($source->name){{ $source->name }}@else{{ $source->display_name }}@endif</span>
                        </span>

                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" readonly placeholder="ID" name="source_id" value="{{ $source->id }}">
                                <label for="source_id">ID</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" placeholder="@lang('labels.display_name')" readonly name="source_display_name" value="{{ $source->display_name }}">
                                <label for="source_display_name">@lang('labels.display_name')</label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-action">
                        <form action="{{ url('api/v1/files/associate/' . $source->class . '/' . $source->id . '/' . $file->id) }}"
                              data-method="DELETE" data-redirect-success="{{ url('files/' . $file->id) }}">
                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light red" type="submit">@lang('buttons.delete')
                                        <i class="material-icons left">delete</i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop