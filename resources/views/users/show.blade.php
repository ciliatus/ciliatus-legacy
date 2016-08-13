@extends('master')

@section('content')
    <div class="col-md-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{ $user->email }}</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="material-icons">expand_less</i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">settings</i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('users/' . $user->id . '/edit') }}"><i class="material-icons">mode_edit</i> @lang('menu.edit')</a>
                            </li>
                            <li>
                                <a href="{{ url('users/' . $user->id . '/delete') }}"><i class="material-icons">delete</i> @lang('menu.delete')</a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="col-xs-12">
                    <strong>@lang('labels.email'): </strong> {{ $user->email }}
                </div>
                <div class="col-xs-12">
                    <strong>@lang('labels.name'): </strong> {{ $user->name }}
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@stop