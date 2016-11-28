@extends('master')

@section('breadcrumbs')
    <a href="/animals" class="breadcrumb">@choice('components.animals', 2)</a>
    <a href="/animals/{{ $animal->id }}" class="breadcrumb">{{ $animal->display_name }}</a>
    <a href="/animals/{{ $animal->id }}/delete" class="breadcrumb">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="col s12 m12 l6">
        <div class="card">
            <form action="{{ url('api/v1/animals/' . $animal->id) }}"
                  data-method="DELETE" data-redirect-success="{{ url('animals') }}">
                <div class="card-content">

                    <span class="card-title activator grey-text text-darken-4 truncate">
                        <span>{{ $animal->display_name }}</span>
                    </span>

                    <p>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" readonly placeholder="ID" name="id" value="{{ $animal->id }}">
                            <label for="id">ID</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" placeholder="@lang('labels.display_name')" readonly name="display_name" value="{{ $animal->display_name }}">
                            <label for="display_name">@lang('labels.display_name')</label>
                        </div>
                    </div>

                </div>

                <div class="card-action">

                    <div class="row">
                        <div class="input-field col s12">
                            <button class="btn waves-effect waves-light red" type="submit">@lang('buttons.delete')
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@stop