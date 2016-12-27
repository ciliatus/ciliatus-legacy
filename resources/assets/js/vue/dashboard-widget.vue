<template>
    <div :class="[containerClasses, 'masonry-grid']" :id="containerId">

        <div :class="wrapperClasses" v-if="dashboard.terraria.critical.length > 0">
            <div class="card">
                <div class="card-content red darken-3 white-text">
                    <i class="material-icons">video_label</i>
                    {{ $tc("components.terraria", 2) }}
                </div>

                <div class="card-content red darken-2 white-text">
                    <span class="card-title activator truncate">
                        <span>{{ dashboard.terraria.critical.length }} {{ $tc("components.terraria", dashboard.terraria.critical.length) }} {{ $t("labels.critical") }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>

                    <div v-for="terrarium in dashboard.terraria.critical">
                        <p>
                            <a v-bind:href="'/terraria/' + terrarium.id" class="white-text">{{ terrarium.display_name }}</a>
                            <span v-show="terrarium.humidity_critical === true && terrarium.temperature_critical !== true">({{ $t("labels.humidity") }}: {{ terrarium.cooked_humidity_percent }}%)</span>
                            <span v-show="terrarium.humidity_critical === true && terrarium.temperature_critical === true">({{ $t("labels.humidity") }}: {{ terrarium.cooked_humidity_percent }}%, {{ $t("labels.temperature") }}: {{ terrarium.cooked_temperature_celsius }}°C)</span>
                            <span v-show="terrarium.humidity_critical !== true && terrarium.temperature_critical === true">({{ $t("labels.temperature") }}: {{ terrarium.cooked_temperature_celsius }}°C)</span>
                        </p>
                    </div>
                </div>

                <div class="card-reveal red darken-2 white-text">
                    <span class="card-title red darken-2 white-text"><i class="material-icons right">close</i></span>

                    <p>
                    </p>
                </div>

                <!--
                <div class="card-action teal lighten-1">
                    <a v-bind:href="''" class="white-text">{{ $t("buttons.details") }}</a>
                </div>
                -->
            </div>
        </div>
        <div :class="wrapperClasses" v-if="dashboard.animal_feeding_schedules.overdue.length > 0">
            <div class="card">
                <div class="card-content orange darken-3 white-text">
                    <i class="material-icons">schedule</i>
                    {{ $tc("components.animal_feedings", 2) }} {{ $t("labels.overdue") }}
                </div>

                <div class="card-content orange darken-2 white-text">
                    <span class="card-title activator truncate">
                        <span>{{ dashboard.animal_feeding_schedules.overdue.length }} {{ $tc("components.animal_feedings", dashboard.animal_feeding_schedules.overdue.length) }} {{ $t("labels.overdue") }}</span>
                        
                    </span>

                    <div v-for="schedule in dashboard.animal_feeding_schedules.overdue">

                        <a v-bind:href="'/animals/' + schedule.animal.id" class="white-text">
                            {{ schedule.animal.display_name }}:
                        </a>

                        <a href="#" v-bind:data-activates="'dropdown-edit-animal_feeding_schedules_' + schedule.id" class="white-text dropdown-button">
                            {{ schedule.type }}
                        </a>

                        <ul v-bind:id="'dropdown-edit-animal_feeding_schedules_' + schedule.id" class="dropdown-content">
                            <li>
                                <a v-bind:href="'/api/v1/animals/' + schedule.animal.id + '/feeding_schedules/' + schedule.id + '/done'" v-on:click="link_post">
                                    <i class="material-icons">done</i> {{ $t('tooltips.done') }}
                                </a>
                            </li>
                            <li>
                                <a v-bind:href="'/api/v1/animals/' + schedule.animal.id + '/feeding_schedules/' + schedule.id + '/skip'" v-on:click="link_post">
                                    <i class="material-icons">update</i> {{ $t('tooltips.skip') }}
                                </a>
                            </li>
                        </ul>

                        ({{ $t("labels.since") }} {{ (schedule.due_days*-1) }} {{ $tc("units.days", (schedule.due_days*-1)) }})

                    </div>
                </div>

                <div class="card-reveal orange darken-2 white-text">
                    <span class="card-title orange darken-2 white-text"><i class="material-icons right">close</i></span>

                    <p>
                    </p>
                </div>

                <!--
                <div class="card-action teal lighten-1">
                    <a v-bind:href="''" class="white-text">{{ $t("buttons.details") }}</a>
                </div>
                -->
            </div>
        </div>
        <div :class="wrapperClasses" v-if="dashboard.animal_weighing_schedules.overdue.length > 0">
            <div class="card">
                <div class="card-content orange darken-3 white-text">
                    <i class="material-icons">schedule</i>
                    {{ $tc("components.animal_weighings", 2) }} {{ $t("labels.overdue") }}
                </div>

                <div class="card-content orange darken-2 white-text">
                    <span class="card-title activator truncate">
                        <span>{{ dashboard.animal_weighing_schedules.overdue.length }} {{ $tc("components.animal_weighings", dashboard.animal_weighing_schedules.overdue.length) }} {{ $t("labels.overdue") }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>

                    <div v-for="schedule in dashboard.animal_weighing_schedules.overdue">
                        <p>
                            <a v-bind:href="'/animals/' + schedule.animal.id" class="white-text">
                                {{ schedule.animal.display_name }}
                            </a>

                            <a href="#" v-bind:data-activates="'dropdown-edit-animal_weighing_schedules_' + schedule.id" class="white-text dropdown-button">
                                ({{ $t("labels.since") }} {{ (schedule.due_days*-1) }} {{ $tc("units.days", (schedule.due_days*-1)) }})
                            </a>

                            <ul v-bind:id="'dropdown-edit-animal_weighing_schedules_' + schedule.id" class="dropdown-content">
                                <!-- @TODO implement modal to enter weight
                                <li>
                                    <a v-bind:href="'/api/v1/animals/' + schedule.animal.id + '/weighing_schedules/' + schedule.id + '/done'" v-on:click="link_post">
                                        <i class="material-icons">done</i> {{ $t('tooltips.done') }}
                                    </a>
                                </li>
                                -->
                                <li>
                                    <a v-bind:href="'/api/v1/animals/' + schedule.animal.id + '/weighing_schedules/' + schedule.id + '/skip'" v-on:click="link_post">
                                        <i class="material-icons">update</i> {{ $t('tooltips.skip') }}
                                    </a>
                                </li>
                            </ul>
                        </p>
                    </div>
                </div>

                <div class="card-reveal orange darken-2 white-text">
                    <span class="card-title orange darken-2 white-text"><i class="material-icons right">close</i></span>

                    <p>
                    </p>
                </div>

                <!--
                <div class="card-action teal lighten-1">
                    <a v-bind:href="''" class="white-text">{{ $t("buttons.details") }}</a>
                </div>
                -->
            </div>
        </div>
        <div :class="wrapperClasses" v-if="dashboard.action_sequence_schedules.overdue.length > 0">
            <div class="card">
                <div class="card-content teal white-text">
                    <i class="material-icons">schedule</i>
                    {{ $tc("components.action_sequences", 2) }} {{ $t("labels.overdue") }}
                </div>

                <div class="card-content teal lighten-1 white-text">
                    <span class="card-title activator truncate">
                        <span>{{ dashboard.action_sequence_schedules.overdue.length }} {{ $tc("components.action_sequences", dashboard.action_sequence_schedules.overdue.length) }} {{ $t("labels.overdue") }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>

                    <div v-for="action_sequence_schedule in dashboard.action_sequence_schedules.overdue">
                        <p>
                            <a v-bind:href="'/action_sequences/' + action_sequence_schedule.sequence.id + '/edit'" class="white-text">
                                {{ action_sequence_schedule.sequence.name }}
                            </a>

                            <i>{{ action_sequence_schedule.timestamps.starts }}</i>
                        </p>
                    </div>
                </div>

                <div class="card-reveal teal lighten-1 white-text">
                    <span class="card-title teal lighten-2 white-text"><i class="material-icons right">close</i></span>

                    <p>
                    </p>
                </div>

                <!--
                <div class="card-action teal lighten-1">
                    <a v-bind:href="''" class="white-text">{{ $t("buttons.details") }}</a>
                </div>
                -->
            </div>
        </div>
        <div :class="wrapperClasses" v-if="dashboard.animal_feeding_schedules.due.length > 0">
            <div class="card">
                <div class="card-content teal white-text">
                    <i class="material-icons">schedule</i>
                    {{ $tc("components.animal_feedings", 2) }} {{ $t("labels.due") }}
                </div>

                <div class="card-content teal lighten-1 white-text">
                    <span class="card-title activator truncate">
                        <span>{{ dashboard.animal_feeding_schedules.due.length }} {{ $tc("components.animal_feedings", dashboard.animal_feeding_schedules.due.length) }} {{ $t("labels.due") }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>

                    <div v-for="schedule in dashboard.animal_feeding_schedules.due">

                        <a v-bind:href="'/animals/' + schedule.animal.id" class="white-text">
                            {{ schedule.animal.display_name }}:
                        </a>

                        <a href="#" v-bind:data-activates="'dropdown-edit-animal_feeding_schedules_' + schedule.id" class="white-text dropdown-button">
                            {{ schedule.type }}
                        </a>

                        <ul v-bind:id="'dropdown-edit-animal_feeding_schedules_' + schedule.id" class="dropdown-content">
                            <li>
                                <a v-bind:href="'/api/v1/animals/' + schedule.animal.id + '/feeding_schedules/' + schedule.id + '/done'" v-on:click="link_post">
                                    <i class="material-icons">done</i> {{ $t('tooltips.done') }}
                                </a>
                            </li>
                            <li>
                                <a v-bind:href="'/api/v1/animals/' + schedule.animal.id + '/feeding_schedules/' + schedule.id + '/skip'" v-on:click="link_post">
                                    <i class="material-icons">update</i> {{ $t('tooltips.skip') }}
                                </a>
                            </li>
                        </ul>

                        ({{ $t("labels.since") }} {{ (schedule.due_days*-1) }} {{ $tc("units.days", (schedule.due_days*-1)) }})

                    </div>
                </div>

                <div class="card-reveal teal lighten-1 white-text">
                    <span class="card-title teal lighten-2 white-text"><i class="material-icons right">close</i></span>

                    <p>
                    </p>
                </div>

                <!--
                <div class="card-action teal lighten-1">
                    <a v-bind:href="''" class="white-text">{{ $t("buttons.details") }}</a>
                </div>
                -->
            </div>
        </div>
        <div :class="wrapperClasses" v-if="dashboard.animal_weighing_schedules.due.length > 0">
            <div class="card">
                <div class="card-content teal white-text">
                    <i class="material-icons">schedule</i>
                    {{ $tc("components.animal_weighings", 2) }} {{ $t("labels.due") }}
                </div>

                <div class="card-content teal lighten-1 white-text">
                    <span class="card-title activator truncate">
                        <span>{{ dashboard.animal_weighing_schedules.due.length }} {{ $tc("components.animal_weighings", dashboard.animal_weighing_schedules.due.length) }} {{ $t("labels.due") }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>

                    <div v-for="schedule in dashboard.animal_weighing_schedules.due">
                        <p>
                            <a v-bind:href="'/animals/' + schedule.animal.id" class="white-text">
                                {{ schedule.animal.display_name }}
                            </a>

                            <a href="#" v-bind:data-activates="'dropdown-edit-animal_weighing_schedules_' + schedule.id" class="white-text dropdown-button">
                                {{ $t('labels.today') }}
                            </a>

                            <ul v-bind:id="'dropdown-edit-animal_weighing_schedules_' + schedule.id" class="dropdown-content">
                                <!-- @TODO implement modal to enter weight
                                <li>
                                    <a v-bind:href="'/api/v1/animals/' + schedule.animal.id + '/weighing_schedules/' + schedule.id + '/done'" v-on:click="link_post">
                                        <i class="material-icons">done</i> {{ $t('tooltips.done') }}
                                    </a>
                                </li>
                                -->
                                <li>
                                    <a v-bind:href="'/api/v1/animals/' + schedule.animal.id + '/weighing_schedules/' + schedule.id + '/skip'" v-on:click="link_post">
                                        <i class="material-icons">update</i> {{ $t('tooltips.skip') }}
                                    </a>
                                </li>
                            </ul>
                        </p>
                    </div>
                </div>

                <div class="card-reveal teal lighten-1 white-text">
                    <span class="card-title teal lighten-2 white-text"><i class="material-icons right">close</i></span>

                    <p>
                    </p>
                </div>

                <!--
                <div class="card-action teal lighten-1">
                    <a v-bind:href="''" class="white-text">{{ $t("buttons.details") }}</a>
                </div>
                -->
            </div>
        </div>
        <div :class="wrapperClasses" v-if="dashboard.action_sequence_schedules.due.length > 0">
            <div class="card">
                <div class="card-content teal white-text">
                    <i class="material-icons">schedule</i>
                    {{ $tc("components.action_sequences", 2) }} {{ $t("labels.due") }}
                </div>

                <div class="card-content teal lighten-1 white-text">
                    <span class="card-title activator truncate">
                        <span>{{ dashboard.action_sequence_schedules.due.length }} {{ $tc("components.action_sequences", dashboard.action_sequence_schedules.due.length) }} {{ $t("labels.due") }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>

                    <div v-for="action_sequence_schedule in dashboard.action_sequence_schedules.due">
                        <p>
                            <a v-bind:href="'/action_sequences/' + action_sequence_schedule.sequence.id + '/edit'" class="white-text">
                                {{ action_sequence_schedule.sequence.name }}
                            </a>

                            <i>{{ action_sequence_schedule.timestamps.starts }}</i>
                        </p>
                    </div>
                </div>

                <div class="card-reveal teal lighten-1 white-text">
                    <span class="card-title teal lighten-2 white-text"><i class="material-icons right">close</i></span>

                    <p>
                    </p>
                </div>

                <!--
                <div class="card-action teal lighten-1">
                    <a v-bind:href="''" class="white-text">{{ $t("buttons.details") }}</a>
                </div>
                -->
            </div>
        </div>

        <div :class="wrapperClasses" v-if="dashboard.action_sequence_schedules.running.length > 0">
            <div class="card">
                <div class="card-content teal white-text">
                    <i class="material-icons">schedule</i>
                    {{ $tc("components.action_sequences", 2) }} {{ $t("labels.running") }}
                </div>

                <div class="card-content teal lighten-1 white-text">
                    <span class="card-title activator truncate">
                        <span>{{ dashboard.action_sequence_schedules.running.length }} {{ $tc("components.action_sequences", dashboard.action_sequence_schedules.running.length) }} {{ $t("labels.running") }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>

                    <div v-for="action_sequence_schedule in dashboard.action_sequence_schedules.running">
                        <p>
                            <a v-bind:href="'/action_sequences/' + action_sequence_schedule.sequence.id" class="white-text">
                                {{ action_sequence_schedule.sequence.name }}
                            </a>

                            <i v-if="action_sequence_schedule.timestamps.last_start !== null">{{ action_sequence_schedule.timestamps.last_start.split(" ")[1] }}</i>
                        </p>
                    </div>
                </div>

                <div class="card-reveal teal lighten-1 white-text">
                    <span class="card-title teal lighten-2 white-text"><i class="material-icons right">close</i></span>

                    <p>
                    </p>
                </div>

                <!--
                <div class="card-action teal lighten-1">
                    <a v-bind:href="''" class="white-text">{{ $t("buttons.details") }}</a>
                </div>
                -->
            </div>
        </div>

        <div :class="wrapperClasses" v-if="dashboard.terraria.critical.length < 1">
            <div class="card">
                <div class="card-content teal white-text">
                    <i class="material-icons">video_label</i>
                    {{ $tc("components.terraria", 2) }}
                </div>

                <div class="card-content teal lighten-1 white-text">
                    <span class="card-title activator truncate">
                        <span>{{ dashboard.terraria.ok.length }} {{ $tc("components.terraria", dashboard.terraria.ok.length) }} {{ $t("labels.ok") }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>
                </div>

                <div class="card-reveal teal lighten-1 white-text">
                    <span class="card-title teal lighten-2 white-text"><i class="material-icons right">close</i></span>

                    <p>
                    </p>
                </div>

                <!--
                <div class="card-action teal lighten-1">
                    <a v-bind:href="''" class="white-text">{{ $t("buttons.details") }}</a>
                </div>
                -->
            </div>
        </div>

    </div>
</template>

<script>
export default {

    data () {
        return {
            dashboard: []
        }
    },

    props: {
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
                if (data.id === e.terrarium.id) {
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
                if (data.id === e.terrarium.id) {
                    item = index;
                }
            });
            if (item !== null) {
                if (e.terrarium.temperature_critical === false && e.terrarium.humidity_critical === false && e.terrarium.heartbeat_critical === false) {
                    this.dashboard.terraria.critical.splice(item, 1);
                }
                else {
                    this.dashboard.terraria.ok.splice(item, 1, e.terrarium);
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

        refresh_grid: function() {
             $('.dropdown-button').dropdown({
                constrain_width: false
             });
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
        },

        submit: function(e) {
            window.submit_form(e);
        },

        link_post: function(e) {
            e.preventDefault();
            $.post(e.target.href);
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
        });

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/dashboard',
            method: 'GET',
            success: function (data) {
                that.dashboard = data.data;

                that.$nextTick(function() {
                    var $container = $('#' + that.containerId);
                    $container.masonry({
                      columnWidth: '.col',
                      itemSelector: '.col',
                    });

                    that.refresh_grid();
                });


                window.eventHubVue.processEnded();
            },
            error: function (error) {
                window.notification('An error occured :(', 'red darken-1 text-white');
                console.log(error);
                window.eventHubVue.processEnded();
            }
        });
    }
}
</script>
