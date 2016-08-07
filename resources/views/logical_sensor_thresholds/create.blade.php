@extends('master')

@section('content')
    <div class="col-md-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>@lang('labels.create') @choice('components.logical_sensor_thresholds', 1)</h2>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <br />
                <form class="form-horizontal form-label-left" name="sensor" action="{{ url('api/v1/logical_sensor_thresholds') }}" data-method="POST">

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">@choice('components.logical_sensors', 1)</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <select class="form-control" name="logical_sensor">
                                <option></option>
                                @foreach ($logical_sensors as $ls)
                                    <option value="{{ $ls->id }}">{{ $ls->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <button type="submit" class="btn btn-success" name="sensor_submit">@lang('buttons.next')</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@stop