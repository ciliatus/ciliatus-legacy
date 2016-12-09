@extends('master')

@section('breadcrumbs')
    <a href="/animals" class="breadcrumb">@choice('components.animals', 2)</a>
    <a href="{{ url('animals/' . $animal->id) }}" class="breadcrumb">{{ $animal->display_name }}</a>
    <a href="#" class="breadcrumb">@choice('components.animal_weighing_schedules', 1)</a>
    <a href="#" class="breadcrumb">@lang('buttons.add')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/animals/' . $animal->id .'/weighing_schedules') }}" data-method="POST" data-redirect-success="auto">
                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@choice('components.animals', 1)" name="animal" value="{{ $animal->display_name }}" readonly>
                                    <label for="animal">@choice('components.animals', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.interval_days')" name="interval_days" value="">
                                    <label for="interval_days">@lang('labels.interval_days')</label>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.next')
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop