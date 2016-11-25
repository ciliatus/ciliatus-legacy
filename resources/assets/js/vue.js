var Vue = require('vue');
import Peity from 'vue-peity'

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
Vue.config.lang = 'de';

Object.keys(locales).forEach(function (lang) {
    Vue.locale(lang, locales[lang])
});


import InlineGraph from './vue/inline-graph.vue';
import AnimalsWidget from './vue/animals-widget.vue';
import TerrariaWidget from './vue/terraria-widget.vue';
import ControlunitsWidget from './vue/controlunit-widget.vue';
import FilesWidget from './vue/files-widget.vue';
import ActionSequenceScheduleWidget from './vue/action_sequence_schedule-widget.vue';
import PumpsWidget from './vue/pumps-widget.vue';
import ValvesWidget from './vue/valves-widget.vue';
import PhysicalSensorsWidget from './vue/physical_sensors-widget.vue';
import LogicalSensorsWidget from './vue/logical_sensors-widget.vue';

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
        'peity': Peity,
        'inline-graph': InlineGraph,
        'animals-widget': AnimalsWidget,
        'terraria-widget': TerrariaWidget,
        'controlunits-widget': ControlunitsWidget,
        'files-widget': FilesWidget,
        'action_sequence_schedule-widget': ActionSequenceScheduleWidget,
        'pumps-widget': PumpsWidget,
        'valves-widget': ValvesWidget,
        'physical_sensors-widget': PhysicalSensorsWidget,
        'logical_sensors-widget': LogicalSensorsWidget
    },

    methods: {
        addCriticalState: function(cs) {
            this.$emit('CriticalStateCreated', cs)
        },
        removeCriticalState: function(cs) {
            this.$emit('CriticalStateDeleted', cs)
        },
        updateTerrarium: function(t) {
            this.$emit('TerrariumUpdated', t)
        },
        deleteTerrarium: function(t) {
            this.$emit('TerrariumDeleted', t)
        },
        updateAnimal: function(a) {
            this.$emit('AnimalUpdated', a)
        },
        deleteAnimal: function(a) {
            this.$emit('AnimalDeleted', a)
        },
        updateFile: function(a) {
            this.$emit('FileUpdated', a)
        },
        deleteFile: function(a) {
            this.$emit('FileDeleted', a)
        },
        updateActionSequenceSchedule: function(a) {
            this.$emit('ActionSequenceScheduleUpdated', a)
        },
        deleteActionSequenceSchedule: function(a) {
            this.$emit('ActionSequenceScheduleDeleted', a)
        }

    }
});