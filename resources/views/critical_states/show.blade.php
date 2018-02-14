@extends('master')

@section('breadcrumbs')
    <a href="/critical_states" class="breadcrumb hide-on-small-and-down">@lang('labels.criticalstates')</a>
    <a href="/critical_states/{{ $critical_state->id }}" class="breadcrumb hide-on-small-and-down">{{ $critical_state->name }}</a>
@stop

@section('content')
    <div class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12 m12 l7">
                    <div class="card">
                        <div class="card-header">
                            <i class="material-icons">error_outline</i>
                            @choice('labels.critical_states', 1)
                        </div>

                        <div class="card-content">
                            <span class="card-title activator">
                                {{ $critical_state->name }}
                            </span>

                            <strong>@lang('labels.created_at'):</strong> {{ $critical_state->created_at }}<br />
                            <strong>@choice('labels.notifications', 2):</strong> {{ $critical_state->notifications_sent_at }}<br />
                            <strong>@lang('labels.recovered_at'):</strong> {{ $critical_state->recovered_at }}<br />
                            <strong>@lang('labels.reason'):</strong> {{ $critical_state->state_details }}<br />
                            <strong>@lang('labels.soft_state'):</strong> @if($critical_state->is_soft_state) @lang('labels.yes') @else @lang('labels.no') @endif<br />
                            <strong>@lang('labels.source'):</strong><br />
                            <?php $obj = $critical_state; $first=true; ?>
                            @while(method_exists($obj, 'belongsTo_object') && !is_null($obj->belongsTo_object()))
                                <div>
                                <?php $obj = $obj->belongsTo_object(); ?>
                                    @if(!$first)
                                        <i class="material-icons" style="padding-left: 7px">subdirectory_arrow_right</i>
                                    @endif
                                    <a href="{{ $obj->url() }}">
                                        <i class="material-icons">{{ $obj->icon() }}</i>
                                        @if($obj->display_name)
                                            {{ $obj->display_name }}
                                        @else
                                            {{ $obj->name }}
                                        @endif
                                    </a>
                                    @if(is_a($obj, 'App\\LogicalSensor'))
                                        <i>@lang('labels.type'): @lang('labels.' . $obj->type)</i>
                                    @endif
                                <?php $first = false; ?>
                                </div>
                            @endwhile
                            <strong>@lang('labels.possibly_affected_animals'):</strong>
                            <i class="material-icons tooltipped" data-position="top" data-delay="50" data-html="true"
                               data-tooltip="<div style='width: 300px'>@lang('tooltips.critical_state_actuality')</div>">help_outline</i>
                            <br />
                            @foreach ($critical_state->getPossiblyAffectedAnimals() as $animal)
                                <div>
                                    <a href="{{ $animal->url() }}">
                                        <i class="material-icons">pets</i> {{ $animal->display_name }}
                                    </a>
                                    <i>{{ $animal->lat_name }}</i>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop