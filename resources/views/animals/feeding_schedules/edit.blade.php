@extends('master')

@section('breadcrumbs')
    <a href="/animals" class="breadcrumb hide-on-small-and-down">@choice('components.animals', 2)</a>
    <a href="{{ url('animals/' . $animal->id) }}" class="breadcrumb hide-on-small-and-down">{{ $animal->display_name }}</a>
    <a href="#" class="breadcrumb hide-on-small-and-down">@choice('components.animal_feeding_schedules', 1)</a>
    <a href="#" class="breadcrumb hide-on-small-and-down">@lang('buttons.add')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/animals/' . $animal->id .'/feeding_schedules/' . $afs->id) }}" data-method="PUT" >
                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@choice('components.animals', 1)" name="animal" value="{{ $animal->display_name }}" readonly>
                                    <label for="animal">@choice('components.animals', 1)</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.interval_days')" name="interval_days" value="{{ $afs->value }}">
                                    <label for="interval_days">@lang('labels.interval_days')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="meal_type" id="meal_type">
                                        @foreach($feeding_types as $ft)
                                            <option value="{{ $ft->name }}" @if($afs->name == $ft->name) selected @endif>{{ $ft->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="meal_type">@lang("labels.meal_type")</label>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="material-icons right">save</i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating red" href="/animals/{{ $animal->id }}/feeding_schedules/{{ $afs->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/animals/{{ $animal->id }}/feeding_schedules/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop