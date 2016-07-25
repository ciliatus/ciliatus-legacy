@extends('master')

@section('content')

    <div class="row">
        @include('controlunits.dashboard_slice', ['controlunits' => $controlunits])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop