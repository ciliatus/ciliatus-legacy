@extends('master')

@section('breadcrumbs')
    <a href="/files" class="breadcrumb hide-on-small-and-down">@choice('components.files', 2)</a>
@stop


@section('content')
    <div class="container">
        <div class="card">
            <form action="{{ url('api/v1/files/associate/' . $type . '/' . $source->id) }}" data-method="POST"
                  data-redirect-success="{{ $source->url() }}" data-prevent-submit-on-enter="true">
                <div class="card-header">@lang('labels.association')</div>

                <div class="card-content">
                    <h5><span>@lang('tooltips.associate_new', [
                            'source_icon' => $source->icon(),
                            'source_type' => trans_choice('components.' . $type, 1),
                            'source_name' => is_null($source->display_name) ? $source->name : $source->display_name,
                            'target_icon' => 'attach_file',
                            'target_type' => trans_choice('components.files', 1),
                        ])</span></h5>
                </div>

                <div class="card-content">
                    <files-list-widget :show-option-select="true"></files-list-widget>
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

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/files/create?preset[belongsTo_type]={{ $type }}&preset[belongsTo_id]={{ $source->id }}"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop