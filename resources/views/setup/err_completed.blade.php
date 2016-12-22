@extends('setup.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 offset-m3 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">@lang('setup.welcome')</span>

                        <p>
                            @lang('setup.err_completed')
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop