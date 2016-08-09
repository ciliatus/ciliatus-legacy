@extends('master')

@section('content')

    <div class="row">
        @include('logical_sensors.dashboard_slice', ['logical_sensors' => [$logical_sensor], 'show_extended' => true])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop