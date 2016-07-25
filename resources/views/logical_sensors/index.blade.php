@extends('master')

@section('content')

    <div class="row">
        @include('logical_sensors.dashboard_slice', ['logical_sensors' => $logical_sensors])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop