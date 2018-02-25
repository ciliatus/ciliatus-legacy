@extends('master')

@section('breadcrumbs')
    <a href="/generic_components" class="breadcrumb hide-on-small-and-down">@choice('labels.generic_components', 2)</a>
    <a href="/generic_component_types/{{ $type->id }}" class="breadcrumb hide-on-small-and-down">{{ $type->name_plural }}</a>
    <a href="/generic_components/create" class="breadcrumb hide-on-small-and-down">@lang('buttons.create')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/generic_components') }}" data-method="POST" data-redirect-success="/generic_component_types/{{ $type->id }}">

                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" name="type_id" value="{{ $type->id }}" readonly hidden>
                                    <i class="material-icons prefix">{{ $type->icon }}</i>
                                    <input type="text" name="type_name" value="{{ $type->name_singular }}" readonly>
                                    <label for="type_name">@choice('labels.generic_component_types', 1)</label>
                                </div>
                            </div>

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

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="controlunit">
                                        <option></option>
                                        @foreach ($controlunits as $cu)
                                            <option value="{{ $cu->id }}">{{ $cu->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="controlunit">@choice('labels.controlunits', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.name')" name="name" value="">
                                    <label for="name">@lang('labels.name')</label>
                                </div>
                            </div>

                            @foreach($type->properties as $prop)
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="{{ $prop->name }}" name="properties[{{ $prop->id }}]" value="">
                                    <label for="name">{{ $prop->name }}</label>
                                </div>
                            </div>
                            @endforeach

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