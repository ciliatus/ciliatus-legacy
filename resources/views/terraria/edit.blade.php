@extends('master')

@section('content')
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>{{ $terrarium->friendly_name }}</h2>

            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <br />
            <form class="form-horizontal form-label-left" name="f_edit_terra" action="{{ url('api/v1/terraria/' . $terrarium->id) }}" data-method="PUT">

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">ID</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" readonly="readonly" placeholder="ID" name="f_edit_terra_id" value="{{ $terrarium->id }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.name')</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="Name" name="f_edit_terra_name" value="{{ $terrarium->name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.display_name')</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="Friendly Name" name="f_edit_terra_friendlyname" value="{{ $terrarium->friendly_name }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@choice('components.valves', 2) <br /><small>@lang('tooltips.ctrltoselect')</small></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="select2_multiple form-control" multiple="multiple" name="f_edit_terra_valves[]">
                            @foreach ($valves as $a)
                                <option value="{{ $a->id }}" @if($a->terrarium_id == $terrarium->id)selected="selected"@endif>{{ $a->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@choice('components.animals', 2) <br /><small>@lang('tooltips.ctrltoselect')</small></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="select2_multiple form-control" multiple="multiple" name="f_edit_terra_animals[]">
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
                                <input type="checkbox" class="js-switch" name="f_edit_terra_active" checked /> @lang('tooltips.active')
                            </label>
                        </div>
                        <div class="">
                            <label>
                                <input type="checkbox" class="js-switch" name="f_edit_terra_showdefaultdashboard" checked /> @lang('tooltips.showondefaultdashboard')
                            </label>
                        </div>
                        <div class="">
                            <label>
                                <input type="checkbox" class="js-switch" name="f_edit_terra_autoirrigation" checked /> @lang('tooltips.autoirrigation')
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@choice('labels.notifications', 2)</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="">
                            <label>
                                <input type="checkbox" class="js-switch" name="f_edit_terra_notifyhumidity" /> @lang('tooltips.sendnotificationsfor'): @lang('labels.humidity')
                            </label>
                        </div>
                        <div class="">
                            <label>
                                <input type="checkbox" class="js-switch" name="f_edit_terra_notifytemperature" /> @lang('tooltips.sendnotificationsfor'): @lang('labels.temperature')
                            </label>
                        </div>
                    </div>
                </div>


                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success" name="f_edit_terra_submit">@lang('buttons.save')</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@stop