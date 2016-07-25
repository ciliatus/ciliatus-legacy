@extends('master')

@section('content')
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>{{ $terrarium->friendly_name }} <small>Edit</small></h2>

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
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="Name" name="f_edit_terra_name" value="{{ $terrarium->name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Friendly/Display Name</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="Friendly Name" name="f_edit_terra_friendlyname" value="{{ $terrarium->friendly_name }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Valves <br /><small>Ctrl-click to deselect</small></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="select2_multiple form-control" multiple="multiple" name="f_edit_terra_valves[]">
                            @foreach ($valves as $a)
                                <option value="{{ $a->id }}" @if($a->terrarium_id == $terrarium->id)selected="selected"@endif>{{ $a->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Animals <br /><small>Ctrl-click to deselect</small></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="select2_multiple form-control" multiple="multiple" name="f_edit_terra_animals[]">
                            @foreach ($animals as $a)
                                <option value="{{ $a->id }}" @if($a->terrarium_id == $terrarium->id)selected="selected"@endif>{{ $a->display_name }} <i>{{ $a->lat_name }}</i></option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Settings</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="">
                            <label>
                                <input type="checkbox" class="js-switch" name="f_edit_terra_active" checked /> Active
                            </label>
                        </div>
                        <div class="">
                            <label>
                                <input type="checkbox" class="js-switch" name="f_edit_terra_showdefaultdashboard" checked /> Show on default dashboard
                            </label>
                        </div>
                        <div class="">
                            <label>
                                <input type="checkbox" class="js-switch" name="f_edit_terra_autoirrigation" checked /> Enable automatic irrigation (if available)
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Notifications</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="">
                            <label>
                                <input type="checkbox" class="js-switch" name="f_edit_terra_notifyhumidity" /> Send notifications for humidity
                            </label>
                        </div>
                        <div class="">
                            <label>
                                <input type="checkbox" class="js-switch" name="f_edit_terra_notifytemperature" /> Send notifications for temperature
                            </label>
                        </div>
                    </div>
                </div>


                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success" name="f_edit_terra_submit">Save</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@stop