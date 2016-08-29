@extends('master')

@section('content')
    @include('animals.dashboard_vue')

    <div>
        <animals-widget></animals-widget>
    </div>
@stop