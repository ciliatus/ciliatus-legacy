@extends('master')

@section('content')
    <div class="col-md-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{ $logical_sensor_threshold->name }}</h2>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <br />
                <form class="form-horizontal form-label-left" name="sensors" action="{{ url('api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id) }}" data-method="DELETE">

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">ID</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" placeholder="Name" name="sensors_id" value="{{ $logical_sensor_threshold->id }}" readonly="readonly">
                        </div>
                    </div>


                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <button type="submit" class="btn btn-danger" name="sensors_submit">@lang('buttons.delete')</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@stop