
<div class="x_panel">
    <div class="x_title">
        <h2><i class="material-icons">done_all</i> @choice('components.action_sequences', 1) {{ $action_sequence->name }}</h2>

        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="material-icons">expand_less</i></a>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">settings</i></a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="{{ url('action_sequences/' . $action_sequence->id . '/edit') }}"><i class="material-icons">mode_edit</i> @lang('menu.edit')</a>
                    </li>
                    <li>
                        <a href="{{ url('action_sequences/' . $action_sequence->id . '/delete') }}"><i class="material-icons">delete</i> @lang('menu.delete')</a>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        @if(is_null($action_sequence->schedules))
            @lang('tooltips.no_schedules')<br />
        @else
            @foreach ($action_sequence->schedules as $ass)
                <div class="col-xs-12">
                    @lang('labels.starts_at') {{ $ass->starts_at }}
                    <span class="pull-right">
                        <form class="form-horizontal form-label-left" name="f_delete_ass_{{ $ass->id }}" id="f_delete_ass_{{ $ass->id }}" action="{{ url('api/v1/action_sequence_schedules/' . $ass->id) }}" data-method="DELETE">
                            <a href="#" onclick="$('#f_delete_ass_{{ $ass->id }}').submit()">
                                <i class="material-icons">delete</i>
                            </a>
                        </form>
                    </span>
                </div>
            @endforeach
            <hr />
        @endif
        <hr />
        <form class="form-horizontal form-label-left" name="f_add_action_sequence_schedule" action="{{ url('api/v1/action_sequence_schedules') }}" data-method="POST">
            <div class="form-group">
                <label class="col-sm-3 control-label">@lang('labels.starts_at')</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="hidden" name="action_sequence_id" value="{{ $action_sequence->id }}">
                        <input type="text" name="starts_at" value="20:00" class="form-control">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-success">@lang('buttons.save')</button>
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>