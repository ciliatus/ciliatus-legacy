@extends('setup.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 center-align">
                <h1 class="thin">@lang('setup.create_user')</h1>
            </div>
            <div class="col s12 offset-m3 m6">
                <div class="card">
                    <form action="{{ url('api/v1/setup/' . env('APP_KEY') . '/step/1') }}"
                          data-method="POST" data-redirect-success="{{ url('setup/' . env('APP_KEY') . '/step/2') }}">
                        <div class="card-content">
                            <div>
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

                        </div>
                        <div class="card-action right-align">
                            <button type="submit" class="btn waves-effect waves-light">
                                <i class="material-icons">navigate_next</i>
                                @lang('buttons.next')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop