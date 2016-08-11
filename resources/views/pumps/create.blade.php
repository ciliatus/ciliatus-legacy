@extends('master')

@section('content')
    <div class="col-md-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="material-icons">rotate_right</i> @lang('labels.create') @choice('components.pumps', 1)</h2>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <br />
                <form class="form-horizontal form-label-left" name="f_create_pump" action="{{ url('api/v1/pumps') }}" data-method="POST">

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.name')</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" placeholder="@lang('labels.name')" name="name" value="">
                        </div>
                    </div>


                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <button type="submit" class="btn btn-success" name="submit">@lang('buttons.next')</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@stop