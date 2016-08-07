@extends('master')

@section('content')
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>{{ $terrarium->display_name }}</h2>

            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <br />
            <form class="form-horizontal form-label-left" name="f_edit_terra" action="{{ url('api/v1/terraria/' . $terrarium->id) }}" data-method="PUT">

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">ID</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" readonly="readonly" placeholder="ID" name="id" value="{{ $terrarium->id }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.name')</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="Name" name="name" value="{{ $terrarium->name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.display_name')</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="Friendly Name" name="display_name" value="{{ $terrarium->display_name }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@choice('components.valves', 2) <br /><small>@lang('tooltips.ctrltoselect')</small></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="select2_multiple form-control" multiple="multiple" name="valves[]">
                            @foreach ($valves as $a)
                                <option value="{{ $a->id }}" @if($a->terrarium_id == $terrarium->id)selected="selected"@endif>{{ $a->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@choice('components.animals', 2) <br /><small>@lang('tooltips.ctrltoselect')</small></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="select2_multiple form-control" multiple="multiple" name="animals[]">
                            @foreach ($animals as $a)
                                <option value="{{ $a->id }}" @if($a->terrarium_id == $terrarium->id)selected="selected"@endif>{{ $a->display_name }} <i>{{ $a->lat_name }}</i></option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@choice('labels.settings', 2)</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="">
                            <label>
                                <input type="checkbox" class="js-switch" name="active" checked /> @lang('tooltips.active')
                            </label>
                        </div>
                        <div class="">
                            <label>
                                <input type="checkbox" class="js-switch" name="showdefaultdashboard" checked /> @lang('tooltips.showondefaultdashboard')
                            </label>
                        </div>
                        <div class="">
                            <label>
                                <input type="checkbox" class="js-switch" name="autoirrigation" checked /> @lang('tooltips.autoirrigation')
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@choice('labels.notifications', 2)</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="">
                            <label>
                                <input type="checkbox" class="js-switch" name="notifyhumidity" /> @lang('tooltips.sendnotificationsfor'): @lang('labels.humidity')
                            </label>
                        </div>
                        <div class="">
                            <label>
                                <input type="checkbox" class="js-switch" name="notifytemperature" /> @lang('tooltips.sendnotificationsfor'): @lang('labels.temperature')
                            </label>
                        </div>
                    </div>
                </div>


                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success" name="submit">@lang('buttons.save')</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

@include('files.create_slice', [
    'belongsTo_type'    =>  'Terrarium',
    'belongsTo_id'      =>  $terrarium->id
])
@stop