<template>
    <div>

        <!--
            Modals
         -->
        <div v-for="schedule in animal_weighing_schedules.filter(s => s.data.due_days <= 0)">
            <animal-add-weight-modal :animalId="schedule.data.animal.id"
                                     :containerId="'modal_add_weight_' + schedule.data.id"> </animal-add-weight-modal>
        </div>

        <div :class="[containerClasses, 'masonry-grid']" :id="containerId">
            <!--
                Active suggestions
            -->
            <div :class="wrapperClasses" v-if="suggestions.length > 0">

                <ul class="collection info with-header">
                    <li class="collection-header">
                        <i class="material-icons">lightbulb_outline</i>
                        {{ suggestions.length }} {{ $tc("labels.suggestions", suggestions.length) }}
                    </li>

                    <li class="collection-item" v-for="suggestion in suggestions">
                        <div v-if="suggestion.data" class="white-text">
                            <span style="display: inline-block; width: calc(100% - 60px);">
                                <a class="white-text" :href="suggestion.data.belongsTo_object.url">
                                    {{ suggestion.data.belongsTo_object.display_name || suggestion.data.belongsTo_object.name }}:
                                </a>
                                {{ $t('messages.suggestions.' + suggestion.data.name + '.' + suggestion.data.violation_type, {
                                    hour: suggestion.data.value,
                                    name: suggestion
                                }) }}
                            </span>

                            <a class="secondary-content white-text" :href="'/api/v1/properties/read/Event/' + suggestion.data.id" v-on:click="link_post">
                                <i class="material-icons">done</i>
                            </a>
                        </div>
                        <div v-else>
                            {{ $t('labels.loading') }}
                        </div>
                    </li>

                </ul>


            </div>

            <!--
                Controlunits critical
            -->
            <div :class="wrapperClasses" v-if="controlunits.filter(c => !c.data.state_ok).length > 0">

                <ul class="collection critical with-header">
                    <li class="collection-header">
                        <i class="material-icons">developer_board</i>
                        {{ controlunits.filter(c => !c.data.state_ok).length }}
                        {{ $tc("labels.controlunits", controlunits.filter(c => !c.data.state_ok).length) }}
                        {{ $t("labels.critical") }}
                    </li>

                    <li class="collection-item" v-for="controlunit in controlunits.filter(c => !c.data.state_ok)">
                        <div v-if="controlunit.data">
                            <a :href="'/controlunits/' + controlunit.data.id" class="white-text">{{ controlunit.data.name }}</a>
                            <span>({{ $t("labels.last_heartbeat") }}: {{ $t(
                                'units.' + $getMatchingTimeDiff(controlunit.data.timestamps.last_heartbeat_diff).unit,
                                {val: $getMatchingTimeDiff(controlunit.data.timestamps.last_heartbeat_diff).val}
                            )}})</span>
                        </div>
                        <div v-else>
                            {{ $t('labels.loading') }}
                        </div>
                    </li>
                </ul>

            </div>

            <!--
                Terraria critical
            -->
            <div :class="wrapperClasses" v-if="terraria.filter(t => !t.data.state_ok).length > 0">

                <ul class="collection critical with-header">
                    <li class="collection-header">
                        <i class="material-icons">video_label</i>
                        {{ terraria.filter(t => !t.data.state_ok).length }}
                        {{ $tc("labels.terraria", terraria.filter(t => !t.data.state_ok).length) }}
                        {{ $t("labels.critical") }}
                    </li>

                    <li class="collection-item" v-for="terrarium in terraria.filter(t => !t.data.state_ok)">
                        <div v-if="terrarium.data">
                            <a :href="'/terraria/' + terrarium.data.id" class="white-text">{{ terrarium.data.display_name }}</a>

                            <span v-show="terrarium.data.humidity_critical === true &&
                                          terrarium.data.temperature_critical !== true">
                                ({{ $t("labels.humidity") }}: {{ terrarium.data.cooked_humidity_percent }}%)
                            </span>
                            <span v-show="terrarium.data.humidity_critical === true &&
                                          terrarium.data.temperature_critical === true">
                                ({{ $t("labels.humidity") }}: {{ terrarium.data.cooked_humidity_percent }}%,
                                {{ $t("labels.temperature") }}: {{ terrarium.data.cooked_temperature_celsius }}°C)
                            </span>
                            <span v-show="terrarium.data.humidity_critical !== true &&
                                          terrarium.data.temperature_critical === true">
                                ({{ $t("labels.temperature") }}: {{ terrarium.data.cooked_temperature_celsius }}°C)
                            </span>
                        </div>
                    </li>

                </ul>

            </div>

            <!--
                Physical Sensors critical
            -->
            <div :class="wrapperClasses" v-if="physical_sensors.filter(p => !p.data.state_ok).length > 0">
                <ul class="collection critical with-header">
                    <li class="collection-header">
                        <i class="material-icons">memory</i>
                        {{ physical_sensors.filter(p => !p.data.state_ok).length }}
                        {{ $tc("labels.physical_sensors", physical_sensors.filter(p => !p.data.state_ok).length) }}
                        {{ $t("labels.critical") }}
                    </li>

                    <li class="collection-item" v-for="physical_sensor in physical_sensors.filter(p => !p.data.state_ok)">
                        <div v-if="physical_sensor.data">
                            <a v-bind:href="'/physical_sensors/' + physical_sensor.data.id" class="white-text">{{ physical_sensor.data.name }}</a>

                            <span>({{ $t("labels.last_heartbeat") }}:
                                {{ $t(
                                    'units.' + $getMatchingTimeDiff(physical_sensor.data.timestamps.last_heartbeat_diff).unit,
                                    {val: $getMatchingTimeDiff(physical_sensor.data.timestamps.last_heartbeat_diff).val}
                                )}})
                            </span>
                        </div>
                    </li>
                </ul>

            </div>

            <!--
                Animal Feeding Schedules overdue
            -->
            <div :class="wrapperClasses" v-if="animal_feeding_schedules.filter(s => s.data.due_days < 0).length > 0">
                <ul class="collection warning with-header">
                    <li class="collection-header">
                        <i class="material-icons">local_dining</i>
                        {{ animal_feeding_schedules.filter(s => s.data.due_days < 0).length }}
                        {{ $tc("labels.animal_feedings", animal_feeding_schedules.filter(s => s.data.due_days < 0).length) }}
                        {{ $t("labels.overdue") }}
                    </li>

                    <li class="collection-item" v-for="schedule in animal_feeding_schedules.filter(s => s.data.due_days < 0)">
                        <div v-if="schedule.data" class="white-text">
                            <span style="display: inline-block; width: calc(100% - 60px);">
                                {{ schedule.data.animal.display_name }}: {{ schedule.data.type }} ({{ $t("labels.since") }} {{ (schedule.data.due_days*-1) }} {{ $tc("units.days", (schedule.data.due_days*-1)) }})
                            </span>

                            <a class="secondary-content white-text" v-bind:href="'/api/v1/animals/' + schedule.data.animal.id + '/feeding_schedules/' + schedule.data.id + '/skip'" v-on:click="link_post">
                                <i class="material-icons">update</i>
                            </a>

                            <a class="secondary-content white-text" v-bind:href="'/api/v1/animals/' + schedule.data.animal.id + '/feeding_schedules/' + schedule.data.id + '/done'" v-on:click="link_post">
                                <i class="material-icons">done</i>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>

            <!--
                Animal Weighing Schedules overdue
            -->
            <div :class="wrapperClasses" v-if="animal_weighing_schedules.filter(s => s.data.due_days < 0).length > 0">
                <ul class="collection warning with-header">
                    <li class="collection-header">
                        <i class="material-icons">vertical_align_bottom</i>
                        {{ animal_weighing_schedules.filter(s => s.data.due_days < 0).length }}
                        {{ $tc("labels.animal_weighings", animal_weighing_schedules.filter(s => s.data.due_days < 0).length) }}
                        {{ $t("labels.overdue") }}
                    </li>

                    <li class="collection-item" v-for="schedule in animal_weighing_schedules.filter(s => s.data.due_days < 0)">
                        <div v-if="schedule.data" class="white-text">
                            <span style="display: inline-block; width: calc(100% - 60px);">
                                {{ schedule.data.animal.display_name }} ({{ $t("labels.since") }}
                                {{ (schedule.data.due_days*-1) }} {{ $tc("units.days", (schedule.data.due_days*-1)) }})
                            </span>

                            <a class="secondary-content white-text"
                               v-bind:href="'/api/v1/animals/' + schedule.data.animal.id + '/weighing_schedules/' + schedule.data.id + '/skip'"
                               v-on:click="link_post">
                                <i class="material-icons">update</i>
                            </a>

                            <a class="secondary-content white-text"
                               v-bind:href="'#modal_add_weight_' + schedule.data.id"
                               v-bind:onclick="'$(\'#modal_add_weight_' + schedule.data.id + '\').modal(); $(\'#modal_add_weight_' + schedule.data.id + '\').modal(\'open\');'">
                                <i class="material-icons">done</i>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>

            <!--
                Animal Feeding Schedules due
            -->
            <div :class="wrapperClasses" v-if="animal_feeding_schedules.filter(s => s.data.due_days === 0).length > 0">
                <ul class="collection ok with-header">
                    <li class="collection-header">
                        <i class="material-icons">local_dining</i>
                        {{ animal_feeding_schedules.filter(s => s.data.due_days < 0).length }} 
                        {{ $tc("labels.animal_feedings", animal_feeding_schedules.filter(s => s.data.due_days < 0).length) }} 
                        {{ $t("labels.due") }}
                    </li>

                    <li class="collection-item" v-for="schedule in animal_feeding_schedules.filter(s => s.data.due_days < 0)">
                        <div v-if="schedule.data" class="white-text">
                            <span style="display: inline-block; width: calc(100% - 60px);">
                                {{ schedule.data.animal.display_name }}: {{ schedule.data.type }}
                            </span>

                            <a class="secondary-content white-text"
                               v-bind:href="'/api/v1/animals/' + schedule.data.animal.id + '/feeding_schedules/' + schedule.data.id + '/skip'"
                               v-on:click="link_post">
                                <i class="material-icons">update</i>
                            </a>
                            <a class="secondary-content white-text"
                               v-bind:href="'/api/v1/animals/' + schedule.data.animal.id + '/feeding_schedules/' + schedule.data.id + '/done'"
                               v-on:click="link_post">
                                <i class="material-icons">done</i>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>

            <!--
                Animal Weighing Schedules due
            -->
            <div :class="wrapperClasses" v-if="animal_weighing_schedules.filter(s => s.data.due_days === 0).length > 0">
                <ul class="collection ok with-header">
                    <li class="collection-header">
                        <i class="material-icons">vertical_align_bottom</i>
                        {{ animal_weighing_schedules.filter(s => s.data.due_days === 0).length }} 
                        {{ $tc("labels.animal_weighings", animal_weighing_schedules.filter(s => s.data.due_days === 0).length) }} 
                        {{ $t("labels.due") }}
                    </li>

                    <li class="collection-item" v-for="schedule in animal_weighing_schedules.filter(s => s.data.due_days === 0)">
                        <div v-if="schedule.data" class="white-text">
                            <span style="display: inline-block; width: calc(100% - 60px);">
                                {{ schedule.data.animal.display_name }} {{ $t('labels.today') }}
                            </span>

                            <a class="secondary-content white-text"
                               v-bind:href="'/api/v1/animals/' + schedule.data.animal.id + '/weighing_schedules/' + schedule.data.id + '/skip'"
                               v-on:click="link_post">
                                <i class="material-icons">update</i>
                            </a>
                            <a class="secondary-content white-text"
                               v-bind:href="'#modal_add_weight_' + schedule.data.id"
                               v-bind:onclick="'$(\'#modal_add_weight_' + schedule.data.id + '\').modal(); $(\'#modal_add_weight_' + schedule.data.id + '\').modal(\'open\');'">
                                <i class="material-icons">done</i>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>

            <!--
                Action Sequence Schedules due
            -->
            <div :class="wrapperClasses" v-if="action_sequence_schedules.filter(s => s.data.states.is_due === true).length > 0">
                <ul class="collection ok with-header">
                    <li class="collection-header">
                        <i class="material-icons">playlist_play</i>
                        {{ action_sequence_schedules.filter(s => s.data.states.is_due === true).length }}
                        {{ $tc("labels.action_sequences", action_sequence_schedules.filter(s => s.data.states.is_due === true).length) }}
                        {{ $t("labels.due") }}
                    </li>

                    <li class="collection-item" v-for="schedule in action_sequence_schedules.filter(s => s.data.states.is_due === true)">
                        <div v-if="schedule.data" class="white-text">
                            <span style="display: inline-block; width: calc(100% - 30px);">
                                {{ schedule.data.timestamps.starts }}: {{ schedule.data.sequence.name }}
                            </span>

                            <a class="secondary-content white-text tooltipped"
                               v-bind:href="'/api/v1/action_sequence_schedules/' + schedule.data.id + '/skip'"
                               v-on:click="link_post"
                               data-delay="50" data-html="true"
                               :data-tooltip="'<div style=\'max-width: 300px\'>' + $t('tooltips.action_sequence_schedules.skip') + '</div>'">
                                <i class="material-icons">update</i>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>

            <!--
                Action Sequence triggers/intentions should be started
            -->
            <div :class="wrapperClasses"
                 v-if="action_sequence_triggers.filter(t => t.should_be_started).length > 0
                    || action_sequence_intentions.filter(t => t.should_be_started).length > 0">

                <ul class="collection ok with-header">
                    <li class="collection-header">
                        <i class="material-icons">playlist_play</i>
                        {{ (action_sequence_triggers.filter(t => t.should_be_started).length + 
                            action_sequence_intentions.filter(t => t.should_be_started).length) }}
                        {{ $tc("labels.action_sequences", action_sequence_intentions.filter(t => t.should_be_started).length) }} 
                        {{ $t("labels.should_be_running") }}
                    </li>
                    
                    <li class="collection-item" v-for="trigger in action_sequence_triggers.filter(t => t.should_be_started)">
                        <div v-if="trigger.data" class="white-text">
                            <span style="display: inline-block; width: calc(100% - 30px);">
                                <i class="material-icons">flare</i>
                                <a v-if="trigger.data.timestamps.last_start !== null" class="white-text">
                                    {{ trigger.data.timestamps.last_start.split(" ")[1] }}
                                </a>
                                <a v-bind:href="'/action_sequences/' + trigger.data.sequence.id + '/edit'" class="white-text">
                                    {{ trigger.data.sequence.name }}
                                </a>
                            </span>

                            <a class="secondary-content white-text"
                               v-bind:href="'/api/v1/action_sequence_triggers/' + trigger.data.id + '/skip'"
                               v-on:click="link_post">
                                <i class="material-icons">update</i>
                            </a>
                        </div>
                    </li>

                    <li class="collection-item" v-for="intention in action_sequence_intentions.filter(t => t.should_be_started)">
                        <div v-if="intention.data" class="white-text">
                            <span style="display: inline-block; width: calc(100% - 30px);">
                                <i class="material-icons">explore</i>
                                <a v-if="intention.data.timestamps.last_start !== null" class="white-text">
                                    {{ intention.data.timestamps.last_start.split(" ")[1] }}
                                </a>
                                <a v-bind:href="'/action_sequences/' + intention.data.sequence.id + '/edit'" class="white-text">
                                    {{ intention.data.sequence.name }}
                                </a>
                            </span>

                            <a class="secondary-content white-text"
                               v-bind:href="'/api/v1/action_sequence_intentions/' + intention.data.id + '/skip'"
                               v-on:click="link_post">
                                <i class="material-icons">update</i>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>

            <!--
                Action Sequence triggers/intentions running
            -->
            <div :class="wrapperClasses"
                 v-if="action_sequence_triggers.filter(t => t.running).length > 0
                    || action_sequence_intentions.filter(t => t.running).length > 0">

                <ul class="collection ok with-header">
                    <li class="collection-header">
                        <i class="material-icons">playlist_play</i>
                        {{ (action_sequence_triggers.filter(t => t.running).length +
                        action_sequence_intentions.filter(t => t.running).length) }}
                        {{ $tc("labels.action_sequences", action_sequence_intentions.filter(t => t.running).length) }}
                        {{ $t("labels.running") }}
                    </li>

                    <li class="collection-item" v-for="trigger in action_sequence_triggers.filter(t => t.running)">
                        <div v-if="trigger.data" class="white-text">
                            <span style="display: inline-block; width: calc(100% - 30px);">
                                <i class="material-icons">flare</i>
                                <a v-if="trigger.data.timestamps.last_start !== null" class="white-text">
                                    {{ trigger.data.timestamps.last_start.split(" ")[1] }}
                                </a>
                                <a v-bind:href="'/action_sequences/' + trigger.data.sequence.id + '/edit'" class="white-text">
                                    {{ trigger.data.sequence.name }}
                                </a>
                            </span>

                            <a class="secondary-content white-text"
                               v-bind:href="'/api/v1/action_sequence_triggers/' + trigger.data.id + '/skip'"
                               v-on:click="link_post">
                                <i class="material-icons">update</i>
                            </a>
                        </div>
                    </li>

                    <li class="collection-item" v-for="intention in action_sequence_intentions.filter(t => t.running)">
                        <div v-if="intention.data" class="white-text">
                            <span style="display: inline-block; width: calc(100% - 30px);">
                                <i class="material-icons">explore</i>
                                <a v-if="intention.data.timestamps.last_start !== null" class="white-text">
                                    {{ intention.data.timestamps.last_start.split(" ")[1] }}
                                </a>
                                <a v-bind:href="'/action_sequences/' + intention.data.sequence.id + '/edit'" class="white-text">
                                    {{ intention.data.sequence.name }}
                                </a>
                            </span>

                            <a class="secondary-content white-text"
                               v-bind:href="'/api/v1/action_sequence_intentions/' + intention.data.id + '/skip'"
                               v-on:click="link_post">
                                <i class="material-icons">update</i>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>

            <!--
                Terraria ok
            -->
            <div :class="wrapperClasses" v-if="terraria.filter(t => !t.data.state_ok).length < 1">
                <ul class="collection ok with-header">
                    <li class="collection-header">
                        <i class="material-icons">video_label</i>
                        {{ terraria_ok_count }}
                        {{ $tc("labels.terraria", terraria_ok_count) }}
                    </li>

                    <li class="collection-item">
                        <div class="white-text">
                            {{ terraria_ok_count }}
                            {{ $tc("labels.terraria", terraria_ok_count) }}
                            {{ $t("labels.ok") }}
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</template>

<script>
    import AnimalAddWeightModal from './animal_add_weight-modal.vue';

    export default {

        data () {
            return {
                action_sequence_intention_ids: [],
                action_sequence_schedule_ids: [],
                action_sequence_trigger_ids: [],
                animal_feeding_schedule_ids: [],
                animal_weighing_schedule_ids: [],
                suggestion_ids: [],
                terrarium_ids: [],
                controlunit_ids: [],
                physical_sensor_ids: [],
                terraria_ok_count: 0
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

        components: {
            'animal-add-weight-modal': AnimalAddWeightModal
        },

        computed: {
            suggestions () {
                let that = this;
                return this.$store.state.suggestions.filter(function(s) {
                    return that.suggestion_ids.includes(s.id) && s.data !== null
                });
            },

            controlunits () {
                let that = this;
                return this.$store.state.controlunits.filter(function(c) {
                    return that.controlunit_ids.includes(c.id) && c.data !== null
                });
            },

            terraria () {
                let that = this;
                return this.$store.state.terraria.filter(function(t) {
                    return that.terrarium_ids.includes(t.id) && t.data !== null
                });
            },

            physical_sensors () {
                let that = this;
                return this.$store.state.physical_sensors.filter(function(p) {
                    return that.physical_sensor_ids.includes(p.id) && p.data !== null
                });
            },

            animal_feeding_schedules () {
                let that = this;
                return this.$store.state.animal_feeding_schedules.filter(function(s) {
                    return that.animal_feeding_schedule_ids.includes(s.id) && s.data !== null
                });
            },

            animal_weighing_schedules () {
                let that = this;
                return this.$store.state.animal_weighing_schedules.filter(function(s) {
                    return that.animal_weighing_schedule_ids.includes(s.id) && s.data !== null
                });
            },

            action_sequence_schedules () {
                let that = this;
                return this.$store.state.action_sequence_schedules.filter(function(s) {
                    return that.action_sequence_schedule_ids.includes(s.id) && s.data !== null
                });
            },

            action_sequence_intentions () {
                let that = this;
                return this.$store.state.action_sequence_intentions.filter(function(i) {
                    return that.action_sequence_intention_ids.includes(i.id) && i.data !== null
                });
            },

            action_sequence_triggers () {
                let that = this;
                return this.$store.state.action_sequence_triggers.filter(function(t) {
                    return that.action_sequence_trigger_ids.includes(t.id) && t.data !== null
                });
            }
        },

        methods: {
            handleCiliatusObjectUpdated: function(object_information) {
                this.$nextTick(() => this.refresh_grid());
            },

            refresh_grid: function() {
                $('.dropdown-button').dropdown({
                    constrain_width: false
                });
                $('.modal').modal();
                let grid = $('#' + this.containerId + '.masonry-grid');
                if (grid.length > 0) {
                    grid.masonry('reloadItems');
                    grid.masonry('layout');
                }
                $('.tooltipped').tooltip({delay: 50});
                $('.datepicker').pickadate({
                    selectMonths: true,
                    selectYears: 15,
                    format: 'yyyy-mm-dd',
                });
            },

            submit: function(e) {
                window.submit_form(e);
            },

            link_post: function(e) {
                e.preventDefault();

                let old = e;
                let parentElement = e.target.href ? e.target : e.target.parentElement;
                let oldContent = parentElement.innerHTML;
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
                let that = this;
                $.ajax({
                    url: '/api/v1/dashboard',
                    method: 'GET',
                    success: function (data) {
                        that.suggestion_ids = data.data.suggestions.map(s => s.id);
                        that.controlunit_ids = data.data.controlunits.map(c => c.id);
                        that.terrarium_ids = data.data.terraria.map(t => t.id);
                        that.physical_sensor_ids = data.data.physical_sensors.map(t => t.id);
                        that.animal_feeding_schedule_ids = data.data.animal_feeding_schedules.map(s => s.id);
                        that.animal_weighing_schedule_ids = data.data.animal_weighing_schedules.map(s => s.id);
                        that.action_sequence_schedule_ids = data.data.action_sequence_schedules.map(s => s.id);
                        that.action_sequence_trigger_ids = data.data.action_sequence_triggers.map(s => s.id);
                        that.action_sequence_intention_ids = data.data.action_sequence_intentions.map(s => s.id);
                        that.terraria_ok_count = data.data.terraria_ok_count;

                        that.$parent.ensureObjects('suggestions', that.suggestion_ids, data.data.suggestions);
                        that.$parent.ensureObjects('controlunits', that.controlunit_ids, data.data.controlunits);
                        that.$parent.ensureObjects('terraria', that.terrarium_ids, data.data.terraria);
                        that.$parent.ensureObjects('physical_sensors', that.physical_sensor_ids, data.data.physical_sensors);
                        that.$parent.ensureObjects('animal_feeding_schedules', that.animal_feeding_schedule_ids, data.data.animal_feeding_schedules);
                        that.$parent.ensureObjects('animal_weighing_schedules', that.animal_weighing_schedule_ids, data.data.animal_weighing_schedules);
                        that.$parent.ensureObjects('action_sequence_schedules', that.action_sequence_schedule_ids, data.data.action_sequence_schedules);
                        that.$parent.ensureObjects('action_sequence_triggers', that.action_sequence_trigger_ids, data.data.action_sequence_triggers);
                        that.$parent.ensureObjects('action_sequence_intentions', that.action_sequence_intention_ids, data.data.action_sequence_intentions);

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
            let that = this;
            setTimeout(function() {
                that.load_data(true);
            }, 100);

            if (this.refreshTimeoutSeconds !== null) {
                setInterval(function() {
                    that.load_data();
                }, this.refreshTimeoutSeconds * 1000)
            }

            window.eventHubVue.$on('CiliatusObjectUpdated', this.handleCiliatusObjectUpdated);
        }
    }
</script>
