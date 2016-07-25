@extends('master')

@section('content')
    <div class="col-md-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>{{ $animal->friendly_name }} <small>Delete</small></h2>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <br />
                <form class="form-horizontal form-label-left" name="f_delete_animals" action="{{ url('api/v1/animals/' . $animal->id) }}" data-method="DELETE">

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">ID</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" placeholder="Name" name="f_delete_animals_id" value="{{ $animal->id }}" readonly="readonly">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" placeholder="Name" name="f_delete_animals_name" value="{{ $animal->name }}" readonly="readonly">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Friendly/Display Name</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" placeholder="Name" name="f_delete_animals_friendlyname" value="{{ $animal->friendly_name }}" readonly="readonly">
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <button type="submit" class="btn btn-danger" name="f_delete_animals_submit">Delete</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@stop