@extends('master')

@section('breadcrumbs')
    <a href="/animals" class="breadcrumb">@choice('components.animals', 2)</a>
    <a href="#" class="breadcrumb">@choice('components.animal_feedings', 1)</a>
    <a href="#" class="breadcrumb">@lang('labels.type')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <table>
                    <thead>
                    <tr>
                        <th data-field="type">@lang('labels.type')</th>
                        <th data-field="actions">@lang('labels.actions')</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($types as $type)
                    <tr>
                        <td>{{ $type->name }}</td>
                        <td>
                            <form action="/api/v1/properties/{{ $type->id }}" data-method="DELETE" data-redirect-success="/animals/feedings/types">
                                <button type="submit" class="btn btn-small red darken-2 white-text">@lang('buttons.delete')</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
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
@stop