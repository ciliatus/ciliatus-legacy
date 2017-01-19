@extends('master')

@section('breadcrumbs')
    <a href="/caresheets" class="breadcrumb">@choice('components.animals', 2)</a>
    <a href="/animal/{{ $caresheet->belongsTo_id }}" class="breadcrumb">{{ $caresheet->belongsTo_object->display_name }}</a>
    <a href="/animal/{{ $caresheet->belongsTo_id }}/#tab_caresheets" class="breadcrumb">@choice('components.caresheets', 2)</a>
    <a href="/animal/{{ $caresheet->belongsTo_id }}/caresheets/{{ $caresheet->id }}" class="breadcrumb">{{ $caresheet->name }}</a>
    <a href="/caresheets/{{ $caresheet->id }}/delete" class="breadcrumb">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/animals/' . $caresheet->belongsTo_id . '/caresheets/' . $caresheet->id) }}"
                          data-method="DELETE" data-redirect-success="auto">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $caresheet->title }}</span>
                            </span>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly placeholder="ID" name="id" value="{{ $caresheet->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light red" type="submit">@lang('buttons.delete')
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
@stop