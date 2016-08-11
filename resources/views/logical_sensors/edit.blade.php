@extends('master')

@section('content')
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="material-icons">memory</i> {{ $logical_sensor->name }}</h2>

            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <br />
            <form class="form-horizontal form-label-left" name="sensor" action="{{ url('api/v1/logical_sensors/' . $logical_sensor->id) }}" data-method="PUT">

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">ID</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" readonly="readonly" placeholder="ID" name="sensor_id" value="{{ $logical_sensor->id }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.name')</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="@lang('labels.name')" name="sensor_name" value="{{ $logical_sensor->name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.type')</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control" name="sensor_type">
                            <option></option>
                            <option value="humidity_percent" @if($logical_sensor->type == 'humidity_percent')selected="selected"@endif>@lang('labels.humidity') %</option>
                            <option value="temperature_celsius" @if($logical_sensor->type == 'temperature_celsius')selected="selected"@endif>@lang('labels.temperature') °C</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.rawlimitlo')</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="@lang('labels.rawlimitlo')" name="sensor_lowerlimit" value="{{ $logical_sensor->rawvalue_lowerlimit }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.rawlimithi')</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="@lang('labels.rawlimithi')" name="sensor_upperlimit" value="{{ $logical_sensor->rawvalue_upperlimit }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@choice('components.physical_sensors', 1)</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control" name="sensor_physical_sensor">
                            <option></option>
                            @foreach ($physical_sensors as $ps)
                                <option value="{{ $ps->id }}" @if($logical_sensor->physical_sensor_id == $ps->id)selected="selected"@endif>{{ $ps->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.thresholds')</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        @foreach($logical_sensor->thresholds()->orderBy('starts_at')->get() as $t)
                            <div class="col-xs-12">
                                @if($t->active == true)
                                    @if (!is_null($logical_sensor->current_threshold()))
                                        @if($t->id == $logical_sensor->current_threshold()->id)
                                            <i class="fa fa-circle text-success"></i>
                                        @else
                                            <i class="fa fa-circle text-warning"></i>
                                        @endif
                                    @else
                                        <i class="fa fa-circle text-warning"></i>
                                    @endif
                                @else
                                    <i class="fa fa-circle text-danger"></i>
                                @endif

                                @lang('labels.starts_at') {{ $t->starts_at }}:

                                <strong>
                                @if(is_null($t->rawvalue_lowerlimit) && !is_null($t->rawvalue_upperlimit))
                                    max {{ $t->rawvalue_upperlimit }}
                                @elseif(!is_null($t->rawvalue_lowerlimit) && is_null($t->rawvalue_upperlimit))
                                    min {{ $t->rawvalue_lowerlimit }}
                                @elseif(is_null($t->rawvalue_lowerlimit) && is_null($t->rawvalue_upperlimit))
                                @else
                                    {{ $t->rawvalue_lowerlimit }} - {{ $t->rawvalue_upperlimit }}
                                @endif
                                </strong>

                                <span class="pull-right">
                                    <a href="{{ url('logical_sensor_thresholds/' . $t->id . '/edit') }}"><i class="fa fa-edit"></i></a>
                                    <a href="{{ url('logical_sensor_thresholds/' . $t->id . '/delete') }}"><i class="fa fa-times"></i></a>
                                </span>
                            </div>
                        @endforeach
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

@include('logical_sensor_thresholds.create_slice', [
    'logical_sensor'    =>  $logical_sensor
])

@include('logical_sensor_thresholds.copy_slice', [
    'logical_sensor'    =>  $logical_sensor,
    'logical_sensors'   =>  $logical_sensors
])
@stop