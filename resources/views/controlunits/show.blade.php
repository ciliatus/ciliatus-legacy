@extends('master')

@section('content')

    <div class="row">
        @include('controlunits.dashboard_slice', ['controlunits' => [$controlunit], 'show_extended' => true])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop