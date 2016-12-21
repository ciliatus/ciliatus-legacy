@extends('master')

@section('breadcrumbs')
    <a href="/animal_weighing_schedules" class="breadcrumb">@choice('components.animal_weighing_schedules', 2)</a>
@stop


@section('content')
    <div class="container">
        <p>@lang('tooltips.animal_weighing_schedule_matrix')</p>
        <animal_weighing_schedules-matrix-widget></animal_weighing_schedules-matrix-widget>
    </div>
@stop