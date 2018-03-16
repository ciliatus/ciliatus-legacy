@extends('setup.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 center-align">
                <h1 class="thin">@lang('setup.done')</h1>

                <p>
                    <br /><br />
                    <a class="btn btn-primary" href="/auth/login">
                        <i class="mdi mdi-18px mdi-security"></i>
                        @lang('labels.login')
                    </a>
                </p>
            </div>
        </div>
    </div>
@stop