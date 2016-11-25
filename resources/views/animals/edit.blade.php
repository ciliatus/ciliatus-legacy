@extends('master')

@section('breadcrumbs')
<a href="/animals" class="breadcrumb">@choice('components.animals', 2)</a>
<a href="/animals/{{ $animal->id }}" class="breadcrumb">{{ $animal->display_name }}</a>
<a href="/animals/{{ $animal->id }}/edit" class="breadcrumb">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="col s12 m12 l6">
        <div class="card">
            <form name="f_edit_terra" action="{{ url('api/v1/animals/' . $animal->id) }}" data-method="PUT"
                  data-redirect-success="{{ url('terraria/' . $animal->id) }}">
                <div class="card-content">

                    <span class="card-title activator grey-text text-darken-4 truncate">
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
                            <input type="text" placeholder="@lang('labels.latin_name')" name="latinname" value="{{ $animal->latin_name }}">
                            <label for="name">@lang('labels.latin_name')</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input type="date" class="datepicker" placeholder="@lang('labels.date_birth')" name="birthdate" value="{{ $animal->birth_date }}">
                            <label for="name">@lang('labels.date_birth')</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input type="date" class="datepicker" placeholder="@lang('labels.date_death')" name="deathdate" value="{{ $animal->death_date }}">
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
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                    </div>

                </div>
            </form>
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
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/animals/{{ $animal->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red" href="/animals/{{ $animal->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/animals/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop