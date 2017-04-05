@extends('master')

@section('breadcrumbs')
    <a href="/users" class="breadcrumb hide-on-small-and-down">@choice('components.users', 2)</a>
    <a href="/users/{{ $user->id }}" class="breadcrumb hide-on-small-and-down">{{ $user->name }}</a>
    <a href="/users/{{ $user->id }}/edit" class="breadcrumb hide-on-small-and-down">@lang('buttons.edit')</a>
    <a href="#" class="breadcrumb hide-on-small-and-down">@lang('buttons.setup_telegram')</a>
@stop

@section('content')
    <script>
        domCallbacks['wizard_wait_for_telegram_contact'] = function(success, data, ld) {
            ld.cleanupRefs();
            if (success === true) {
                ld.stop();
                $('#loading-indicator').html('<h4><i class="material-icons">check</i></h4>');
                $('#next-button').removeAttr('disabled');
            }
        };
    </script>
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/users/' . $user->id) }}" data-method="PUT"
                          data-redirect-success="{{ url('/') }}">
                        <div class="card-content">

                            <span class="card-title activator truncate">
                                <span>@lang('tooltips.contact_bot')</span>
                            </span>

                            <div class="row">
                                <div class="col s12 center"
                                     data-livedata="true" data-livedatainterval="5"
                                     data-livedatasource="{{ url('api/v1/users/' . Auth::user()->id . '/setting/notifications_telegram_chat_id') }}"
                                     data-livedatacallback="wizard_wait_for_telegram_contact">
                                    <span>@lang('messages.users.setup_telegram_description')</span>
                                    <h4 class="text-white">{{ $token }}</h4>
                                    <div id="loading-indicator">
                                        <div class="preloader-wrapper small active">
                                            <div class="spinner-layer spinner-green-only">
                                                <div class="circle-clipper left">
                                                    <div class="circle"></div>
                                                </div><div class="gap-patch">
                                                    <div class="circle"></div>
                                                </div><div class="circle-clipper right">
                                                    <div class="circle"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-action">
                            <div class="row">
                                <div class="input-field col s12">
                                    <a href="{{ url('users/' . $user->id . '/edit') }}" class="btn waves-effect waves-light teal" disabled id="next-button">@lang('buttons.save')</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop