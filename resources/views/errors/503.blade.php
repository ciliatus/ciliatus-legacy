@extends('errors.master')

@section('error_id')
    @if(!App::isDownForMaintenance())
        Error 503
    @endif
@stop

@section('error_text')
    @if(App::isDownForMaintenance())
        Down for Maintenance
    @else
        Service unavailable
    @endif
@stop

@section('error_description')
    @if(App::isDownForMaintenance())
        @if (env('DEMO_ENVIRONMENT', false) == true)
            Rebuilding demo environment. This will take only a few minutes.
        @else
            Be right back!
        @endif
    @else
        {{ $exception->getMessage() }}
    @endif
@stop