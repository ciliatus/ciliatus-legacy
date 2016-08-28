@extends('master')

@section('content')
    @include('terraria.dashboard_vue')
    @include('critical_states.dashboard_vue')

    <div id="dashboard">
        <criticalstates-widget></criticalstates-widget>
        <terraria-widget></terraria-widget>
    </div>

    <script>
        $(function() {
            runPage();

            if ($(window).width() < 992) { //bootstrap md
                setTimeout(function() {
                    $('.x_content').fadeOut(1000);
                }, 100);
            }
        });
    </script>
@stop