@extends('master')

@section('content')
<div class="col-md-6 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>{{ $valve->name }} <small>Edit</small></h2>

            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <br />
            <form class="form-horizontal form-label-left" name="f_edit_valve" action="{{ url('api/v1/valves/' . $valve->id) }}" data-method="PUT">

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">ID</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" readonly="readonly" placeholder="ID" name="f_edit_valve_id" value="{{ $valve->id }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" placeholder="Name" name="f_edit_valve_name" value="{{ $valve->name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Control Unit</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control" name="f_edit_valve_controlunit">
                            <option></option>
                            @foreach ($controlunits as $c)
                                <option value="{{ $c->id }}" @if($valve->controlunit_id == $c->id)selected="selected"@endif>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Pump</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control" name="f_edit_valve_pump">
                            <option></option>
                            @foreach ($pumps as $p)
                                <option value="{{ $p->id }}" @if($valve->pump_id == $p->id)selected="selected"@endif>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Terrarium</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control" name="f_edit_valve_terrarium">
                            <option></option>
                            @foreach ($terraria as $t)
                                <option value="{{ $t->id }}" @if($valve->terrarium_id == $t->id)selected="selected"@endif>{{ $t->friendly_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success" name="f_edit_valve_submit">Save</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@stop