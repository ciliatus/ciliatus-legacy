@extends('master')

@section('breadcrumbs')
    <a href="/files" class="breadcrumb hide-on-small-and-down">@choice('components.files', 2)</a>
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

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="belongsTo">
                                        <option></option>
                                        @foreach ($belongTo_Options as $t=>$objects)
                                            <optgroup label="@choice('components.' . strtolower($t), 2)">
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

                            <div class="row">
                                <div class="file-field input-field">
                                    <div class="btn">
                                        <span>@choice('components.files', 1)</span>
                                        <input type="file" name="file">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text">
                                    </div>
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
@stop