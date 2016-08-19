
<div class="x_panel">
    <div class="x_title">
        <h2><i class="material-icons">done</i> @choice('components.actions', 2)</h2>

        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        @foreach($action_sequence->actions as $a)
                {{ $a->sequence_sort_id }} -
                @lang('tooltips.set_state_to', [
                    'target' => '<a href="' . $a->url() . '"><span class="material-icons">' . $a->target()->icon() . '</span> ' . $a->target()->name . '</a>',
                    'state' => $a->desired_state,
                    'minutes' => $a->duration_minutes
                ])
                @if (!is_null($a->wait_for_started_action_id))
                    @lang('tooltips.start_after_started', [
                        'id' => $a->wait_for_started_action_object()->sequence_sort_id
                    ])
                @endif
            <span class="pull-right">
                @if ($a->sequence_sort_id != 1)
                <a href="{{ url('actions/' . $a->id . '/sort_up') }}">
                    <i class="material-icons">arrow_upward</i>
                </a>
                @endif
                <a href="{{ url('actions/' . $a->id . '/sort_down') }}">
                    <i class="material-icons">arrow_downward</i>
                </a>
                <a href="{{ url('actions/' . $a->id . '/edit') }}">
                    <i class="material-icons">edit</i>
                </a>
                <a href="{{ url('actions/' . $a->id . '/delete') }}">
                    <i class="material-icons">delete</i>
                </a>
            </span>
            <br />
        @endforeach
    </div>
</div>