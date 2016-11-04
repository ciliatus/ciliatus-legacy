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

Vue.component('action_sequence_schedule-widget', {

    template: '#action_sequence_schedule-widget-template',

    data: function() {
        return {
            action_sequence_schedules: []
        }
    },

    props: {
        assid: {
            type: String,
            default: '',
            required: false
        }
    },

    events: {
        ActionSequenceScheduleUpdated: function(ass) {
            var item = null;
            this.action_sequence_schedules.forEach(function(data, index) {
                if (data.id === ass.action_sequence_schedule.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.action_sequence_schedules.push(ass.action_sequence_schedule);
            }
            else {
                this.action_sequence_schedules.$set(item, ass.action_sequence_schedule);
            }
            this.$broadcast('ActionSequenceScheduleUpdated', ass);
        },

        ActionSequenceScheduleDeleted: function(ass) {
            var item = null;
            this.action_sequence_schedules.forEach(function(data, index) {
                if (data.id === ass.action_sequence_schedule.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.action_sequence_schedules.splice(item, 1);
            }
            this.$broadcast('ActionSequenceScheduleDeleted', ass);
        }
    },

    created: function() {
        if (this.assid === '') {
            $.getJSON('/api/v1/action_sequence_schedules/?filter[last_finished_at]=nottoday', function(action_sequence_schedules) {
                this.action_sequence_schedules = action_sequence_schedules.data;
            }.bind(this));
        }
        else {
            $.getJSON('/api/v1/action_sequence_schedules/' + this.assid, function(action_sequence_schedules) {
                this.action_sequence_schedules = action_sequence_schedules.data;
            }.bind(this));
        }
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
            if (item === null) {
                this.terraria.push(t.terrarium);
            }
            else {
                this.terraria[item].display_name = t.terrarium.display_name;
                this.terraria[item].animals = t.terrarium.animals;
                this.terraria[item].cooked_temperature_celsius = t.terrarium.cooked_temperature_celsius;
                this.terraria[item].cooked_humidity_percent = t.terrarium.cooked_humidity_percent;
                this.terraria[item].heartbeat_ok = t.terrarium.heartbeat_ok;
                this.terraria[item].temperature_ok = t.terrarium.temperature_ok;
                this.terraria[item].humidity_ok = t.terrarium.humidity_ok;
                this.terraria[item].state_ok = t.terrarium.state_ok;
            }
            this.$broadcast('TerrariumUpdated', t);
        },

        TerrariumDeleted: function(t) {
            var item = null;
            this.terraria.forEach(function(data, index) {
                if (data.id === t.terrarium.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.terraria.splice(item, 1);
            }
            this.$broadcast('TerrariumDeleted', t);
        }

    },

    created: function() {
        $.getJSON('/api/v1/terraria/' + this.terraid, function(terraria) {
            this.terraria = terraria.data;
        }.bind(this));
    }

});

Vue.component('animals-widget', {

    template: '#animals-widget-template',

    data: function() {
        return {
            animals: []
        }
    },

    props: {
        animalid: {
            type: String,
            default: '',
            required: false
        }
    },

    events: {
        AnimalUpdated: function(a) {
            var item = null;
            this.animals.forEach(function(data, index) {
                if (data.id === a.animal.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.animals.push(a.animal)
            }
            else {
                this.animals.$set(item, a.animal);
            }
            this.$broadcast('AnimalUpdated', a);
        },

        AnimalDeleted: function(a) {
            var item = null;
            this.animals.forEach(function(data, index) {
                if (data.id === a.animal.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.animals.splice(item, 1);
            }
            this.$broadcast('AnimalDeleted', a);
        }

    },

    created: function() {
        $.getJSON('/api/v1/animals/' + this.animalid, function(animals) {
            this.animals = animals.data;
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
            if (item !== null) {
                this.criticalstates.splice(item, 1);
            }
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
        },
        deleteTerrarium: function(t) {
            this.$broadcast('TerrariumDeleted', t)
        },
        updateAnimal: function(a) {
            this.$broadcast('AnimalUpdated', a)
        },
        deleteAnimal: function(a) {
            this.$broadcast('AnimalDeleted', a)
        },
        updateActionSequenceSchedule: function(a) {
            this.$broadcast('ActionSequenceScheduleUpdated', a)
        },
        deleteActionSequenceSchedule: function(a) {
            this.$broadcast('ActionSequenceScheduleDeleted', a)
        }

    }
});