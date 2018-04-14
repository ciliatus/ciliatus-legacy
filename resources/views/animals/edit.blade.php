@extends('master')

@section('breadcrumbs')
    <a href="/animals" class="breadcrumb hide-on-small-and-down">@choice('labels.animals', 2)</a>
    <a href="/animals/{{ $animal->id }}" class="breadcrumb hide-on-small-and-down">{{ $animal->display_name }}</a>
    <a href="/animals/{{ $animal->id }}/edit" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <form action="{{ url('api/v1/animals/' . $animal->id) }}" data-method="PUT">
                <div class="card">
                    <div class="card-content">

                        <span class="card-title activator truncate">
                            <span>{{ $animal->display_name }}</span>
                        </span>

                        <div class="row">
                            <div class="col s12 m6 l6">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" readonly="readonly" placeholder="ID" name="id" value="{{ $animal->id }}">
                                        <label for="id">ID</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" placeholder="@lang('labels.display_name')" name="display_name" value="{{ $animal->display_name }}">
                                        <label for="display_name">@lang('labels.display_name')</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" placeholder="@lang('labels.common_name')" name="common_name" value="{{ $animal->common_name }}"
                                               id="animal-common-name" class="autocomplete">
                                        <label for="name">@lang('labels.common_name')</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" placeholder="@lang('labels.latin_name')" name="latin_name" value="{{ $animal->lat_name }}"
                                               id="animal-latin-name" class="autocomplete">
                                        <label for="name">@lang('labels.latin_name')</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col s12">
                                        <label for="active">@lang('labels.active')</label>
                                        <div class="switch">
                                            <label>
                                                @lang('labels.off')
                                                <input name="active" type="hidden" value="off">
                                                <input name="active" type="checkbox" value="on"
                                                       @if($animal->active()) checked @endif>
                                                <span class="lever"></span>
                                                @lang('labels.on')
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col s12 m6 l6">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" class="datepicker" placeholder="@lang('labels.date_birth')"
                                               name="birth_date" id="birthdate"
                                               @if ($animal->birth_date) value="{{ $animal->birth_date->format('Y-m-d') }}" @endif>
                                        <label for="birthdate">@lang('labels.date_birth')</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" class="datepicker" placeholder="@lang('labels.date_death')"
                                               name="death_date" id="deathdate"
                                               @if ($animal->death_date) value="{{ $animal->death_date->format('Y-m-d') }}" @endif>
                                        <label for="deathdate">@lang('labels.date_death')</label>
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
                                        <label for="valves">@choice('labels.terraria', 1)</label>
                                    </div>
                                </div>
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
                </div>

            </form>

        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large orange darken-4">
            <i class="mdi mdi-18px mdi-pencil"></i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/animals/{{ $animal->id }}"><i class="mdi mdi-18px mdi-information-outline"></i></a></li>
            <li><a class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.delete')" href="/animals/{{ $animal->id }}/delete"><i class="mdi mdi-24px mdi-delete"></i></a></li>
            <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/animals/create"><i class="mdi mdi-24px mdi-plus"></i></a></li>
        </ul>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                selectMonths: true, // Creates a dropdown to control month
                selectYears: true // Creates a dropdown of 15 years to control year
            });
            $('#animal-common-name').autocomplete({
                data: {
                    @foreach ($common_names as $n)
                    "{{ $n }}": null,
                    @endforeach
                },
                limit: 20,
                minLength: 1
            });
            $('#animal-latin-name').autocomplete({
                data: {
                    @foreach ($latin_names as $n)
                    "{{ $n }}": null,
                    @endforeach
                },
                limit: 20,
                minLength: 1
            });
        });
    </script>
@stop