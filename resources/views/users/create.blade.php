@extends('master')

@section('breadcrumbs')
    <a href="/users" class="breadcrumb">@lang('menu.users')</a>
    <a href="/users/create" class="breadcrumb">@lang('labels.create')</a>
@stop

@section('content')
    <div class="col s12 m12 l6">
        <div class="card">
            <form action="{{ url('api/v1/users') }}" data-method="POST" data-redirect-success="auto">
                <div class="card-content">

                    <div class="row">
                        <div class="input-field col s12">
                            <input class="validate" required type="text" placeholder="@lang('labels.name')" name="name" value="">
                            <label for="name">@lang('labels.name')</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input class="validate" required type="email" placeholder="@lang('labels.email')" name="email" value="">
                            <label for="email">@lang('labels.email')</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input class="validate" required type="password" placeholder="@lang('labels.password')" name="password" value="">
                            <label for="password">@lang('labels.password')</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input class="validate" required type="password" placeholder="@lang('labels.password')" name="password_2" value="">
                            <label for="password_2">@lang('labels.password')</label>
                        </div>
                    </div>

                </div>

                <div class="card-action">

                    <div class="row">
                        <div class="input-field col s12">
                            <button class="btn waves-effect waves-light" type="submit">@lang('buttons.next')
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@stop