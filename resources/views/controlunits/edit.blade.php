@extends('master')

@section('breadcrumbs')
<a href="/controlunits" class="breadcrumb hide-on-small-and-down">@choice('components.controlunits', 2)</a>
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

                            <p>
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
                            </p>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="material-icons right">save</i>
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
                        <div class="card-content orange darken-4 white-text">
                            <span class="activator truncate">
                                <span>@lang('labels.properties')</span>
                            </span>
                        </div>
                        <div class="card-content">
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.i2c_bus_num')" name="ControlunitConnectivity::i2c_bus_num" value="{{ $controlunit->property('ControlunitConnectivity', 'i2c_bus_num', true) }}">
                                    <label for="name">
                                        @lang('labels.i2c_bus_num')
                                        <a href="#" class="material-icons black-text tooltipped" data-position="top"
                                           data-delay="50" data-html="true" data-tooltip="<div style='max-width: 300px'>@lang('tooltips.i2c_bus_num')</div>">
                                            info_outline
                                        </a>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="material-icons right">save</i>
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
            <li><a class="btn-floating teal" href="/controlunits/{{ $controlunit->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red" href="/controlunits/{{ $controlunit->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/controlunits/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop