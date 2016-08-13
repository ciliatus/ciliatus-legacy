@extends('master')

@section('content')
    <div class="col-md-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="material-icons">{{ $file->icon() }}</i> {{ $file->display_name }}</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="material-icons">expand_less</i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">settings</i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('files/' . $file->id . '/edit') }}"><i class="material-icons">mode_edit</i> @lang('menu.edit')</a>
                            </li>
                            <li>
                                <a href="{{ url('files/' . $file->id . '/delete') }}"><i class="material-icons">delete</i> @lang('menu.delete')</a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="col-xs-12">
                    <strong>@lang('labels.display_name'): </strong> {{ $file->display_name }}
                </div>
                <div class="col-xs-12">
                    <strong>@lang('labels.name'): </strong> {{ $file->name }}
                </div>
                <div class="col-xs-12">
                    <strong>MIME-Type: </strong> {{ $file->mimetype }}
                </div>
                <div class="col-xs-12">
                    <strong>@lang('labels.size') </strong> {{ $file->sizeReadable() }}
                </div>

                <div class="clearfix"></div>
                <div class="ln_solid"></div>

                <div class="col-xs-12">
                    <a class="btn btn-primary" href="{{ url('files/' . $file->id . '/download') }}">@lang('labels.download')</a>
                </div>
            </div>
        </div>

        <div class="x_panel">
            <div class="x_title">
                <h2>@lang('labels.properties')</h2>

                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="material-icons">expand_less</i></a>
                    </li>
                </ul>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                @foreach ($file->properties as $prop)
                    <div class="col-xs-12">
                        <strong>{{ $prop->name }}: </strong> {{ $prop->value }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @if(explode('/', $file->mimetype)[0] == 'image')
    <div class="col-md-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>@lang('labels.preview')</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <img src="{{ url('files/' . $file->id . '/download') }}" style="max-height: 100%; max-width: 100%;" />
            </div>
        </div>
    </div>
    @endif
@stop