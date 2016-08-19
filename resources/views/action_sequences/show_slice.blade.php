
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
                @lang('labels.starts_at') {{ $ass->starts_at }}<br />
            @endforeach
        @endif
        <hr />
        @include('actions.dashboard_mini_slice', ['action_sequence' => $ass])
    </div>
</div>