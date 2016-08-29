@extends('master')

@section('content')
    @include('animals.show_vue')

    <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-xs-12">
        @include('animals.show_vue')
        @include('animals.show_slice', ['animal' => $animal])
    </div>
    <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 col-xs-12">
        @include('animals.details_slice', ['animal' => $animal])
    </div>
@stop