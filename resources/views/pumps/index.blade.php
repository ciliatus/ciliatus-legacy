@extends('master')

@section('content')

    <div class="row">
        @include('pumps.dashboard_slice', ['pumps' => $pumps])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop