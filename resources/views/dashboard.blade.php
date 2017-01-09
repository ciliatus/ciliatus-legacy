@extends('master')

@section('content')
    <div class="container">
        <dashboard-widget :refresh-timeout-seconds="60" container-classes="row" wrapper-classes="col s12 m6 l6"></dashboard-widget>
    </div>
@stop