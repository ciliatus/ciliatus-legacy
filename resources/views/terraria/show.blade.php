@extends('master')

@section('content')

    <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-xs-12 dashboard-box">
        @include('terraria.show_vue')
        @include('terraria.show_slice', ['terrarium' => $terrarium])

        @if(!is_null($terrarium->animals))
            @include('animals.show_vue')
            @foreach($terrarium->animals as $animal)
                @include('animals.show_slice', ['animal' => $animal])
            @endforeach
        @endif

        @if(!is_null($terrarium->action_sequences))
            @foreach ($terrarium->action_sequences as $ass)
                @include('action_sequences.show_mini_slice', ['action_sequence' => $ass, 'readonly' => true])
            @endforeach
        @endif
    </div>

    <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 col-xs-12 dashboard-box">
        @include('terraria.details_slice', ['terraria' => [$terrarium]])
    </div>

    <script>
        $(function() {
            runPage();
        });
    </script>
@stop