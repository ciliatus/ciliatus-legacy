@extends('master')

@section('breadcrumbs')
    <a href="/pumps" class="breadcrumb hide-on-small-and-down">@choice('components.pumps', 2)</a>
    <a href="/pumps/{{ $pump->id }}" class="breadcrumb hide-on-small-and-down">{{ $pump->name }}</a>
    <a href="/pumps/{{ $pump->id }}/delete" class="breadcrumb hide-on-small-and-down">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/pumps/' . $pump->id) }}"
                          data-method="DELETE" data-redirect-success="{{ url('pumps') }}">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $pump->name }}</span>
                            </span>

                            <p>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly placeholder="ID" name="id" value="{{ $pump->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.name')" readonly name="display_name" value="{{ $pump->name }}">
                                    <label for="name">@lang('labels.name')</label>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light red" type="submit">@lang('buttons.delete')
                                        <i class="material-icons right">delete</i>
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