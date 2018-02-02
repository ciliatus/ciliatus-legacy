@extends('master')

@section('breadcrumbs')
    <a href="/users" class="breadcrumb hide-on-small-and-down">@lang('menu.users')</a>
    <a href="/users/{{ $user->id }}/edit" class="breadcrumb hide-on-small-and-down">{{ $user->email }}</a>
    <a class="breadcrumb hide-on-small-and-down">@lang('labels.personal_access_tokens')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/users/' . $user->id . '/personal_access_tokens') }}"
                          data-method="POST" data-callback="showToken">
                        <div class="card-content">

                            <div class="row" id="input-row">
                                <div class="input-field col s12">
                                    <input class="validate" required type="text" placeholder="@lang('labels.name')" name="name" value="">
                                    <label for="name">@lang('labels.name')</label>
                                </div>
                            </div>

                            <div class="row" id="result-row" style="display: none; overflow-wrap: break-word;">
                                <h4>@lang('labels.personal_access_token')</h4>
                                <p id="result-content"></p>
                            </div>

                        </div>

                        <div class="card-action" id="button-row">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="material-icons left">save</i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                    <script>
                        window.showToken = function(data) {
                            $('#input-row').remove();
                            $('#button-row').remove();
                            $('#result-content').html(data.data.token);
                            $('#result-row').show();
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
@stop