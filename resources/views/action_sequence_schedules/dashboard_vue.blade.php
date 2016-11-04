<template id="action_sequence_schedule-widget-template">
    <div class="col-xs-12">
        <div class="x_panel">

            <div class="x_title">
                <h2>
                    <i class="material-icons">description</i>
                    @choice('components.action_sequence_schedule', 2)
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="material-icons">expand_less</i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <div class="row" v-for="ass in action_sequence_schedules">
                    <span class="material-icons" v-show="ass.states.is_overdue">warning</span>
                    <span class="material-icons" v-show="ass.states.running">cached</span>
                    <span class="material-icons" v-show="ass.states.will_run_today">hourglass_empty</span>
                    <span class="material-icons" v-show="ass.states.ran_today">check</span>

                    @lang('labels.starts_at') @{{ ass.timestamps.starts }}
                    <span v-show="ass.states.running">(@lang('labels.last_start') @{{ ass.timestamps.last_start }})</span>
                    <span v-show="ass.states.ran_today">(@lang('labels.ran_today') @{{ ass.timestamps.last_finished }})</span> -
                    <a href="/action_sequence_schedules/@{{ ass.sequence.id }}">@{{ ass.sequence.name }}</a> (@{{ ass.sequence.duration_minutes }} @choice('units.minutes', 2))
                </div>

                <div class="clearfix"></div>

            </div>
        </div>
    </div>
</template>