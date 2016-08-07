@extends('master')

@section('content')

    <div class="row">
        @include('animals.dashboard_slice', ['animals' => [$animal]])
        @include('files.dashboard_slice', ['files' => $animal->files])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop