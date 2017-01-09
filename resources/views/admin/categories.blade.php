@extends('master')

@section('breadcrumbs')
    <a href="/categories" class="breadcrumb">@lang('menu.categories')</a>
@stop

@section('content')

    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s3"><a class="active" href="#tab_feeding_types">@lang('menu.animal_feeding_types')</a></li>
            <li class="tab col s3"><a href="#tab_bio_categories">@lang('labels.bio_categories')</a></li>
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
                                    <button type="submit" class="btn btn-small red darken-2 white-text">@lang('buttons.delete')</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large teal">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green" href="/animals/feedings/types/create"><i class="material-icons">add</i></a></li>
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
                                    <button type="submit" class="btn btn-small red darken-2 white-text">@lang('buttons.delete')</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large teal">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li><a class="btn-floating green" href="/biography_entries/categories/create"><i class="material-icons">add</i></a></li>
            </ul>
        </div>
    </div>

    <script>
        ($(function() {
            $(document).ready(function(){
                $('ul.tabs').tabs();
            });
        }));
    </script>
@stop