@extends('master')

@section('content')

    <div class="row">
        @include('terraria.dashboard_slice', ['terraria' => [$terrarium], 'show_extended' => true])
        @include('terraria.details_slice', ['terraria' => [$terrarium]])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop