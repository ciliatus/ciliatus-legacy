@extends('master')

@section('breadcrumbs')
    <a href="/files" class="breadcrumb hide-on-small-and-down">@choice('labels.files', 2)</a>
    <a href="/files/create" class="breadcrumb hide-on-small-and-down">@lang('buttons.create')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/files') }}" data-method="POST" data-redirect-success="auto"
                          data-ignore-enctype="true" data-user-formdata="true">
                        <div class="card-content">

                            @if(!$preset_defined)
                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="belongsTo">
                                        <option></option>
                                        @foreach ($belongTo_Options as $t=>$objects)
                                        <optgroup label="@choice('labels.' . strtolower($t), 2)">
                                            @foreach ($objects as $o)
                                            <option value="{{ $t }}|{{ $o->id }}"
                                                @if(isset($preset['belongsTo_type']) && isset($preset['belongsTo_id']))
                                                    @if($preset['belongsTo_type'] == $t && $preset['belongsTo_id'] == $o->id)
                                                    selected
                                                    @endif
                                                @endif>@if(is_null($o->display_name)) {{ $o->name }} @else {{ $o->display_name }} @endif</option>
                                            @endforeach
                                        </optgroup>
                                        @endforeach
                                    </select>
                                    <label for="valves">@lang('labels.belongsTo')</label>
                                </div>
                            </div>
                            @else
                                <input name="belongsTo" hidden="hidden" value="{{ $preset['belongsTo_type'] }}|{{ $preset['belongsTo_id'] }}">
                            @endif

                            <div class="row">
                                <div class="file-field input-field">
                                    <div class="btn">
                                        <span>@choice('labels.files', 1)</span>
                                        <input type="file" name="file">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text">
                                    </div>
                                </div>
                                <div class="input-field">
                                    @lang('tooltips.max_file_size', ['size' => App\System::maxUploadFileSize()/1024/1024 . ' MB'])
                                </div>
                            </div>

                            <div class="row">
                                <div class="progress">
                                    <div class="determinate progress-bar form-progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0;"></div>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="mdi mdi-18px mdi-floppy left"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop