@extends('master')

@section('breadcrumbs')
<a href="/pumps" class="breadcrumb">@choice('components.pumps', 2)</a>
<a href="/pumps/{{ $pump->id }}" class="breadcrumb">{{ $pump->display_name }}</a>
<a href="/pumps/{{ $pump->id }}/edit" class="breadcrumb">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="col s12 m12 l6">
        <div class="card">
            <form name="f_edit_terra" action="{{ url('api/v1/pumps/' . $pump->id) }}" data-method="PUT"
                  data-redirect-success="{{ url('terraria/' . $pump->id) }}">
                <div class="card-content">

                    <span class="card-title activator grey-text text-darken-4 truncate">
                        <span>{{ $pump->display_name }}</span>
                    </span>

                    <p>
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" readonly="readonly" placeholder="ID" name="id" value="{{ $pump->id }}">
                                <label for="id">ID</label>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" placeholder="@lang('labels.name')" name="name" value="{{ $pump->name }}">
                                <label for="name">@lang('labels.name')</label>
                            </div>
                        </div>
                    </p>

                </div>

                <div class="card-action">

                    <div class="row">
                        <div class="input-field col s12">
                            <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
    
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/pumps/{{ $pump->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red" href="/pumps/{{ $pump->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/pumps/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop