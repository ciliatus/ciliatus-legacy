<div class="x_panel">
    <div class="x_title">
        <h2><i class="material-icons">done_all</i> @lang('labels.create') @choice('components.action_sequences', 1)</h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <br />
        <form class="form-horizontal form-label-left" name="f_create_terra" action="{{ url('api/v1/action_sequences') }}" data-method="POST">
            <input type="hidden" name="terrarium_id" value="{{ $terrarium_id }}">

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.template')</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <select class="form-control" name="template">
                        <option></option>
                        <option value="irrigate">@lang('labels.irrigate')</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.duration') (@choice('units.minutes', 2))</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" class="form-control" placeholder="@choice('units.minutes', 2)" name="duration_minutes" value="10">
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