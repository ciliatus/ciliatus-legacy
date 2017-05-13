@extends('master')

@section('breadcrumbs')
    <a href="/users" class="breadcrumb hide-on-small-and-down">@choice('components.users', 2)</a>
    <a href="/users/{{ $user->id }}" class="breadcrumb hide-on-small-and-down">{{ $user->name }}</a>
    <a href="/users/{{ $user->id }}/delete" class="breadcrumb hide-on-small-and-down">@lang('buttons.delete')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/users/' . $user->id) }}"
                          data-method="DELETE" data-redirect-success="{{ url('users') }}">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>{{ $user->name }}</span>
                            </span>

                            <p>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly placeholder="ID" name="id" value="{{ $user->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.name')" readonly name="display_name" value="{{ $user->name }}">
                                    <label for="name">@lang('labels.name')</label>
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