@extends('master')

@section('content')

    <div class="row">
        @include('valves.dashboard_slice', ['valves' => [$valve], 'show_extended' => true])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop