@extends('master')

@section('breadcrumbs')
    <a href="/controlunits" class="breadcrumb">@choice('components.controlunits', 2)</a>
    <a href="/controlunits/{{ $controlunit->id }}" class="breadcrumb">{{ $controlunit->name }}</a>
    <a href="/controlunits/{{ $controlunit->id }}/delete" class="breadcrumb">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/controlunits/' . $controlunit->id) }}"
                          data-method="DELETE" data-redirect-success="{{ url('controlunits') }}">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $controlunit->name }}</span>
                            </span>

                            <p>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly placeholder="ID" name="id" value="{{ $controlunit->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.name')" readonly name="display_name" value="{{ $controlunit->name }}">
                                    <label for="name">@lang('labels.name')</label>
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