@extends('master')

@section('breadcrumbs')
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/action_sequences/resume_all') }}" data-method="POST"
                          data-redirect-success="auto">
                        <div class="card-content">

                            <div class="row">
                                <div class="col s12">
                                    <strong>@lang('tooltips.emergency_resume')</strong>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn green btn-large waves-effect waves-light" type="submit">@lang('buttons.emergency_resume')
                                        <i class="material-icons green right">power_settings_new</i>
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
