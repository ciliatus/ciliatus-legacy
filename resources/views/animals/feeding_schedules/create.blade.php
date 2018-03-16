@extends('master')

@section('breadcrumbs')
    <a href="/animals" class="breadcrumb hide-on-small-and-down">@choice('labels.animals', 2)</a>
    <a href="{{ url('animals/' . $animal->id) }}" class="breadcrumb hide-on-small-and-down">{{ $animal->display_name }}</a>
    <a href="#!" class="breadcrumb hide-on-small-and-down">@choice('labels.animal_feeding_schedules', 1)</a>
    <a href="#!" class="breadcrumb hide-on-small-and-down">@lang('buttons.add')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/animals/' . $animal->id .'/feeding_schedules') }}" data-method="POST" data-redirect-success="auto">
                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@choice('labels.animals', 1)" name="animal" value="{{ $animal->display_name }}" readonly>
                                    <label for="animal">@choice('labels.animals', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.interval_days')" name="interval_days" value="">
                                    <label for="interval_days">@lang('labels.interval_days')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="meal_type" id="meal_type">
                                    @foreach($feeding_types as $ft)
                                        <option value="{{ $ft->name }}">{{ $ft->name }}</option>
                                    @endforeach
                                    </select>
                                    <label for="meal_type">@lang("labels.meal_type")</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input class="datepicker" type="date" placeholder="@lang('labels.starts_at')" name="starts_at"
                                           data-default="{{ Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                                    <label for="starts_at">@lang('labels.starts_at')</label>
                                </div>
                            </div>
                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="mdi mdi-18px mdi-floppy left"></i>
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

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.datepicker').pickadate({
                format: 'yyyy-mm-dd',
            });
        });
    </script>
@stop