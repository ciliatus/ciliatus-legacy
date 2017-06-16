@extends('errors.master')

@section('error_id')
    Error 503
@stop

@section('error_text')
    Service unavailable
@stop

@section('error_description')
    {{ $exception->getMessage() }}
@stop