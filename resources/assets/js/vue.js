var Vue = require('vue');

import Peity from 'vue-peity'

Vue.component('inline-graph', {

    template: '#graph-template',

    components: {
        Peity
    },

    props: ['source', 'type', 'options', 'parentid', 'graphtype'],

    computed: {
        graphData: function() {
            if (this.data === undefined)
                return '';

            return this.data.toString()
        }
    },

    data: function() {
        return {
            data: []
        }
    },

    events: {
        SensorreadingCreated: function(value) {
            this.data.push(value);
        },
        TerrariumUpdated: function(t) {
            if (t.terrarium.id == this.parentid) {
                if (this.graphtype == 'humidity_percent')
                    this.data = t.terrarium.humidity_history;
                else if (this.graphtype == 'temperature_celsius')
                    this.data = t.terrarium.temperature_history;
            }
        }
    },

    created: function() {
        $.getJSON(this.source, function(history) {
            this.data = history.data;
        }.bind(this));
    }
});

Vue.component('terraria-widget', {

    template: '#terraria-widget-template',

    data: function() {
        return {
            terraria: []
        }
    },

    components: {
        Peity
    },

    props: {
        terraid: {
            type: String,
            default: '',
            required: false
        }
    },

    events: {
        TerrariumUpdated: function(t) {
            var item = null;
            this.terraria.forEach(function(data, index) {
                if (data.id === t.terrarium.id) {
                    item = index;
                }
            });
            this.$broadcast('TerrariumUpdated', t);
            this.terraria[item].display_name = t.terrarium.display_name;
            this.terraria[item].animals = t.terrarium.animals;
            this.terraria[item].cooked_temperature_celsius = t.terrarium.cooked_temperature_celsius;
            this.terraria[item].cooked_humidity_percent = t.terrarium.cooked_humidity_percent;
        }

    },

    created: function() {
        $.getJSON('/api/v1/terraria/' + this.terraid, function(terraria) {
            this.terraria = terraria.data;
        }.bind(this));
    }

});

Vue.component('criticalstates-widget', {

    template: '#criticalstates-widget-template',

    computed: {
        criticalstateClasses: function() {
            var soft_state = this.soft_state;

            return {
                'danger': soft_state !== true,
                'warning': soft_state === true
            }
        }
    },

    data: function() {
        return {
            criticalstates: []
        }
    },

    events: {
        CriticalStateCreated: function(cs) {
            this.criticalstates.push(cs.critical_state)
        },
        CriticalStateDeleted: function(cs) {
            var item = null;
            this.criticalstates.forEach(function(data, index) {
                if (data.id === cs.critical_state.id) {
                    item = data;
                }
            });
            this.criticalstates.$remove(item);
        }
    },

    created: function() {
        $.getJSON('/api/v1/critical_states', function(critical_states) {
            this.criticalstates = critical_states.data;
        }.bind(this));
    }

});

window.dashboardVue = new Vue({

    el: 'body',

    components: {
        'peity': Peity
    },

    methods: {
        addCriticalState: function(cs) {
            this.$broadcast('CriticalStateCreated', cs)
        },
        removeCriticalState: function(cs) {
            this.$broadcast('CriticalStateDeleted', cs)
        },
        updateTerrarium: function(t) {
            this.$broadcast('TerrariumUpdated', t)
        }
    }
});