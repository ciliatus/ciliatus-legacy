@extends('master')

@section('breadcrumbs')
<a href="/controlunits" class="breadcrumb hide-on-small-and-down">@choice('labels.controlunits', 2)</a>
<a href="/controlunits/{{ $controlunit->id }}" class="breadcrumb hide-on-small-and-down">{{ $controlunit->name }}</a>
<a href="/controlunits/{{ $controlunit->id }}/edit" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6">
                <div class="card">
                    <form action="{{ url('api/v1/controlunits/' . $controlunit->id) }}" data-method="PUT">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $controlunit->name }}</span>
                            </span>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly="readonly" placeholder="ID" name="id" value="{{ $controlunit->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.name')" name="name" value="{{ $controlunit->name }}">
                                    <label for="name">@lang('labels.name')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <label for="active">@lang('labels.active')</label>
                                    <div class="switch">
                                        <label>
                                            @lang('labels.off')
                                            <input name="active" type="hidden" value="off">
                                            <input name="active" type="checkbox" value="on"
                                                   @if($controlunit->active()) checked @endif>
                                            <span class="lever"></span>
                                            @lang('labels.on')
                                        </label>
                                    </div>
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

            <div class="col s12 m6 l6">
                <div class="card">
                    <form action="{{ url('api/v1/controlunits/' . $controlunit->id) }}" data-method="PUT">
                        <div class="card-header">
                            <span class="activator truncate">
                                <span>
                                    <i class="mdi mdi-18px mdi-silverware-variant"></i>
                                    @lang('labels.properties')
                                </span>
                            </span>
                        </div>
                        <div class="card-content">
                            <div class="row">
                                <div class="input-field col s12 tooltipped" data-position="top"
                                     data-delay="50" data-html="true" data-tooltip="<div style='max-width: 300px'>@lang('tooltips.i2c.bus_num')</div>">
                                    <input type="text" placeholder="@lang('labels.i2c_bus_num')" name="ControlunitConnectivity::i2c_bus_num" value="{{ $controlunit->property('ControlunitConnectivity', 'i2c_bus_num', true) }}">

                                    <label for="name">
                                        @lang('labels.i2c_bus_num')
                                    </label>
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
    
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="mdi mdi-18px mdi-pencil"></i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/controlunits/{{ $controlunit->id }}"><i class="mdi mdi-18px mdi-information-outline"></i></a></li>
            <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/controlunits/{{ $controlunit->id }}/delete"><i class="mdi mdi-24px mdi-delete"></i></a></li>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/controlunits/create"><i class="mdi mdi-24px mdi-plus"></i></a></li>
        </ul>
    </div>
@stop