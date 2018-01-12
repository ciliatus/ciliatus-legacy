@extends('master')

@section('breadcrumbs')
    <a href="/categories" class="breadcrumb hide-on-small-and-down">@lang('menu.categories')</a>
@stop

@section('content')

    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s3"><a class="active" href="#tab_feeding_types">@lang('menu.animal_feeding_types')</a></li>
            <li class="tab col s3"><a href="#tab_bio_categories">@lang('labels.bio_categories')</a></li>
            <li class="tab col s3"><a href="#tab_generic_components_types">@choice('components.generic_component_types', 2)</a></li>
        </ul>
    </div>

    <div id="tab_feeding_types" class="col s12">
        <div class="container">
            <div class="row">
                <table>
                    <thead>
                    <tr>
                        <th data-field="type">@lang('menu.animal_feeding_types')</th>
                        <th data-field="actions">@lang('labels.actions')</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($animal_feeding_types as $type)
                        <tr>
                            <td>{{ $type->name }}</td>
                            <td>
                                <form action="/api/v1/animals/feedings/types/{{ $type->id }}" data-method="DELETE" data-redirect-success="/categories#tab_feeding_types">
                                    <a class="red-text text-lighten-1" href="#!" onclick="$(this).closest('form').submit();">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large orange darken-4">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/animals/feedings/types/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_bio_categories" class="col s12">
        <div class="container">
            <div class="row">
                <table>
                    <thead>
                    <tr>
                        <th data-field="type">@lang('labels.bio_categories')</th>
                        <th data-field="icon">@lang('labels.icon')</th>
                        <th data-field="actions">@lang('labels.actions')</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($bio_categories as $type)
                        <tr>
                            <td>{{ $type->name }}</td>
                            <td><i class="material-icons">{{ $type->value }}</i></td>
                            <td>
                                <form action="/api/v1/biography_entries/categories/{{ $type->id }}" data-method="DELETE" data-redirect-success="/categories#tab_bio_categories">
                                    <a class="red-text text-lighten-1" href="#!" onclick="$(this).closest('form').submit();">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large orange darken-4">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/biography_entries/categories/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <div id="tab_generic_components_types" class="col s12">
        <div class="container">
            <div class="row">
                <br />
                @lang('tooltips.generic_components.type_about')
            </div>
            <div class="row">
                <table>
                    <thead>
                    <tr>
                        <th data-field="type">@choice('components.generic_component_types', 1)</th>
                        <th data-field="icon">@lang('labels.icon')</th>
                        <th data-field="actions">@lang('labels.actions')</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($generic_component_types as $type)
                        <tr>
                            <td><a href="/generic_component_types/{{ $type->id }}">{{ $type->name_singular }}</a></td>
                            <td><i class="material-icons">{{ $type->icon }}</i></td>
                            <td>
                                <a href="/generic_component_types/{{ $type->id }}/edit"><i class="material-icons">edit</i></a>
                                <a class="red-text text-lighten-1" href="/generic_component_types/{{ $type->id }}/delete"><i class="material-icons">delete</i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large orange darken-4">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green tooltipped" data-position="left" data-delay="50" data-tooltip="@lang('tooltips.floating.add')" href="/generic_component_types/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>
@stop