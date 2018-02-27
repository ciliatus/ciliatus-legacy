import Peity from 'vue-peity';

import SystemIndicator from './vue/system-indicator.vue';
import LoadingIndicator from './vue/loading-indicator.vue';

import DashboardWidget from './vue/dashboard-widget.vue';
import GoogleGraph from './vue/google-graph.vue';
import DygraphGraph from './vue/dygraph-graph.vue';
import ChartjsGraph from './vue/chartjs-graph.vue';
import InlineGraph from './vue/inline-graph.vue';
import AnimalsWidget from './vue/animals-widget.vue';
import AnimalFeedingsWidget from './vue/animal_feedings-widget.vue';
import AnimalFeedingSchedulesWidget from './vue/animal_feeding_schedules-widget.vue';
import AnimalFeedingSchedulesMatrixWidget from './vue/animal_feeding_schedules-matrix-widget.vue';
import AnimalWeighingSchedulesMatrixWidget from './vue/animal_weighing_schedules-matrix-widget.vue';
import AnimalWeighingsWidget from './vue/animal_weighings-widget.vue';
import AnimalWeighingSchedulesWidget from './vue/animal_weighing_schedules-widget.vue';
import TerrariaWidget from './vue/terraria-widget.vue';
import ControlunitWidget from './vue/controlunit-widget.vue';
import ControlunitsListWidget from './vue/controlunits-list-widget.vue';
import FilesListWidget from './vue/files-list-widget.vue';
import FilesShowWidget from './vue/files-show-widget.vue';
import ActionSequencesListWidget from './vue/action_sequences-list-widget.vue';
import ActionSequencesWidget from './vue/action_sequences-widget.vue';
import PumpWidget from './vue/pump-widget.vue';
import PumpsListWidget from './vue/pumps-list-widget.vue';
import ValveWidget from './vue/valve-widget.vue';
import ValvesListWidget from './vue/valves-list-widget.vue';
import PhysicalSensorWidget from './vue/physical_sensor-widget.vue';
import PhysicalSensorsListWidget from './vue/physical_sensors-list-widget.vue';
import LogicalSensorWidget from './vue/logical_sensor-widget.vue';
import LogicalSensorsListWidget from './vue/logical_sensors-list-widget.vue';
import LogicalSensorThresholdsWidget from './vue/logical_sensor_thresholds-widget.vue';
import GenericComponentsWidget from './vue/generic_components-widget.vue';
import GenericComponentsListWidget from './vue/generic_components-list-widget.vue';
import UsersListWidget from './vue/users-list-widget.vue';
import BiographyEntriesWidget from './vue/biography_entries-widget.vue'
import CaresheetsWidget from './vue/caresheets-widget.vue'
import LogsWidget from './vue/logs-widget.vue';
import ComponentsListWidget from './vue/components-list-widget.vue';

import ApiIoWidget from './vue/api-io-widget.vue';

import BusTypeEditForm from './vue/bus_type_edit-form.vue';
import GenericComponentTypeCreateForm from './vue/generic_component_type_create-form.vue';

import CiliatusObject from "./ciliatus_object";

/**
 * Vuex
 */
import Vuex from "vuex";

global.Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        action_sequences: [],
        action_sequence_intentions: [],
        action_sequence_schedules: [],
        action_sequence_triggers: [],
        animals: [],
        animal_weighings: [],
        animal_feedings: [],
        animal_feeding_types: [],
        animal_weighing_schedules: [],
        animal_feeding_schedules: [],
        biography_entries: [],
        caresheets: [],
        controlunits: [],
        files: [],
        generic_components: [],
        logical_sensors: [],
        logical_sensor_thresholds: [],
        physical_sensors: [],
        pumps: [],
        suggestions: [],
        terraria: [],
        users: [],
        valves: [],

        max_object_age_seconds: 60
    }
});


/**
 * ciliatusVue
 */
global.ciliatusVue = new global.Vue({

    el: '#body',

    i18n: global.i18n,
    store,

    data: {
        terraria: [],
        files: [],
        animals: []
    },

    props: {
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        belongsTo_type: {
            type: String,
            default: '',
            required: false
        },
        belongsTo_id: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        ensureObject (type, id, data, include) {
            if (!type || (!id && !data)) {
                return;
            }

            if (!id) {
                id = data.id;
            }

            if (this.$store.state[type].filter(o => o.id === id).length < 1) {
                this.$store.state[type].push(new CiliatusObject(this, type, id, data, include));
            }
        },

        ensureObjects (type, ids, data, include) {
            if (!ids && !data) {
                return;
            }

            if (!ids) {
                data.forEach(obj => this.ensureObject(type, null, obj));
            }
            else {
                let that = this;
                ids.forEach(function (id) {
                    that.ensureObject(
                        type,
                        id,
                        data ? data.filter(o => o && o.id === id)[0] : undefined,
                        include
                    )
                });
            }
        },

        removeObject (object) {
            this.$store.state[object.type].splice(
                this.$store.state[object.type].findIndex(o => o.id === object.id),
                1
            );
            window.eventHubVue.$emit('CiliatusObjectDeleted', {type: object.type, id: object.id});
        },

        __deleteEventName(type) {
            let event_names = {
                action_sequences: 'ActionSequenceDeleted',
                action_sequence_intentions: 'ActionSequenceIntentionDeleted',
                action_sequence_schedules: 'ActionSequenceScheduleDeleted',
                action_sequence_triggers: 'ActionSequenceTriggerDeleted',
                animals: 'AnimalDeleted',
                animal_weighings: 'AnimalWeighingEventDeleted',
                animal_feedings: 'AnimalFeedingEventDeleted',
                animal_weighing_schedules: 'AnimalWeighingSchedulePropertyDeleted',
                animal_feeding_schedules: 'AnimalFeedingSchedulePropertyDeleted',
                biography_entries: 'BiographyEntryEventDeleted',
                caresheets: 'CaresheetDeleted',
                controlunits: 'ControlunitDeleted',
                files: 'FileDeleted',
                generic_components: 'GenericComponentDeleted',
                logical_sensors: 'LogicalSensorDeleted',
                logical_sensor_thresholds: 'LogicalSensorThresholdsDeleted',
                physical_sensors: 'PhysicalSensorDeleted',
                pumps: 'PumpDeleted',
                suggestions: 'SuggestionDeleted',
                terraria: 'TerrariumDeleted',
                users: 'UserDeleted',
                valves: 'ValveDeleted',
            };

            return event_names[type];
        },

        __updateEventName(type) {
            let event_names = {
                action_sequences: 'ActionSequenceUpdated',
                action_sequence_intentions: 'ActionSequenceIntentionUpdated',
                action_sequence_schedules: 'ActionSequenceScheduleUpdated',
                action_sequence_triggers: 'ActionSequenceTriggerUpdated',
                animals: 'AnimalUpdated',
                animal_weighings: 'AnimalWeighingEventUpdated',
                animal_feedings: 'AnimalFeedingEventUpdated',
                animal_weighing_schedules: 'AnimalWeighingSchedulePropertyUpdated',
                animal_feeding_schedules: 'AnimalFeedingSchedulePropertyUpdated',
                biography_entries: 'BiographyEntryEventUpdated',
                caresheets: 'CaresheetUpdated',
                controlunits: 'ControlunitUpdated',
                files: 'FileUpdated',
                generic_components: 'GenericComponentUpdated',
                logical_sensors: 'LogicalSensorUpdated',
                logical_sensor_thresholds: 'LogicalSensorThresholdsUpdated',
                physical_sensors: 'PhysicalSensorUpdated',
                pumps: 'PumpUpdated',
                suggestions: 'SuggestionUpdated',
                terraria: 'TerrariumUpdated',
                users: 'UserUpdated',
                valves: 'ValveUpdated',
            };

            return event_names[type];
        }
    },

    created () {
        let that = this;
        let event_name;
        Object.keys(this.$store.state).forEach(function (k) {
            if (event_name = that.__updateEventName(k)) {
                window
                    .echo
                    .private('dashboard-updates')
                    .listen(event_name, function (e) {
                        that.$store.state[k].filter(obj => obj.id === e.id).forEach(obj => obj.refresh(false, .2));
                    });
            }

            if (event_name = that.__deleteEventName(k)) {
                window
                    .echo
                    .private('dashboard-updates')
                    .listen(event_name, function (e) {
                        that.$store.state[k].filter(obj => obj.id === e.id).forEach(obj => that.removeObject(obj));
                    });
            }
        });

    },

    components: {
        'system-indicator': SystemIndicator,
        'loading-indicator': LoadingIndicator,

        'dashboard-widget': DashboardWidget,
        'peity': Peity,
        'google-graph': GoogleGraph,
        'dygraph-graph': DygraphGraph,
        'chartjs-graph': ChartjsGraph,
        'inline-graph': InlineGraph,
        'animals-widget': AnimalsWidget,
        'animal_feedings-widget': AnimalFeedingsWidget,
        'animal_feeding_schedules-widget': AnimalFeedingSchedulesWidget,
        'animal_feeding_schedules-matrix-widget': AnimalFeedingSchedulesMatrixWidget,
        'animal_weighing_schedules-matrix-widget': AnimalWeighingSchedulesMatrixWidget,
        'animal_weighings-widget': AnimalWeighingsWidget,
        'animal_weighing_schedules-widget': AnimalWeighingSchedulesWidget,
        'terraria-widget': TerrariaWidget,
        'controlunit-widget': ControlunitWidget,
        'controlunits-list-widget': ControlunitsListWidget,
        'files-list-widget': FilesListWidget,
        'files-show-widget': FilesShowWidget,
        'action_sequences-list-widget': ActionSequencesListWidget,
        'action_sequences-widget': ActionSequencesWidget,
        'pump-widget': PumpWidget,
        'pumps-list-widget': PumpsListWidget,
        'valve-widget': ValveWidget,
        'valves-list-widget': ValvesListWidget,
        'physical_sensor-widget': PhysicalSensorWidget,
        'physical_sensors-list-widget': PhysicalSensorsListWidget,
        'logical_sensor-widget': LogicalSensorWidget,
        'logical_sensors-list-widget': LogicalSensorsListWidget,
        'logical_sensor_thresholds-widget': LogicalSensorThresholdsWidget,
        'generic_components-widget': GenericComponentsWidget,
        'generic_components-list-widget': GenericComponentsListWidget,
        'users-list-widget': UsersListWidget,
        'biography_entries-widget': BiographyEntriesWidget,
        'caresheets-widget': CaresheetsWidget,
        'logs-widget': LogsWidget,
        'components-list-widget': ComponentsListWidget,

        'api-io-widget': ApiIoWidget,

        'bus-type-edit-form': BusTypeEditForm,
        'generic_component_type_create-form': GenericComponentTypeCreateForm
    }
});