@extends('master')

@section('breadcrumbs')
    <a href="/generic_components" class="breadcrumb">@choice('components.generic_components', 2)</a>
    <a href="/generic_component_types/{{ $generic_component->type->id }}" class="breadcrumb">{{ $generic_component->type->name_plural }}</a>
    <a href="/generic_components/{{ $generic_component->id }}" class="breadcrumb">{{ $generic_component->name }}</a>
    <a href="/generic_components/{{ $generic_component->id }}/edit" class="breadcrumb">@lang('labels.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/generic_components/' . $generic_component->id) }}" data-method="PUT" data-redirect-success="auto">

                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" name="type_id" value="{{ $generic_component->type->id }}" readonly hidden>
                                    <i class="material-icons prefix">{{ $generic_component->type->icon }}</i>
                                    <input type="text" name="type_name" value="{{ $generic_component->type->name_singular }}" readonly>
                                    <label for="type_name">@choice('components.generic_components', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="belongsTo">
                                        <option></option>
                                        @foreach ($belongTo_Options as $t=>$objects)
                                            <optgroup label="@choice('components.' . strtolower($t), 2)">
                                                @foreach ($objects as $o)
                                                    <option value="{{ $t }}|{{ $o->id }}"
                                                            @if($generic_component->belongsTo_type == $t && $generic_component->belongsTo_id == $o->id)
                                                            selected
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
                                            <option value="{{ $cu->id }}" @if($cu->id == $generic_component->controlunit->id) selected @endif>{{ $cu->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="controlunit">@choice('components.controlunit', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.name')" name="name" value="{{ $generic_component->name }}">
                                    <label for="name">@lang('labels.name')</label>
                                </div>
                            </div>

                            @foreach($generic_component->properties as $prop)
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="{{ $prop->name }}" name="properties[{{ $prop->id }}]" value="{{ $prop->value }}">
                                    <label for="properties[{{ $prop->id }}]">{{ $prop->name }}</label>
                                </div>
                            </div>
                            @endforeach

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.next')
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