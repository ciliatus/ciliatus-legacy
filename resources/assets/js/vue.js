var Vue = require('vue');
import Peity from 'vue-peity'

/*
import VueResource from 'vue-resource';
Vue.use(VueResource);
Vue.http.interceptors.push((request, next) => {
    request.headers['X-CSRF-TOKEN'] = Laravel.csrfToken;

    next();
});
*/

$.ajaxPrefilter(function(options) {
    if (!options.beforeSend) {
        options.beforeSend = function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', Laravel.csrfToken);
        }
    }
});

window.eventHubVue = new Vue({
    props: {
        globalLoadingBarCount: {
            type: Number,
            default: 0,
            required: false
        }
    },

    methods: {
        processStarted: function() {
            this.globalLoadingBarCount++;
            this.checkLoadingBarState();
        },

        processEnded: function() {
            this.globalLoadingBarCount--;
            this.checkLoadingBarState();
        },

        checkLoadingBarState: function() {
            if (this.globalLoadingBarCount > 0) {
                $('#global-loading-bar').show();
            }
            else {
                $('#global-loading-bar').hide();
            }
        }
    }
});

var VueI18n = require('vue-i18n');
var locales = require("./lang.js");

Vue.use(VueI18n);

Vue.config.lang = $('body').data('lang');

Object.keys(locales).forEach(function (lang) {
    Vue.locale(lang, locales[lang])
});

import SystemIndicator from './vue/system-indicator.vue';

import DashboardWidget from './vue/dashboard-widget.vue';
import GoogleGraph from './vue/google-graph.vue';
import DygraphGraph from './vue/dygraph-graph.vue';
import InlineGraph from './vue/inline-graph.vue';
import AnimalsWidget from './vue/animals-widget.vue';
import AnimalFeedingsWidget from './vue/animal_feedings-widget.vue';
import AnimalFeedingSchedulesWidget from './vue/animal_feeding_schedules-widget.vue';
import AnimalFeedingSchedulesMatrixWidget from './vue/animal_feeding_schedules-matrix-widget.vue';
import AnimalWeighingSchedulesMatrixWidget from './vue/animal_weighing_schedules-matrix-widget.vue';
import AnimalWeighingsWidget from './vue/animal_weighings-widget.vue';
import AnimalWeighingSchedulesWidget from './vue/animal_weighing_schedules-widget.vue';
import TerrariaWidget from './vue/terraria-widget.vue';
import TerrariaOverviewWidget from './vue/terraria-overview-widget.vue';
import ControlunitsWidget from './vue/controlunit-widget.vue';
import ControlunitsListWidget from './vue/controlunits-list-widget.vue';
import FilesWidget from './vue/files-widget.vue';
import FilesShowWidget from './vue/files-show-widget.vue';
import ActionSequencesWidget from './vue/action_sequences-widget.vue';
import ActionSequenceScheduleWidget from './vue/action_sequence_schedule-widget.vue';
import PumpsWidget from './vue/pumps-widget.vue';
import PumpsListWidget from './vue/pumps-list-widget.vue';
import ValvesWidget from './vue/valves-widget.vue';
import ValvesListWidget from './vue/valves-list-widget.vue';
import PhysicalSensorsWidget from './vue/physical_sensors-widget.vue';
import PhysicalSensorsListWidget from './vue/physical_sensors-list-widget.vue';
import LogicalSensorsWidget from './vue/logical_sensors-widget.vue';
import LogicalSensorsListWidget from './vue/logical_sensors-list-widget.vue';
import GenericComponentsWidget from './vue/generic_components-widget.vue';
import GenericComponentsListWidget from './vue/generic_components-list-widget.vue';
import UsersWidget from './vue/users-widget.vue';
import BiographyEntriesWidget from './vue/biography_entries-widget.vue'
import CaresheetsWidget from './vue/caresheets-widget.vue'
import LogsWidget from './vue/logs-widget.vue';
import ComponentsListWidget from './vue/components-list-widget.vue';

import GenericComponentTypeCreateForm from './vue/generic_component_type_create-form.vue';

window.systemVue = new Vue({

    el: '#system-indicator',

    components: {
        'system-indicator': SystemIndicator
    }

});

window.bodyVue = new Vue({

    el: '#content',

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

    components: {
        'dashboard-widget': DashboardWidget,
        'peity': Peity,
        'google-graph': GoogleGraph,
        'dygraph-graph': DygraphGraph,
        'inline-graph': InlineGraph,
        'animals-widget': AnimalsWidget,
        'animal_feedings-widget': AnimalFeedingsWidget,
        'animal_feeding_schedules-widget': AnimalFeedingSchedulesWidget,
        'animal_feeding_schedules-matrix-widget': AnimalFeedingSchedulesMatrixWidget,
        'animal_weighing_schedules-matrix-widget': AnimalWeighingSchedulesMatrixWidget,
        'animal_weighings-widget': AnimalWeighingsWidget,
        'animal_weighing_schedules-widget': AnimalWeighingSchedulesWidget,
        'terraria-widget': TerrariaWidget,
        'terraria-overview-widget': TerrariaOverviewWidget,
        'controlunits-widget': ControlunitsWidget,
        'controlunits-list-widget': ControlunitsListWidget,
        'files-widget': FilesWidget,
        'files-show-widget': FilesShowWidget,
        'action_sequences-widget': ActionSequencesWidget,
        'action_sequence_schedule-widget': ActionSequenceScheduleWidget,
        'pumps-widget': PumpsWidget,
        'pumps-list-widget': PumpsListWidget,
        'valves-widget': ValvesWidget,
        'valves-list-widget': ValvesListWidget,
        'physical_sensors-widget': PhysicalSensorsWidget,
        'physical_sensors-list-widget': PhysicalSensorsListWidget,
        'logical_sensors-widget': LogicalSensorsWidget,
        'logical_sensors-list-widget': LogicalSensorsListWidget,
        'generic_components-widget': GenericComponentsWidget,
        'generic_components-list-widget': GenericComponentsListWidget,
        'users-widget': UsersWidget,
        'biography_entries-widget': BiographyEntriesWidget,
        'caresheets-widget': CaresheetsWidget,
        'logs-widget': LogsWidget,
        'components-list-widget': ComponentsListWidget,

        'generic_component_type_create-form': GenericComponentTypeCreateForm
    }

});