@extends('master')

@section('breadcrumbs')
    <a href="/biography_entries" class="breadcrumb hide-on-small-and-down">@choice('components.biography_entries', 2)</a>
    <a href="/biography_entries/create" class="breadcrumb hide-on-small-and-down">@lang('buttons.create')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/biography_entries') }}" data-method="POST" data-redirect-success="auto">
                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="belongsTo">
                                        <option></option>
                                        @foreach ($belongTo_Options as $t=>$objects)
                                            <optgroup label="@choice('components.' . strtolower($t), 2)">
                                                @foreach ($objects as $o)
                                                    <option value="{{ $t }}|{{ $o->id }}"
                                                            @if(isset($preset['belongsTo_type']) && isset($preset['belongsTo_id']))
                                                            @if($preset['belongsTo_type'] == $t && $preset['belongsTo_id'] == $o->id)
                                                            selected
                                                            @endif
                                                            @endif>@if(is_null($o->display_name)) {{ $o->name }} @else {{ $o->display_name }} @endif</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <label for="belongsTo">@lang('labels.belongsTo')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="category">
                                        <option></option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->name }}"
                                            @if(isset($preset['category']))
                                            @if($preset['category'] == $cat->name)
                                            selected
                                            @endif
                                            @endif>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="category">@lang('labels.bio_categories')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.title')" name="title" value="">
                                    <label for="name">@lang('labels.title')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <textarea placeholder="@lang('labels.text')" rows="15"
                                              name="text" class="materialize-textarea"></textarea>
                                    <label for="text">@lang('labels.text')</label>
                                </div>
                            </div>
                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="material-icons left">save</i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(function() {
            $('.chips-tags').material_chip();
        });

        $('.chips-tags').on('chip.add', function(e, chip){
            $('#tags').append('<input hidden="hidden" type="text" class="chip-tag-' + chip.tag + '" name="tags[]" value="' + chip.tag + '">');
        });

        $('.chips-tags').on('chip.delete', function(e, chip){
            $('input.chip-tag-' + chip.tag).remove();
        });
    </script>
@stop