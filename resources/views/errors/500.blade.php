@extends('errors.master')

@section('error_id')
    Error 500
@stop

@section('error_text')
    Server Error
@stop

@section('error_description')
    {{ $exception->getMessage() }}
@stop