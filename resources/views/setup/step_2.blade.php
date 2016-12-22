@extends('setup.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 offset-m3 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">@lang('setup.done')</span>

                        <div>
                            <div class="row">
                                <h5>@lang('setup.what_now')</h5>
                            </div>
                            <div class="row">
                                <a href="/auth/login"><strong>@lang('setup.login')</strong></a>
                                <p>@lang('setup.tooltip_login')</p>
                            </div>
                            <div class="row">
                                <a href="/terraria/create"><strong>@lang('setup.add_terrarium')</strong></a>
                                <p>@lang('setup.tooltip_add_terrarium')</p>
                            </div>
                            <div class="row">
                                <a href="/animals/create"><strong>@lang('setup.add_animal')</strong></a>
                                <p>@lang('setup.tooltip_add_animal')</p>
                            </div>
                            <div class="row">
                                <a href="https://github.com/matthenning/ciliatus/wiki/Model:-Controlunit"><strong>@lang('setup.setup_controlunit')</strong></a>
                                <p>@lang('setup.tooltip_setup_controlunit')</p>
                            </div>
                            <div class="row">
                                <a href="https://github.com/matthenning/ciliatus/wiki/Notifications"><strong>@lang('setup.setup_telegram')</strong></a>
                                <p>@lang('setup.tooltip_setup_telegram')</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop