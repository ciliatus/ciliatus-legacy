@extends('setup.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 offset-m3 m6">
                <div class="card">
                    <form action="{{ url('api/v1/setup/' . env('APP_KEY') . '/step/0') }}"
                          data-method="POST" data-redirect-success="{{ url('setup/' . env('APP_KEY') . '/step/1') }}">
                    <div class="card-content">
                        <span class="card-title">@lang('setup.welcome')</span>

                        <div class="row">
                            <div class="input-field col s12">
                                <select name="language">
                                    <option value="en" selected>@lang('languages.english')</option>
                                    <option value="de">@lang('languages.german')</option>
                                </select>
                                <label for="language">@lang('labels.language')</label>
                            </div>
                        </div>

                    </div>
                    <div class="card-action right-align">
                        <button type="submit" class="btn waves-effect waves-light">@lang('buttons.save')</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop