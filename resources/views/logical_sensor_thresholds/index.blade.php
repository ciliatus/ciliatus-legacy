@extends('master')

@section('content')

    <div class="row">
        @include('logical_sensor_thresholds.dashboard_slice', ['logical_sensor_thresholds' => $logical_sensor_thresholds])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop