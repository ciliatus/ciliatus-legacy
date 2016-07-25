@extends('master')

@section('content')

    <div class="row">
        @include('physical_sensors.dashboard_slice', ['physical_sensors' => $physical_sensors])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop