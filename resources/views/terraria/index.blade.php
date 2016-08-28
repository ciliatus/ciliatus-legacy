@extends('master')

@section('content')
    @include('terraria.dashboard_vue')

    <div>
        <terraria-widget></terraria-widget>
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop