@extends('master')

@section('breadcrumbs')
<a href="/valves" class="breadcrumb">@choice('components.valves', 2)</a>
<a href="/valves/{{ $valve->id }}" class="breadcrumb">{{ $valve->name }}</a>
<a href="/valves/{{ $valve->id }}/edit" class="breadcrumb">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/valves/' . $valve->id) }}" data-method="PUT"
                          data-redirect-success="{{ url('valves/' . $valve->id) }}">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $valve->name }}</span>
                            </span>

                            <p>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" readonly="readonly" placeholder="ID" name="id" value="{{ $valve->id }}">
                                        <label for="id">ID</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" placeholder="@lang('labels.name')" name="name" value="{{ $valve->name }}">
                                        <label for="name">@lang('labels.name')</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <select name="controlunit">
                                            <option></option>
                                            @foreach ($controlunits as $cu)
                                                <option value="{{ $cu->id }}" @if($valve->controlunit_id == $cu->id)selected="selected"@endif>{{ $cu->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="valves">@choice('components.controlunits', 1)</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <select name="pump">
                                            <option></option>
                                            @foreach ($pumps as $p)
                                                <option value="{{ $p->id }}" @if($valve->pump_id == $p->id)selected="selected"@endif>{{ $p->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="valves">@choice('components.pumps', 1)</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <select name="terrarium">
                                            <option></option>
                                            @foreach ($terraria as $t)
                                                <option value="{{ $t->id }}" @if($valve->terrarium_id == $t->id)selected="selected"@endif>{{ $t->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="valves">@choice('components.terraria', 1)</label>
                                    </div>
                                </div>
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
            <li><a class="btn-floating teal" href="/valves/{{ $valve->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red" href="/valves/{{ $valve->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/valves/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop