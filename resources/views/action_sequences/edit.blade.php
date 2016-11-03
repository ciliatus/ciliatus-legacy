@extends('master')

@section('content')
<div class="col-md-4 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="material-icons">done_all</i> {{ $action_sequence->name }}</h2>

            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <br />
            <form class="form-horizontal form-label-left" name="f_edit_terra" action="{{ url('api/v1/action_sequences/' . $action_sequence->id) }}" data-method="PUT">

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">ID</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" readonly="readonly" placeholder="ID" name="id" value="{{ $action_sequence->id }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('labels.name')</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="Name" name="name" value="{{ $action_sequence->name }}">
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

    @include('action_sequences.show_mini_slice', ['action_sequence' => $action_sequence])
</div>



<div class="col-md-8 col-xs-12">
    @include('actions.dashboard_mini_slice', ['action_sequence' => $action_sequence])
</div>
@stop