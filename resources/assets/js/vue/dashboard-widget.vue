<template>
    <div :class="[containerClasses, 'masonry-grid']" :id="containerId">
        
        <!--
            Active suggestions
        -->
        <div :class="wrapperClasses" v-if="dashboard.suggestions.length > 0">

            <ul class="collection info with-header">
                <li class="collection-header">
                    <i class="material-icons">lightbulb_outline</i>
                    {{ dashboard.suggestions.length }} {{ $tc("components.suggestions", dashboard.suggestions.length) }}
                </li>

                <li class="collection-item" v-for="suggestion in dashboard.suggestions">

                    <div class="white-text">

                        <span style="display: inline-block; width: calc(100% - 60px);">
                            <a class="white-text" v-bind:href="suggestion.belongsTo_object.url">
                                {{ suggestion.belongsTo_object.display_name || suggestion.belongsTo_object.name }}:
                            </a>
                            {{ $t('messages.suggestions.' + suggestion.name, {
                            hour: suggestion.value,
                            name: suggestion
                        }) }}
                        </span>


                        <a class="secondary-content white-text" v-bind:href="'/api/v1/properties/read/Event/' + suggestion.id" v-on:click="link_post">
                            <i class="material-icons">done</i>
                        </a>

                    </div>

                </li>

            </ul>


        </div>


        <!--
            Controlunits critical
        -->
        <div :class="wrapperClasses" v-if="dashboard.controlunits.critical.length > 0">

            <ul class="collection critical with-header">
                <li class="collection-header">
                    <i class="material-icons">developer_board</i>
                    {{ dashboard.controlunits.critical.length }} {{ $tc("components.controlunits", dashboard.controlunits.critical.length) }} {{ $t("labels.critical") }}
                </li>

                <li class="collection-item" v-for="controlunit in dashboard.controlunits.critical">

                    <div>
                        <a v-bind:href="'/controlunits/' + controlunit.id" class="white-text">{{ controlunit.name }}</a>

                        <span>({{ $t("labels.last_heartbeat") }}: {{ $t(
                            'units.' + $getMatchingTimeDiff(controlunit.timestamps.last_heartbeat_diff).unit,
                            {val: $getMatchingTimeDiff(controlunit.timestamps.last_heartbeat_diff).val}
                        )}})</span>
                    </div>

                </li>

            </ul>

        </div>


        <!--
            Terraria critical
        -->
        <div :class="wrapperClasses" v-if="dashboard.terraria.critical.length > 0">

            <ul class="collection critical with-header">
                <li class="collection-header">
                    <i class="material-icons">video_label</i>
                    {{ dashboard.terraria.critical.length }} {{ $tc("components.terraria", dashboard.terraria.critical.length) }} {{ $t("labels.critical") }}
                </li>

                <li class="collection-item" v-for="terrarium in dashboard.terraria.critical">

                    <div>
                        <a v-bind:href="'/terraria/' + terrarium.id" class="white-text">{{ terrarium.display_name }}</a>

                        <span v-show="terrarium.humidity_critical === true && terrarium.temperature_critical !== true">({{ $t("labels.humidity") }}: {{ terrarium.cooked_humidity_percent }}%)</span>
                        <span v-show="terrarium.humidity_critical === true && terrarium.temperature_critical === true">({{ $t("labels.humidity") }}: {{ terrarium.cooked_humidity_percent }}%, {{ $t("labels.temperature") }}: {{ terrarium.cooked_temperature_celsius }}°C)</span>
                        <span v-show="terrarium.humidity_critical !== true && terrarium.temperature_critical === true">({{ $t("labels.temperature") }}: {{ terrarium.cooked_temperature_celsius }}°C)</span>
                    </div>

                </li>

            </ul>

        </div>

        <!--
            Physical Sensors critical
        -->
        <div :class="wrapperClasses" v-if="dashboard.physical_sensors.critical.length > 0">

            <ul class="collection critical with-header">
                <li class="collection-header">
                    <i class="material-icons">memory</i>
                    {{ dashboard.physical_sensors.critical.length }} {{ $tc("components.physical_sensors", dashboard.physical_sensors.critical.length) }} {{ $t("labels.critical") }}
                </li>

                <li class="collection-item" v-for="physical_sensor in dashboard.physical_sensors.critical">

                    <div>
                        <a v-bind:href="'/physical_sensors/' + physical_sensor.id" class="white-text">{{ physical_sensor.name }}</a>

                        <span>({{ $t("labels.last_heartbeat") }}:
                        {{ $t(
                            'units.' + $getMatchingTimeDiff(physical_sensor.timestamps.last_heartbeat_diff).unit,
                            {val: $getMatchingTimeDiff(physical_sensor.timestamps.last_heartbeat_diff).val}
                        )}})</span>
                    </div>

                </li>

            </ul>

        </div>
        
        <!--
            Animal Feeding Schedules overdue
        -->
        <div :class="wrapperClasses" v-if="dashboard.animal_feeding_schedules.overdue.length > 0">

            <ul class="collection warning with-header">
                <li class="collection-header">
                    <i class="material-icons">local_dining</i>
                    {{ dashboard.animal_feeding_schedules.overdue.length }} {{ $tc("components.animal_feedings", 2) }} {{ $t("labels.overdue") }}
                </li>

                <li class="collection-item" v-for="schedule in dashboard.animal_feeding_schedules.overdue">

                    <div class="white-text">

                        <span style="display: inline-block; width: calc(100% - 60px);">
                            {{ schedule.animal.display_name }}: {{ schedule.type }} ({{ $t("labels.since") }} {{ (schedule.due_days*-1) }} {{ $tc("units.days", (schedule.due_days*-1)) }})
                        </span>


                        <a class="secondary-content white-text" v-bind:href="'/api/v1/animals/' + schedule.animal.id + '/feeding_schedules/' + schedule.id + '/skip'" v-on:click="link_post">
                            <i class="material-icons">update</i>
                        </a>
                        <a class="secondary-content white-text" v-bind:href="'/api/v1/animals/' + schedule.animal.id + '/feeding_schedules/' + schedule.id + '/done'" v-on:click="link_post">
                            <i class="material-icons">done</i>
                        </a>

                    </div>

                </li>

            </ul>


        </div>

        <!--
            Animal Weighing Schedules overdue
        -->
        <div :class="wrapperClasses" v-if="dashboard.animal_weighing_schedules.overdue.length > 0">

            <ul class="collection warning with-header">
                <li class="collection-header">
                    <i class="material-icons">vertical_align_bottom</i>
                    {{ dashboard.animal_weighing_schedules.overdue.length }} {{ $tc("components.animal_weighings", 2) }} {{ $t("labels.overdue") }}
                </li>

                <li class="collection-item" v-for="schedule in dashboard.animal_weighing_schedules.overdue">

                    <div class="white-text">

                        <span style="display: inline-block; width: calc(100% - 60px);">
                            {{ schedule.animal.display_name }} ({{ $t("labels.since") }} {{ (schedule.due_days*-1) }} {{ $tc("units.days", (schedule.due_days*-1)) }})
                        </span>


                        <a class="secondary-content white-text" v-bind:href="'/api/v1/animals/' + schedule.animal.id + '/weighing_schedules/' + schedule.id + '/skip'" v-on:click="link_post">
                            <i class="material-icons">update</i>
                        </a>
                        <!-- @TODO: Toggle Modal -->
                        <a class="secondary-content white-text">
                            <i class="material-icons">done</i>
                        </a>

                    </div>

                </li>

            </ul>

        </div>

        <!--
            Action Sequence Schedules overdue
        -->
        <div :class="wrapperClasses" v-if="dashboard.action_sequence_schedules.overdue.length > 0">

            <ul class="collection warning with-header">
                <li class="collection-header">
                    <i class="material-icons">playlist_play</i>
                    {{ dashboard.action_sequence_schedules.overdue.length }} {{ $tc("components.action_sequences", 2) }} {{ $t("labels.overdue") }}
                </li>

                <li class="collection-item" v-for="schedule in dashboard.action_sequence_schedules.overdue">

                    <div class="white-text">

                        <span style="display: inline-block; width: calc(100% - 30px);">
                            {{ schedule.timestamps.starts }}: {{ schedule.sequence.name }}
                        </span>

                        <a class="secondary-content white-text tooltipped" v-bind:href="'/api/v1/action_sequence_schedules/' + schedule.id + '/skip'" v-on:click="link_post"
                           data-delay="50" data-html="true"
                           :data-tooltip="'<div style=\'max-width: 300px\'>' + $t('tooltips.action_sequence_schedules.skip') + '</div>'">
                            <i class="material-icons">update</i>
                        </a>

                    </div>

                </li>

            </ul>

        </div>

        <!--
            Animal Feeding Schedules due
        -->
        <div :class="wrapperClasses" v-if="dashboard.animal_feeding_schedules.due.length > 0">

            <ul class="collection ok with-header">
                <li class="collection-header">
                    <i class="material-icons">local_dining</i>
                    {{ dashboard.animal_feeding_schedules.due.length }} {{ $tc("components.animal_feedings", 2) }} {{ $t("labels.due") }}
                </li>

                <li class="collection-item" v-for="schedule in dashboard.animal_feeding_schedules.due">

                    <div class="white-text">

                        <span style="display: inline-block; width: calc(100% - 60px);">
                            {{ schedule.animal.display_name }}: {{ schedule.type }}
                        </span>

                        <a class="secondary-content white-text" v-bind:href="'/api/v1/animals/' + schedule.animal.id + '/feeding_schedules/' + schedule.id + '/skip'" v-on:click="link_post">
                            <i class="material-icons">update</i>
                        </a>
                        <a class="secondary-content white-text" v-bind:href="'/api/v1/animals/' + schedule.animal.id + '/feeding_schedules/' + schedule.id + '/done'" v-on:click="link_post">
                            <i class="material-icons">done</i>
                        </a>

                    </div>

                </li>

            </ul>

        </div>

        <!--
            Animal Weighing Schedules due
        -->
        <div :class="wrapperClasses" v-if="dashboard.animal_weighing_schedules.due.length > 0">

            <ul class="collection ok with-header">
                <li class="collection-header">
                    <i class="material-icons">vertical_align_bottom</i>
                    {{ dashboard.animal_weighing_schedules.due.length }} {{ $tc("components.animal_weighings", 2) }} {{ $t("labels.due") }}
                </li>

                <li class="collection-item" v-for="schedule in dashboard.animal_weighing_schedules.due">

                    <div class="white-text">

                        <span style="display: inline-block; width: calc(100% - 60px);">
                            {{ schedule.animal.display_name }} {{ $t('labels.today') }}
                        </span>

                        <a class="secondary-content white-text" v-bind:href="'/api/v1/animals/' + schedule.animal.id + '/weighing_schedules/' + schedule.id + '/skip'" v-on:click="link_post">
                            <i class="material-icons">update</i>
                        </a>
                        <!-- @TODO: Toggle Modal -->
                        <a class="secondary-content white-text">
                            <i class="material-icons">done</i>
                        </a>

                    </div>

                </li>

            </ul>

        </div>

        <!--
            Action Sequence Schedules due
        -->
        <div :class="wrapperClasses" v-if="dashboard.action_sequence_schedules.due.length > 0">

            <ul class="collection ok with-header">
                <li class="collection-header">
                    <i class="material-icons">playlist_play</i>
                    {{ dashboard.action_sequence_schedules.due.length }} {{ $tc("components.action_sequences", 2) }} {{ $t("labels.due") }}
                </li>

                <li class="collection-item" v-for="schedule in dashboard.action_sequence_schedules.due">

                    <div class="white-text">

                        <span style="display: inline-block; width: calc(100% - 30px);">
                            {{ schedule.timestamps.starts }}: {{ schedule.sequence.name }}
                        </span>

                        <a class="secondary-content white-text tooltipped" v-bind:href="'/api/v1/action_sequence_schedules/' + schedule.id + '/skip'" v-on:click="link_post"
                           data-delay="50" data-html="true"
                           :data-tooltip="'<div style=\'max-width: 300px\'>' + $t('tooltips.action_sequence_schedules.skip') + '</div>'">
                            <i class="material-icons">update</i>
                        </a>

                    </div>

                </li>

            </ul>

        </div>


        <!--
            Action Sequence schedules/triggers/intentions running
        -->
        <div :class="wrapperClasses"
             v-if="dashboard.action_sequence_schedules.running.length > 0
                || dashboard.action_sequence_triggers.running.length > 0
                || dashboard.action_sequence_intentions.running.length > 0">

            <ul class="collection ok with-header">
                <li class="collection-header">
                    <i class="material-icons">playlist_play</i>
                    {{ (dashboard.action_sequence_schedules.running.length + dashboard.action_sequence_triggers.running.length + dashboard.action_sequence_intentions.running.length) }}
                    {{ $tc("components.action_sequences", 2) }} {{ $t("labels.running") }}
                </li>

                <li class="collection-item" v-for="schedule in dashboard.action_sequence_schedules.running">

                    <div class="white-text">

                        <span style="display: inline-block; width: calc(100% - 30px);">
                            <i class="material-icons">schedule</i>
                            <a v-if="schedule.timestamps.last_start !== null" class="white-text">{{ schedule.timestamps.last_start.split(" ")[1] }}</a>
                            <a v-bind:href="'/action_sequences/' + schedule.sequence.id + '/edit'" class="white-text">
                                {{ schedule.sequence.name }}
                            </a>
                        </span>

                        <a class="secondary-content white-text" v-bind:href="'/api/v1/action_sequence_schedules/' + schedule.id + '/skip'" v-on:click="link_post">
                            <i class="material-icons">update</i>
                        </a>

                    </div>

                </li>

                <li class="collection-item" v-for="trigger in dashboard.action_sequence_triggers.running">

                    <div class="white-text">

                        <span style="display: inline-block; width: calc(100% - 30px);">
                            <i class="material-icons">flare</i>
                            <a v-if="trigger.timestamps.last_start !== null" class="white-text">{{ trigger.timestamps.last_start.split(" ")[1] }}</a>
                            <a v-bind:href="'/action_sequences/' + trigger.sequence.id + '/edit'" class="white-text">
                                {{ trigger.sequence.name }}
                            </a>
                        </span>

                        <a class="secondary-content white-text" v-bind:href="'/api/v1/action_sequence_triggers/' + trigger.id + '/skip'" v-on:click="link_post">
                            <i class="material-icons">update</i>
                        </a>

                    </div>

                </li>

                <li class="collection-item" v-for="intention in dashboard.action_sequence_intentions.running">

                    <div class="white-text">

                        <span style="display: inline-block; width: calc(100% - 30px);">
                            <i class="material-icons">explore</i>
                            <a v-if="intention.timestamps.last_start !== null" class="white-text">{{ intention.timestamps.last_start.split(" ")[1] }}</a>
                            <a v-bind:href="'/action_sequences/' + intention.sequence.id + '/edit'" class="white-text">
                                {{ intention.sequence.name }}
                            </a>
                        </span>

                        <a class="secondary-content white-text" v-bind:href="'/api/v1/action_sequence_intentions/' + intention.id + '/skip'" v-on:click="link_post">
                            <i class="material-icons">update</i>
                        </a>

                    </div>

                </li>

            </ul>

        </div>

        <!--
            Terraria ok
        -->
        <div :class="wrapperClasses" v-if="dashboard.terraria.critical.length < 1">

            <ul class="collection ok with-header">
                <li class="collection-header">
                    <i class="material-icons">video_label</i>
                    {{ dashboard.terraria.ok.length }} {{ $tc("components.terraria", 2) }}
                </li>

                <li class="collection-item">

                    <div class="white-text">

                        {{ dashboard.terraria.ok.length }} {{ $tc("components.terraria", dashboard.terraria.ok.length) }} {{ $t("labels.ok") }}

                    </div>

                </li>

            </ul>

        </div>

    </div>
</template>

<script>
    export default {

        data () {
            return {
                dashboard: {
                    action_sequence_intentions: {
                        running: [],
                        should_be_running: []
                    },
                    action_sequence_schedules: {
                        due: [],
                        overdue: [],
                        running: []
                    },
                    action_sequence_triggers: {
                        running: [],
                        should_be_running: []
                    },
                    animal_feeding_schedules: {
                        due: [],
                        overdue: []
                    },
                    animal_weighing_schedules: {
                        due: [],
                        overdue: []
                    },
                    suggestions: [],
                    terraria: {
                        ok: [],
                        critical: []
                    }
                }
            }
        },

        props: {
            refreshTimeoutSeconds: {
                type: Number,
                default: null,
                required: false
            },
            wrapperClasses: {
                type: String,
                default: '',
                required: false
            },
            containerClasses: {
                type: String,
                default: '',
                required: false
            },
            containerId: {
                type: String,
                default: 'dashboard-masonry-grid',
                required: false
            }
        },

        methods: {

            /*
             * Terrarium events
             */
            updateTerrarium: function(e) {
                var item = null;
                var found = false;

                /*
                 * Check in ok array
                 */
                this.dashboard.terraria.ok.forEach(function(data, index) {
                    if (data.id === e.terrarium_id) {
                        item = index;
                    }
                });
                if (item !== null) {
                    if (e.terrarium.temperature_critical !== false || e.terrarium.humidity_critical !== false || e.terrarium.heartbeat_critical !== false) {
                        this.dashboard.terraria.ok.splice(item, 1);
                    }
                    else {
                        this.dashboard.terraria.ok.splice(item, 1, e.terrarium);
                        found = true;
                    }
                }

                /*
                 * Check in critical array
                 */
                item = null;
                this.dashboard.terraria.critical.forEach(function(data, index) {
                    if (data.id === e.terrarium_id) {
                        item = index;
                    }
                });
                if (item !== null) {
                    if (e.terrarium.temperature_critical === false && e.terrarium.humidity_critical === false && e.terrarium.heartbeat_critical === false) {
                        this.dashboard.terraria.critical.splice(item, 1);
                    }
                    else {
                        this.dashboard.terraria.critical.splice(item, 1, e.terrarium);
                        found = true;
                    }
                }

                /*
                 * If found is not true, the item was either not found
                 * or was removed from an array.
                 * In this case properties will be checked again and
                 * item will be pushed to an array if they match certain criteria
                 */
                if (found !== true) {
                    if (e.terrarium.temperature_critical === false && e.terrarium.humidity_critical === false && e.terrarium.heartbeat_critical === false) {
                        this.dashboard.terraria.ok.push(e.terrarium);
                    }
                    else {
                        this.dashboard.terraria.critical.push(e.terrarium);
                    }
                }

                this.refresh_grid();
            },
            deleteTerrarium: function(e) {
                var that = this;

                /*
                 * Check ok array
                 */
                this.dashboard.terraria.ok.forEach(function(data, index) {
                    if (data.id === e.terrarium_id) {
                        this.dashboard.terraria.ok.splice(index, 1);
                    }
                });

                /*
                 * Check critical array
                 */
                this.dashboard.terraria.critical.forEach(function(data, index) {
                    if (data.id === e.terrarium_id) {
                        this.dashboard.terraria.critical.splice(index, 1);
                    }
                });

                this.refresh_grid();
            },


            /*
             * AnimalFeedingSchedule events
             */
            updateAnimalFeedingSchedule: function(e) {
                var item = null;
                var found = false;

                /*
                 * Check in due array
                 */
                this.dashboard.animal_feeding_schedules.due.forEach(function(data, index) {
                    if (data.id === e.animal_feeding_schedule.id) {
                        item = index;
                    }
                });
                if (item !== null) {
                    if (e.animal_feeding_schedule.due_days === 0) {
                        this.dashboard.animal_feeding_schedules.due.splice(item, 1, e.animal_feeding_schedule);
                        found = true;
                    }
                    else {
                        this.dashboard.animal_feeding_schedules.due.splice(item, 1);
                    }
                }

                /*
                 * Check in overdue array
                 */
                item = null;
                this.dashboard.animal_feeding_schedules.overdue.forEach(function(data, index) {
                    if (data.id === e.animal_feeding_schedule.id) {
                        item = index;
                    }
                });

                if (item !== null) {
                    if (e.animal_feeding_schedule.due_days >= 0) {
                        this.dashboard.animal_feeding_schedules.overdue.splice(item, 1);
                    }
                    else {
                        this.dashboard.animal_feeding_schedules.overdue.splice(item, 1, e.animal_feeding_schedule);
                        found = true;
                    }
                }


                /*
                 * If found is not true, the item was either not found
                 * or was removed from an array.
                 * In this case properties will be checked again and
                 * item will be pushed to an array if they match certain criteria
                 */
                if (found !== true) {
                    if (e.animal_feeding_schedule.due_days == 0) {
                        this.dashboard.animal_feeding_schedules.due.push(e.animal_feeding_schedule);
                    }
                    else if (e.animal_feeding_schedule.due_days < 0) {
                        this.dashboard.animal_feeding_schedules.overdue.push(e.animal_feeding_schedule);
                    }
                }

                this.$nextTick(function() {
                    this.refresh_grid();
                });
            },

            deleteAnimalFeedingSchedule: function(e) {
                var that = this;

                /*
                 * check in due array
                 */
                this.dashboard.animal_feeding_schedules.due.forEach(function(data, index) {
                    if (data.id === e.animal_feeding_schedule_id) {
                        that.dashboard.animal_feeding_schedules.due.splice(index, 1);
                    }
                });

                /*
                 * check in overdue array
                 */
                this.dashboard.animal_feeding_schedules.overdue.forEach(function(data, index) {
                    if (data.id === e.animal_feeding_schedule_id) {
                        this.dashboard.animal_feeding_schedules.overdue.splice(index, 1);
                    }
                });

                this.$nextTick(function() {
                    this.refresh_grid();
                });
            },


            /*
             * AnimalWeighingSchedule events
             */
            updateAnimalWeighingSchedule: function(e) {
                var item = null;
                var found = false;

                /*
                 * Check in due array
                 */
                this.dashboard.animal_weighing_schedules.due.forEach(function(data, index) {
                    if (data.id === e.animal_weighing_schedule.id) {
                        item = index;
                    }
                });
                if (item !== null) {
                    if (e.animal_weighing_schedule.due_days === 0) {
                        this.dashboard.animal_weighing_schedules.due.splice(item, 1, e.animal_weighing_schedule);
                        found = true;
                    }
                    else {
                        this.dashboard.animal_weighing_schedules.due.splice(item, 1);
                    }
                }

                /*
                 * Check in overdue array
                 */
                item = null;
                this.dashboard.animal_weighing_schedules.overdue.forEach(function(data, index) {
                    if (data.id === e.animal_weighing_schedule.id) {
                        item = index;
                    }
                });

                if (item !== null) {
                    if (e.animal_weighing_schedule.due_days >= 0) {
                        this.dashboard.animal_weighing_schedules.overdue.splice(item, 1);
                    }
                    else {
                        this.dashboard.animal_weighing_schedules.overdue.splice(item, 1, e.animal_weighing_schedule);
                        found = true;
                    }
                }

                /*
                 * If found is not true, the item was either not found
                 * or was removed from an array.
                 * In this case properties will be checked again and
                 * item will be pushed to an array if they match certain criteria
                 */
                if (found !== true) {
                    if (e.animal_weighing_schedule.due_days == 0) {
                        this.dashboard.animal_weighing_schedules.due.push(e.animal_weighing_schedule);
                    }
                    else if (e.animal_weighing_schedule.due_days < 0) {
                        this.dashboard.animal_weighing_schedules.overdue.push(e.animal_weighing_schedule);
                    }
                }

                this.$nextTick(function() {
                    this.refresh_grid();
                });
            },

            deleteAnimalWeighingSchedule: function(e) {
                var that = this;

                /*
                 * check in due array
                 */
                this.dashboard.animal_weighing_schedules.due.forEach(function(data, index) {
                    if (data.id === e.animal_weighing_schedule_id) {
                        that.dashboard.animal_weighing_schedules.due.splice(index, 1);
                    }
                });

                /*
                 * check in overdue array
                 */
                this.dashboard.animal_weighing_schedules.overdue.forEach(function(data, index) {
                    if (data.id === e.animal_weighing_schedule_id) {
                        this.dashboard.animal_weighing_schedules.overdue.splice(index, 1);
                    }
                });

                this.$nextTick(function() {
                    this.refresh_grid();
                });
            },


            /*
             * ActionSequenceSchedule events
             */
            updateActionSequenceSchedule: function(e) {
                var item = null;
                var found = false;

                /*
                 * Check in due array
                 */
                this.dashboard.action_sequence_schedules.due.forEach(function(data, index) {
                    if (data.id === e.action_sequence_schedule.id) {
                        item = index;
                    }
                });
                if (item !== null) {
                    if (e.action_sequence_schedule.states.is_overdue === false && e.action_sequence_schedule.states.will_run_today === true) {
                        this.dashboard.action_sequence_schedules.due.splice(item, 1, e.action_sequence_schedule);
                        found = true;
                    }
                    else {
                        this.dashboard.action_sequence_schedules.due.splice(item, 1);
                    }
                }
                item = null;

                /*
                 * Check in overdue array
                 */
                item = null;
                this.dashboard.action_sequence_schedules.overdue.forEach(function(data, index) {
                    if (data.id === e.action_sequence_schedule.id) {
                        item = index;
                    }
                });
                if (item !== null) {
                    if (e.action_sequence_schedule.states.is_overdue === false) {
                        this.dashboard.action_sequence_schedules.overdue.splice(item, 1);
                    }
                    else {
                        this.dashboard.action_sequence_schedules.overdue.splice(item, 1, e.action_sequence_schedule);
                        item = null;
                    }
                    found = true;
                }

                /*
                 * Check in running array
                 */
                item = null;
                this.dashboard.action_sequence_schedules.running.forEach(function(data, index) {
                    if (data.id === e.action_sequence_schedule.id) {
                        item = index;
                    }
                });

                if (item !== null) {
                    if (e.action_sequence_schedule.states.running === false) {
                        this.dashboard.action_sequence_schedules.running.splice(item, 1);
                    }
                    else {
                        this.dashboard.action_sequence_schedules.running.splice(item, 1, e.action_sequence_schedule);
                        found = true;
                    }
                }

                /*
                 * If found is not true, the item was either not found
                 * or was removed from an array.
                 * In this case properties will be checked again and
                 * item will be pushed to an array if they match certain criteria
                 */
                if (found !== true) {
                    if (e.action_sequence_schedule.states.is_overdue === false && e.action_sequence_schedule.states.will_run_today === true) {
                        this.dashboard.action_sequence_schedules.due.push(e.action_sequence_schedule);
                    }
                    else if (e.action_sequence_schedule.states.is_overdue === true) {
                        this.dashboard.action_sequence_schedules.overdue.push(e.action_sequence_schedule);
                    }
                    else if (e.action_sequence_schedule.states.running === true) {
                        this.dashboard.action_sequence_schedules.running.push(e.action_sequence_schedule);
                    }
                }

                this.$nextTick(function() {
                    this.refresh_grid();
                });
            },

            deleteActionSequenceSchedule: function(e) {
                var that = this;

                /*
                 * check in due array
                 */
                this.dashboard.action_sequence_schedules.due.forEach(function(data, index) {
                    if (data.id === e.action_sequence_schedule_id) {
                        that.dashboard.action_sequence_schedules.due.splice(index, 1);
                    }
                });

                /*
                 * check in overdue array
                 */
                this.dashboard.action_sequence_schedules.overdue.forEach(function(data, index) {
                    if (data.id === e.action_sequence_schedule_id) {
                        that.dashboard.action_sequence_schedules.overdue.splice(index, 1);
                    }
                });

                /*
                 * check in running array
                 */
                this.dashboard.action_sequence_schedules.running.forEach(function(data, index) {
                    if (data.id === e.action_sequence_schedule_id) {
                        that.dashboard.action_sequence_schedules.running.splice(index, 1);
                    }
                });

                this.$nextTick(function() {
                    this.refresh_grid();
                });
            },

            /*
             * ActionSequenceTrigger events
             */
            updateActionSequenceTrigger: function(e) {
                var item = null;
                var found = false;

                /*
                 * Check in running array
                 */
                item = null;
                this.dashboard.action_sequence_triggers.running.forEach(function(data, index) {
                    if (data.id === e.action_sequence_trigger.id) {
                        item = index;
                    }
                });

                if (item !== null) {
                    if (e.action_sequence_trigger.states.running === false) {
                        this.dashboard.action_sequence_triggers.running.splice(item, 1);
                    }
                    else {
                        this.dashboard.action_sequence_triggers.running.splice(item, 1, e.action_sequence_trigger);
                        found = true;
                    }
                }

                /*
                 * If found is not true, the item was either not found
                 * or was removed from an array.
                 * In this case properties will be checked again and
                 * item will be pushed to an array if they match certain criteria
                 */
                if (found !== true) {
                    if (e.action_sequence_trigger.states.running === true) {
                        this.dashboard.action_sequence_triggers.running.push(e.action_sequence_trigger);
                    }
                }

                this.$nextTick(function() {
                    this.refresh_grid();
                });
            },

            deleteActionSequenceTrigger: function(e) {
                var that = this;

                /*
                 * check in running array
                 */
                this.dashboard.action_sequence_triggers.running.forEach(function(data, index) {
                    if (data.id === e.action_sequence_trigger_id) {
                        that.dashboard.action_sequence_triggers.running.splice(index, 1);
                    }
                });

                this.$nextTick(function() {
                    this.refresh_grid();
                });
            },

            /*
             * ActionSequenceIntention events
             */
            updateActionSequenceIntention: function(e) {
                var item = null;
                var found = false;

                /*
                 * Check in running array
                 */
                item = null;
                this.dashboard.action_sequence_intentions.running.forEach(function(data, index) {
                    if (data.id === e.action_sequence_intention.id) {
                        item = index;
                    }
                });

                if (item !== null) {
                    if (e.action_sequence_intention.states.running === false) {
                        this.dashboard.action_sequence_intentions.running.splice(item, 1);
                    }
                    else {
                        this.dashboard.action_sequence_intentions.running.splice(item, 1, e.action_sequence_intention);
                        found = true;
                    }
                }

                /*
                 * If found is not true, the item was either not found
                 * or was removed from an array.
                 * In this case properties will be checked again and
                 * item will be pushed to an array if they match certain criteria
                 */
                if (found !== true) {
                    if (e.action_sequence_intention.states.running === true) {
                        this.dashboard.action_sequence_intentions.running.push(e.action_sequence_intention);
                    }
                }

                this.$nextTick(function() {
                    this.refresh_grid();
                });
            },

            deleteActionSequenceIntention: function(e) {
                var that = this;

                /*
                 * check in running array
                 */
                this.dashboard.action_sequence_intentions.running.forEach(function(data, index) {
                    if (data.id === e.action_sequence_intention_id) {
                        that.dashboard.action_sequence_intentions.running.splice(index, 1);
                    }
                });

                this.$nextTick(function() {
                    this.refresh_grid();
                });
            },

            deleteSuggestion: function(e) {
                var that = this;

                this.dashboard.suggestions.forEach(function(data, index) {
                    if (data.id === e.target_id) {
                        that.dashboard.suggestions.splice(index, 1);
                    }
                });

                this.$nextTick(function() {
                    this.refresh_grid();
                });
            },

            refresh_grid: function() {
                $('.dropdown-button').dropdown({
                    constrain_width: false
                });
                $('#' + this.containerId).masonry('reloadItems');
                $('#' + this.containerId).masonry('layout');
                $('.tooltipped').tooltip({delay: 50});
            },

            submit: function(e) {
                window.submit_form(e);
            },

            link_post: function(e) {
                e.preventDefault();
                var old = e;

                var parentElement = e.target.href ? e.target : e.target.parentElement;
                var oldContent = parentElement.innerHTML;
                $(parentElement).html('<div class="preloader-wrapper tiny active">' +
                    '<div class="spinner-layer spinner-green-only">' +
                    '<div class="circle-clipper left">' +
                    '<div class="circle"></div>' +
                    '</div><div class="gap-patch">' +
                    '<div class="circle"></div>' +
                    '</div><div class="circle-clipper right">' +
                    '<div class="circle"></div>' +
                    '</div>' +
                    '</div>' +
                    '</div>');

                $.post(parentElement.href, null,
                    function (e) {
                        $(parentElement).html(oldContent);
                    }
                );
            },

            load_data: function(initial) {
                window.eventHubVue.processStarted();
                var that = this;
                $.ajax({
                    url: '/api/v1/dashboard',
                    method: 'GET',
                    success: function (data) {
                        that.dashboard = data.data;

                        that.$nextTick(function() {
                            if (initial) {
                                var container = $('#' + that.containerId);
                                container.masonry({
                                    columnWidth: '.col',
                                    itemSelector: '.col',
                                });
                            }

                            that.refresh_grid();
                        });


                        window.eventHubVue.processEnded();
                    },
                    error: function (error) {
                        window.notification('An error occured :(', 'red darken-1 text-white');
                        console.log(JSON.stringify(error));
                        window.eventHubVue.processEnded();
                    }
                });
            }
        },

        created: function() {
            window.echo.private('dashboard-updates')
                .listen('TerrariumUpdated', (e) => {
                    this.updateTerrarium(e);
                }).listen('TerrariumDeleted', (e) => {
                this.deleteTerrarium(e);
            }).listen('AnimalFeedingScheduleUpdated', (e) => {
                this.updateAnimalFeedingSchedule(e);
            }).listen('AnimalFeedingScheduleDeleted', (e) => {
                this.deleteAnimalFeedingSchedule(e);
            }).listen('AnimalWeighingScheduleUpdated', (e) => {
                this.updateAnimalWeighingSchedule(e);
            }).listen('AnimalWeighingScheduleDeleted', (e) => {
                this.deleteAnimalWeighingSchedule(e);
            }).listen('ActionSequenceScheduleUpdated', (e) => {
                this.updateActionSequenceSchedule(e);
            }).listen('ActionSequenceScheduleDeleted', (e) => {
                this.deleteActionSequenceSchedule(e);
            }).listen('ActionSequenceTriggerUpdated', (e) => {
                this.updateActionSequenceTrigger(e);
            }).listen('ActionSequenceTriggerDeleted', (e) => {
                this.deleteActionSequenceTrigger(e);
            }).listen('ActionSequenceIntentionUpdated', (e) => {
                this.updateActionSequenceIntention(e);
            }).listen('ActionSequenceIntentionDeleted', (e) => {
                this.deleteActionSequenceIntention(e);
            }).listen('ReadFlagSet', (e) => {
                this.deleteSuggestion(e);
            });

            this.load_data(true);

            var that = this;
            if (this.refreshTimeoutSeconds !== null) {
                setInterval(function() {
                    that.load_data();
                }, this.refreshTimeoutSeconds * 1000)
            }
        }
    }
</script>
