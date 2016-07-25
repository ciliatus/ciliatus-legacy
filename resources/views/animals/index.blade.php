@extends('master')

@section('content')

    <div class="row">
        @include('animals.dashboard_slice', ['animals' => $animals])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop