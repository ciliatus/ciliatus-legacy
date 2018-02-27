@extends('master')

@section('breadcrumbs')
    <a href="/animals/{{ $animal->id }}" class="breadcrumb hide-on-small-and-down">{{ $animal->display_name }}</a>
    <a href="/animals/{{ $animal->id }}/weighings/" class="breadcrumb hide-on-small-and-down">@choice('labels.animal_weighing_schedules', 1)</a>
    <a href="/animals/{{ $animal->id }}/weighings/{{ $animal_weighing->id }}/delete" class="breadcrumb hide-on-small-and-down">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/animals/' . $animal->id . '/weighings/' . $animal_weighing->id) }}"
                          data-method="DELETE" data-redirect-success="{{ url('animals/' . $animal->id . '#tab_health_weight') }}">
                        <div class="card-content">
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly placeholder="ID" name="id" value="{{ $animal_weighing->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly placeholder="name" name="name" value="{{ $animal_weighing->name }}">
                                    <label for="name">@lang('labels.name')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly placeholder="created_at" name="created_at" value="{{ $animal_weighing->created_at }}">
                                    <label for="created_at">@lang('labels.created_at')</label>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light red" type="submit">@lang('buttons.delete')
                                        <i class="material-icons left">delete</i>
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