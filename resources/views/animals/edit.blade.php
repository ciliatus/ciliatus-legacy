@extends('master')

@section('breadcrumbs')
    <a href="/animals" class="breadcrumb hide-on-small-and-down">@choice('components.animals', 2)</a>
    <a href="/animals/{{ $animal->id }}" class="breadcrumb hide-on-small-and-down">{{ $animal->display_name }}</a>
    <a href="/animals/{{ $animal->id }}/edit" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/animals/' . $animal->id) }}" data-method="PUT"
                          >
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $animal->display_name }}</span>
                            </span>

                            <p>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly="readonly" placeholder="ID" name="id" value="{{ $animal->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.display_name')" name="displayname" value="{{ $animal->display_name }}">
                                    <label for="display_name">@lang('labels.display_name')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.common_name')" name="commonname" value="{{ $animal->common_name }}">
                                    <label for="name">@lang('labels.common_name')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.latin_name')" name="latinname" value="{{ $animal->lat_name }}">
                                    <label for="name">@lang('labels.latin_name')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="date" class="datepicker" placeholder="@lang('labels.date_birth')"
                                           name="birthdate" id="birthdate"
                                           @if ($animal->birth_date) value="{{ $animal->birth_date->format('Y-m-d') }}" @endif>
                                    <label for="name">@lang('labels.date_birth')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="date" class="datepicker" placeholder="@lang('labels.date_death')"
                                           name="deathdate" id="deathdate"
                                           @if ($animal->death_date) value="{{ $animal->death_date->format('Y-m-d') }}" @endif>
                                    <label for="name">@lang('labels.date_death')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="gender">
                                        <option></option>
                                        <option value="male" @if($animal->gender == 'male')selected="selected"@endif>@lang('labels.gender_male')</option>
                                        <option value="female" @if($animal->gender == 'female')selected="selected"@endif>@lang('labels.gender_female')</option>
                                    </select>
                                    <label for="name">@lang('labels.gender')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="terrarium">
                                        <option></option>
                                        @foreach ($terraria as $t)
                                            <option value="{{ $t->id }}" @if($animal->terrarium_id == $t->id)selected="selected"@endif>{{ $t->display_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="valves">@choice('components.terraria', 2)</label>
                                </div>
                            </div>
                            </p>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="material-icons left">save</i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="col s12 m12 l6">

                <div class="card">

                    <div class="card-header">
                        <span>@choice('components.animal_feeding_schedules', 2)</span>
                    </div>

                    <div class="card-content">

                        <div class="row">

                            @foreach ($animal->feeding_schedules as $afs)
                                <div class="col s12">

                                    {{ $afs->name }} - {{ $afs->value }} @choice('units.days', $afs->value) @lang('labels.interval')

                                    <a href="{{ url('animals/' . $animal->id . '/feeding_schedules/' . $afs->id . '/delete') }}" class="right red-text text-lighten-1">
                                        <i class="material-icons">delete</i>
                                    </a>

                                    <a href="{{ url('animals/' . $animal->id . '/feeding_schedules/' . $afs->id . '/edit') }}" class="right">
                                        <i class="material-icons">edit</i>
                                    </a>

                                </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="card-action">
                        <a href="{{ url('/animals/' . $animal->id . '/feeding_schedules/create') }}">
                            @lang('buttons.add')
                        </a>
                    </div>

                </div>


                <div class="card">

                    <div class="card-header">
                        <span>@choice('components.animal_weighing_schedules', 2)</span>
                    </div>

                    <div class="card-content">

                        <div class="row">

                            @foreach ($animal->weighing_schedules as $afs)
                                <div class="col s12">

                                    {{ $afs->name }} - {{ $afs->value }} @choice('units.days', $afs->value) @lang('labels.interval')

                                    <a href="{{ url('animals/' . $animal->id . '/weighing_schedules/' . $afs->id . '/delete') }}" class="right red-text text-lighten-1">
                                        <i class="material-icons">delete</i>
                                    </a>

                                    <a href="{{ url('animals/' . $animal->id . '/weighing_schedules/' . $afs->id . '/edit') }}" class="right">
                                        <i class="material-icons">edit</i>
                                    </a>

                                </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="card-action">
                        <a href="{{ url('/animals/' . $animal->id . '/weighing_schedules/create') }}">
                            @lang('buttons.add')
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            $('.datepicker').pickadate({
                format: 'yyyy-mm-dd',
                selectMonths: true, // Creates a dropdown to control month
                selectYears: true // Creates a dropdown of 15 years to control year
            });
        });
    </script>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/animals/{{ $animal->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/animals/{{ $animal->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/animals/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop