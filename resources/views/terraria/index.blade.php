@extends('master')

@section('content')

    <div class="row">
        @include('terraria.dashboard_slice', ['terraria' => $terraria])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop