@extends('master')

@section('content')
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>{{ $file->display_name }}</h2>

            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <br />
            <form class="form-horizontal form-label-left" name="f_edit_file" action="{{ url('api/v1/files/' . $file->id) }}" data-method="PUT">

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">ID</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" readonly="readonly" placeholder="ID" name="id" value="{{ $file->id }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.name')</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="@lang('labels.name')" name="name" value="{{ $file->name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.display_name')</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="@lang('labels.display_name')" name="display_name" value="{{ $file->display_name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@choice('components.belongsto_type', 1)</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control" name="controlunit">
                            <option></option>
                            <option value="Terrarium" @if($file->belongsTo_type == 'Terrarium')selected="selected"@endif>@choice('components.terraria', 1)</option>
                            <option value="Animal" @if($file->belongsTo_type == 'Animal')selected="selected"@endif>@choice('components.animals', 1)</option>
                            <option value="Controlunit" @if($file->belongsTo_type == 'Controlunit')selected="selected"@endif>@choice('components.controlunits', 1)</option>
                            <option value="PhysicalSensor" @if($file->belongsTo_type == 'PhysicalSensor')selected="selected"@endif>@choice('components.physical_sensors', 1)</option>
                            <option value="LogicalSensor" @if($file->belongsTo_type == 'LogicalSensor')selected="selected"@endif>@choice('components.logical_sensors', 1)</option>
                            <option value="Pump" @if($file->belongsTo_type == 'Pump')selected="selected"@endif>@choice('components.pumps', 1)</option>
                            <option value="Valve" @if($file->belongsTo_type == 'Valve')selected="selected"@endif>@choice('components.valves', 1)</option>
                        </select>
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
@stop