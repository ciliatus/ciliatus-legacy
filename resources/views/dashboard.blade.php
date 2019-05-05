@extends('master')

@section('content')
    <div class="container">
        <dashboard-widget :refresh-timeout-seconds="60"></dashboard-widget>
    </div>
@stop