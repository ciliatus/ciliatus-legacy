@extends('master')

@section('breadcrumbs')
    <a href="/animals" class="breadcrumb hide-on-small-and-down">@choice('labels.animals', 2)</a>
    <a href="{{ url('animals/' . $animal->id) }}" class="breadcrumb hide-on-small-and-down">{{ $animal->display_name }}</a>
    <a href="#!" class="breadcrumb hide-on-small-and-down">@choice('labels.animal_weighing_schedules', 1)</a>
    <a href="#!" class="breadcrumb hide-on-small-and-down">@lang('buttons.add')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/animals/' . $animal->id .'/weighing_schedules/' . $aws->id) }}" data-method="PUT" >
                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@choice('labels.animals', 1)" name="animal" value="{{ $animal->display_name }}" readonly>
                                    <label for="animal">@choice('labels.animals', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.interval_days')" name="interval_days" value="{{ $aws->value }}">
                                    <label for="interval_days">@lang('labels.interval_days')</label>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">

                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="mdi mdi-18px mdi-floppy left"></i>
                                    </button>

                                    <a class="right btn waves-effect waves-light red" href="{{ url('animals/' . $animal->id .'/weighing_schedules/' . $aws->id . '/delete') }}">@lang('buttons.delete')
                                        <i class="mdi mdi-18px mdi-delete left"></i>
                                    </a>

                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop