@extends('master')

@section('breadcrumbs')
    <a href="/animal_feeding_schedules" class="breadcrumb">@choice('components.animal_feeding_schedules', 2)</a>
@stop


@section('content')
    <div class="container">
        <p>@lang('tooltips.animal_feeding_schedule_matrix')</p>
        <animal_feeding_schedules-matrix-widget></animal_feeding_schedules-matrix-widget>
    </div>
@stop