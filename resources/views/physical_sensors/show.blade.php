@extends('master')

@section('content')

    <div class="row">
        @include('physical_sensors.dashboard_slice', ['physical_sensors' => [$physical_sensor], 'show_extended' => true])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop