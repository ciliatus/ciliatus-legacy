@extends('master')

@section('content')

    <div class="row">
        @include('terraria.dashboard_slice', ['terraria' => [$terrarium]])
        @include('terraria.details_slice', ['terraria' => [$terrarium]])
        @include('files.dashboard_slice', ['files' => $terrarium->files])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop