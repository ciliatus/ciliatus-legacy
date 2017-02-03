@extends('master')

@section('breadcrumbs')
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/action_sequences/stop_all') }}" data-method="POST"
                          data-redirect-success="auto">
                        <div class="card-content">

                            <div class="row">
                                <div class="col s12">
                                    <strong>@lang('tooltips.emergency_stop')</strong>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn red btn-large waves-effect waves-light" type="submit">@lang('buttons.emergency_stop')
                                        <i class="material-icons red right">power_settings_new</i>
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
