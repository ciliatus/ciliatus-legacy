@extends('master')

@section('breadcrumbs')
    <a href="/terraria" class="breadcrumb">@choice('components.terraria', 2)</a>
    <a href="/terraria/create" class="breadcrumb">@lang('buttons.create')</a>
@stop

@section('content')
    <div class="col s12 m12 l6">
        <div class="card">
            <form name="f_edit_terra" action="{{ url('api/v1/terraria') }}" data-method="POST" data-redirect-success="auto">
                <div class="card-content">

                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" placeholder="@lang('labels.display_name')" name="display_name" value="">
                            <label for="display_name">@lang('labels.display_name')</label>
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