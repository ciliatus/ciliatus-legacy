@extends('master')

@section('breadcrumbs')
    <a href="/animals" class="breadcrumb">@choice('components.animals', 2)</a>
@stop


@section('content')
    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s3"><a class="active" href="#tab_dashboard">@lang('labels.overview')</a></li>
            <li class="tab col s3"><a href="#tab_feeding_schedules">@choice('components.animal_feeding_schedules', 2)</a></li>
            <li class="tab col s3"><a href="#tab_weighing_schedules">@choice('components.animal_weighing_schedules', 2)</a></li>
        </ul>
    </div>

    <div id="tab_dashboard" class="col s12">
        <div class="container">
            <animals-widget container-classes="row" wrapper-classes="col s12 m6 l4"></animals-widget>
        </div>
    </div>

    <div id="tab_feeding_schedules" class="col s12">
        <div class="container">
            <p>@lang('tooltips.animal_feeding_schedule_matrix')</p>
            <animal_feeding_schedules-matrix-widget container-classes="row" wrapper-classes=""></animal_feeding_schedules-matrix-widget>
        </div>
    </div>

    <div id="tab_weighing_schedules" class="col s12">
        <div class="container">
            <p>@lang('tooltips.animal_weighing_schedule_matrix')</p>
            <animal_weighing_schedules-matrix-widget container-classes="row" wrapper-classes=""></animal_weighing_schedules-matrix-widget>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green" href="/animals/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop