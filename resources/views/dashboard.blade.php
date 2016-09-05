@extends('master')

@section('content')
    @include('terraria.dashboard_vue')

    <div id="dashboard">
        <terraria-widget></terraria-widget>
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