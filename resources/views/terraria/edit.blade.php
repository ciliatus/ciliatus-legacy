@extends('master')

@section('breadcrumbs')
    <a href="/terraria" class="breadcrumb">@choice('components.terraria', 2)</a>
    <a href="/terraria/{{ $terrarium->id }}" class="breadcrumb">{{ $terrarium->display_name }}</a>
    <a href="/terraria/{{ $terrarium->id }}/edit" class="breadcrumb">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="col s12 m12 l6">
        <div class="card">
            <form action="{{ url('api/v1/terraria/' . $terrarium->id) }}" data-method="PUT"
                data-redirect-success="{{ url('terraria/' . $terrarium->id) }}">
                <div class="card-content">

                    <span class="card-title activator grey-text text-darken-4 truncate">
                        <span>{{ $terrarium->display_name }}</span>
                    </span>

                    <p>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" readonly="readonly" placeholder="ID" name="id" value="{{ $terrarium->id }}">
                            <label for="id">ID</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" placeholder="@lang('labels.name')" name="name" value="{{ $terrarium->name }}">
                            <label for="name">@lang('labels.name')</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" placeholder="@lang('labels.display_name')" name="display_name" value="{{ $terrarium->display_name }}">
                            <label for="display_name">@lang('labels.display_name')</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <select multiple name="valves[]">
                                @foreach ($valves as $v)
                                    <option value="{{ $v->id }}" @if($v->terrarium_id == $terrarium->id)selected="selected"@endif>{{ $v->name }}</option>
                                @endforeach
                            </select>
                            <label for="valves">@choice('components.valves', 2)</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <select multiple="multiple" name="animals[]">
                                @foreach ($animals as $a)
                                    <option value="{{ $a->id }}" @if($a->terrarium_id == $terrarium->id)selected="selected"@endif>{{ $a->display_name }} <i>{{ $a->lat_name }}</i></option>
                                @endforeach
                            </select>
                            <label for="animals">@choice('components.animals', 2)</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <div class="switch">
                                <label>
                                    @lang('labels.off')
                                    <input name="notifications_enabled" type="checkbox" @if($terrarium->notifications_enabled) checked @endif>
                                    <span class="lever"></span>
                                    @lang('labels.on') @lang('tooltips.sendnotifications')
                                </label>
                            </div>
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

    <div class="col s12 m12 l6">
        <div class="card">
            <div class="card-content">

                <span class="card-title activator grey-text text-darken-4 truncate">
                    <span>@choice('components.action_sequences', 2)</span>
                </span>

                <p>

                <div class="row">
                    @foreach($terrarium->action_sequences as $as)
                        <div class="input-field col s12">

                            <strong>{{ $as->name }}</strong> <i>{{ $as->duration_minutes }} @choice('units.minutes', $as->duration_minutes)</i>

                            <a class="dropdown-button btn btn-small" href="#" data-activates="dropdown-edit-action_sequences_{{ $as->id }}" style="margin-left: 20px">
                                @lang('labels.actions') <i class="material-icons">keyboard_arrow_down</i>
                            </a>

                            <ul id="dropdown-edit-action_sequences_{{ $as->id }}" class="dropdown-content">
                                <li>
                                    <a href="{{ url('action_sequences/' . $as->id . '/edit') }}">
                                        @lang('buttons.edit')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('action_sequences/' . $as->id . '/delete') }}">
                                        @lang('buttons.delete')
                                    </a>
                                </li>
                            </ul>

                            @foreach ($as->schedules as $ass)
                                <li>{{ $ass->starts_at }} @if (!$ass->runonce)<i>@lang('labels.daily')</i>@endif</li>
                            @endforeach
                        </div>
                    @endforeach

                    <a class="btn-floating btn-large waves-effect waves-light green right" href="/action_sequences/create?preset[terrarium]={{ $terrarium->id }}">
                        <i class="material-icons">add</i>
                    </a>

                </div>

                </p>

            </div>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/terraria/{{ $terrarium->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red" href="/terraria/{{ $terrarium->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/terraria/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop