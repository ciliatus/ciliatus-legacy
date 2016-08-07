@extends('master')

@section('content')
<div class="col-md-8 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>{{ $logical_sensor_threshold->name }}</h2>

            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <br />
            <form class="form-horizontal form-label-left" name="sensor" action="{{ url('api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id) }}" data-method="PUT">

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">ID</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" readonly="readonly" placeholder="ID" name="sensor_id" value="{{ $logical_sensor_threshold->id }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.rawlimitlo')</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="@lang('labels.rawlimitlo')" name="sensor_lowerlimit" value="{{ $logical_sensor_threshold->rawvalue_lowerlimit }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.rawlimithi')</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="@lang('labels.rawlimithi')" name="sensor_upperlimit" value="{{ $logical_sensor_threshold->rawvalue_upperlimit }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@choice('components.logical_sensors', 1)</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control" name="logical_sensor">
                            <option></option>
                            @foreach ($logical_sensors as $ls)
                                <option value="{{ $ls->id }}" @if($logical_sensor_threshold->logical_sensor_id == $ls->id)selected="selected"@endif>{{ $ls->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.starts_at')</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control datepicker" placeholder="@lang('labels.starts_at')" name="starts_at" value="{{ $logical_sensor_threshold->starts_at }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@choice('labels.settings', 2)</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="">
                            <label>
                                <input type="checkbox" class="js-switch" name="active" @if($logical_sensor_threshold->active) checked @endif /> @lang('tooltips.active')
                            </label>
                        </div>
                    </div>
                </div>


                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success" name="sensor_submit">@lang('buttons.save')</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@stop