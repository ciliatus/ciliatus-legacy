@extends('master')

@section('content')

    <div class="row">
        @include('pumps.dashboard_slice', ['pumps' => [$pump], 'show_extended' => true])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop