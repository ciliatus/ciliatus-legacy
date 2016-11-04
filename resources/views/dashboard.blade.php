@extends('master')

@section('content')

    <div id="dashboard">
        @include('terraria.dashboard_vue')
        <terraria-widget></terraria-widget>
        @include('action_sequence_schedules.dashboard_vue')
        <action_sequence_schedule-widget></action_sequence_schedule-widget>
    </div>


    <script>
        $(function() {
            if ($(window).width() < 992) { //bootstrap md
                setTimeout(function() {
                    $('.x_content').fadeOut(1000);
                }, 100);
            }
        });
    </script>
@stop