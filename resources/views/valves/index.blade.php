@extends('master')

@section('content')

    <div class="row">
        @include('valves.dashboard_slice', ['valves' => $valves])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop