@extends('master')

@section('breadcrumbs')
    <a href="/terraria" class="breadcrumb hide-on-small-and-down">@choice('components.terraria', 2)</a>
    <a href="/terraria/{{ $terrarium->id }}" class="breadcrumb hide-on-small-and-down">{{ $terrarium->display_name }}</a>
    <a href="/terraria/{{ $terrarium->id }}/edit" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/terraria/' . $terrarium->id) }}" data-method="PUT"
                        >
                        <div class="card-content">

                            <span class="card-title activator truncate">
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
                                    <input name="valves" value="" hidden>
                                    <select multiple name="valves[]">
                                        <option value="" disabled selected></option>
                                        @foreach ($valves as $v)
                                            <option value="{{ $v->id }}" @if($v->terrarium_id == $terrarium->id)selected="selected"@endif>{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="valves">@choice('components.valves', 2)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input name="animals" value="" hidden>
                                    <select multiple="multiple" name="animals[]">
                                        @if ($terrarium->animals->count() < 1)
                                        @endif
                                        <option value="" disabled selected></option>
                                        @foreach ($animals as $a)
                                            <option value="{{ $a->id }}"
                                                    data-icon="{{ $a->background_image_path() }}" class="circle"
                                                    @if($a->terrarium_id == $terrarium->id)selected="selected"@endif>{{ $a->display_name }} <i>{{ $a->lat_name }}</i>
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="animals">@choice('components.animals', 2)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <label for="notifications_enabled">@lang('tooltips.sendnotifications')</label>
                                    <div class="switch">
                                        <label>
                                            @lang('tooltips.on')
                                            <input name="notifications_enabled" type="hidden" value="off">
                                            <input name="notifications_enabled" type="checkbox" @if($terrarium->notifications_enabled) checked @endif>
                                            <span class="lever"></span>
                                            @lang('tooltips.off')
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
                                        <i class="material-icons left">save</i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

            </div>

            <div class="col s12 m12 l6">

                <form action="{{ url('api/v1/terraria/' . $terrarium->id) }}" data-method="PUT"
                      >
                    <div class="card">
                        <div class="card-header">
                            <span class="activator truncate">
                                <span>
                                    <i class="material-icons">lightbulb_outline</i>
                                    @lang('labels.suggestions')
                                </span>
                            </span>
                        </div>

                        <div class="card-content">
                            @foreach (['humidity_percent', 'temperature_celsius'] as $type)

                                <strong>@lang('labels.suggestions') @lang('labels.' . $type)</strong>


                                <div class="row">

                                    <div class="col s12 m6">
                                        <label for="suggestions[{{ $type }}][enabled]">@lang('tooltips.show_suggestions')</label>
                                        <div class="switch">
                                            <label>
                                                @lang('labels.off')
                                                <input name="suggestions[{{ $type }}][enabled]" type="hidden" value="off">
                                                <input name="suggestions[{{ $type }}][enabled]" type="checkbox" value="on" @if($terrarium->getSuggestionsEnabled($type)) checked @endif>
                                                <span class="lever"></span>
                                                @lang('labels.on')
                                            </label>
                                        </div>
                                    </div>

                                    <div class="input-field col s12 m6 tooltipped" data-position="top"
                                         data-delay="50" data-html="true" data-tooltip="<div style='max-width: 300px'>@lang('tooltips.suggestions_unit')</div>">
                                        <input type="text" placeholder="@lang('labels.suggestions_threshold') @lang('labels.' . $type)"
                                               name="suggestions[{{ $type }}][threshold]" @if($terrarium->getSuggestionThreshold($type)) value="{{ $terrarium->getSuggestionThreshold($type) }}" @else value="10" @endif>
                                        <label for="suggestions[{{ $type }}][threshold]">
                                            @lang('labels.suggestions_unit')
                                        </label>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="input-field col s12 m6 tooltipped" data-position="top"
                                         data-delay="50" data-html="true" data-tooltip="<div style='max-width: 300px'>@lang('tooltips.suggestion_timeframe_unit')</div>">
                                        <select name="suggestions[{{ $type }}][timeframe_unit]">
                                            <option value="year" @if($terrarium->getSuggestionTimeframeUnit($type) == 'year') selected @endif>@choice('units.years', 1)</option>
                                            <option value="month" @if($terrarium->getSuggestionTimeframeUnit($type) == 'month' || !$terrarium->getSuggestionTimeframeUnit($type)) selected @endif>@choice('units.months', 1)</option>
                                            <option value="week" @if($terrarium->getSuggestionTimeframeUnit($type) == 'week') selected @endif>@choice('units.weeks', 1)</option>
                                        </select>

                                        <label for="suggestions[{{ $type }}][timeframe_unit]">
                                            @lang('labels.suggestion_timeframe_unit')
                                        </label>
                                    </div>

                                    <div class="input-field col s12 m6 tooltipped" data-position="top"
                                         data-delay="50" data-html="true" data-tooltip="<div style='max-width: 300px'>@lang('tooltips.suggestions_timeframe')</div>">
                                        <input type="text" placeholder="@lang('labels.suggestions_timeframe') @lang('labels.' . $type)"
                                               name="suggestions[{{ $type }}][timeframe_start]" @if($terrarium->getSuggestionTimeframe($type)) value="{{ $terrarium->getSuggestionTimeframe($type) }}" @else value="1" @endif>
                                        <label for="suggestions[{{ $type }}][timeframe_start]">
                                            @lang('labels.suggestions_timeframe')
                                        </label>
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
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/terraria/{{ $terrarium->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/terraria/{{ $terrarium->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/terraria/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop