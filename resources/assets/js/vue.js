var Vue = require('vue');

import Peity from 'vue-peity'

Vue.component('inline-graph', {

    template: '#inline-graph-template',

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

    methods: {
        createSensorrreading: function(value) {
            this.data.push(value);
        },
        updateTerrariumGraph: function(t) {
            if (t.terrarium.id == this.parentid) {
                if (this.graphtype == 'humidity_percent')
                    this.data = t.terrarium.humidity_history;
                else if (this.graphtype == 'temperature_celsius')
                    this.data = t.terrarium.temperature_history;
            }
        }
    },

    created: function() {
        /*$.getJSON(this.source, function(history) {
            this.data = history.data;
        }.bind(this));*/

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: that.source,
            method: 'GET',
            success: function (data) {
                that.data = data.data;
                window.eventHubVue.processEnded();
            },
            error: function (error) {
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });

        window.eventHubVue.$on('SensorreadingCreated', this.createSensorrreading);
        window.eventHubVue.$on('TerrariumGraphUpdated', this.updateTerrariumGraph);
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
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function(ass) {
            var item = null;
            this.action_sequence_schedules.forEach(function(data, index) {
                if (data.id === ass.action_sequence_schedule.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.action_sequence_schedules.push(ass.action_sequence_schedule);
            }
            else if (item !== null) {
                this.action_sequence_schedules.splice(item, 1, ass.action_sequence_schedule);
            }
        },

        delete: function(ass) {
            var item = null;
            this.action_sequence_schedules.forEach(function(data, index) {
                if (data.id === ass.action_sequence_schedule.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.action_sequence_schedules.splice(item, 1);
            }
        }
    },

    created: function() {
        window.echo.private('dashboard-updates')
                .listen('ActionSequenceScheduleUpdated', (e) => {
                this.update(e);
        }).listen('ActionSequenceScheduleDeleted', (e) => {
                this.delete(e);
        });

        var uri = '';
        if (this.assid === '') {
            uri = '/api/v1/action_sequence_schedules/?filter[last_finished_at]=nottoday';
        }
        else {
            uri = '/api/v1/action_sequence_schedules/' + this.assid;
        }

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: uri,
            method: 'GET',
            success: function (data) {
                that.action_sequence_schedules = data.data;
                window.eventHubVue.processEnded();
            },
            error: function (error) {
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });
    }

});

Vue.component('terraria-widget', {

    template: '#terraria-widget-template',

    data: function() {
        return {
            terraria: []
        }
    },

    props: {
        terrariumId: {
            type: String,
            default: '',
            required: false
        },
        subscribeAdd: {
            type: Boolean,
            default: true,
            required: false
        },
        subscribeDelete: {
            type: Boolean,
            default: true,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function(t) {
            var item = null;
            this.terraria.forEach(function(data, index) {
                if (data.id === t.terrarium.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true) {
                this.terraria.push(t.terrarium);
            }
            else if (item !== null) {
                this.terraria.splice(item, 1, t.terrarium);
                /*
                this.terraria[item].display_name = t.terrarium.display_name;
                this.terraria[item].animals = t.terrarium.animals;
                this.terraria[item].cooked_temperature_celsius = t.terrarium.cooked_temperature_celsius;
                this.terraria[item].cooked_humidity_percent = t.terrarium.cooked_humidity_percent;
                this.terraria[item].heartbeat_ok = t.terrarium.heartbeat_ok;
                this.terraria[item].temperature_ok = t.terrarium.temperature_ok;
                this.terraria[item].humidity_ok = t.terrarium.humidity_ok;
                this.terraria[item].state_ok = t.terrarium.state_ok;
                */
            }
            window.eventHubVue.$emit('TerrariumGraphUpdated', t);
        },

        delete: function(t) {
            if (this.subscribeAdd !== true) {
                return;
            }
            var item = null;
            this.terraria.forEach(function(data, index) {
                if (data.id === t.terrarium.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.terraria.splice(item, 1);
            }
        }

    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('TerrariumUpdated', (e) => {
                this.update(e);
            }).listen('TerrariumDeleted', (e) => {
                this.delete(e);
        });

        /*$.getJSON(, function(terraria) {
            this.terraria = terraria.data;
        }.bind(this));*/

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/terraria/' + that.terrariumId,
            method: 'GET',
            success: function (data) {
                that.terraria = data.data;
                window.eventHubVue.processEnded();
            },
            error: function (error) {
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });
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
        animalId: {
            type: String,
            default: '',
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function(a) {
            var item = null;
            this.animals.forEach(function(data, index) {
                if (data.id === a.animal.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.animals.push(a.animal)
            }
            else if (item !== null) {
                this.animals.splice(item, 1, a.animal);
            }
        },

        delete: function(a) {
            var item = null;
            this.animals.forEach(function(data, index) {
                if (data.id === a.animal.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.animals.splice(item, 1);
            }
        }

    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('AnimalUpdated', (e) => {
                this.update(e);
            }).listen('AnimalDeleted', (e) => {
                this.delete(e);
            });

        /*$.getJSON('/api/v1/animals/' + this.animalId, function(animals) {
            this.animals = animals.data;
        }.bind(this));*/

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/animals/' + that.animalId,
            method: 'GET',
            success: function (data) {
                that.animals = data.data;
                window.eventHubVue.processEnded();
            },
            error: function (error) {
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });
    }

});

Vue.component('physical_sensors-widget', {

    template: '#physical_sensors-widget-template',

    data: function() {
        return {
            physical_sensors: []
        }
    },

    props: {
        physical_sensorId: {
            type: String,
            default: '',
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function(a) {
            var item = null;
            this.physical_sensors.forEach(function(data, index) {
                if (data.id === a.physical_sensor.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.physical_sensors.push(a.physical_sensor)
            }
            else if (item !== null) {
                this.physical_sensors.splice(item, 1, a.physical_sensor);
            }
        },

        delete: function(a) {
            var item = null;
            this.physical_sensors.forEach(function(data, index) {
                if (data.id === a.physical_sensor.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.physical_sensors.splice(item, 1);
            }
        }

    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('PhysicalSensorUpdated', (e) => {
                this.update(e);
            }).listen('PhysicalSensorDeleted', (e) => {
                this.delete(e);
            });

        /*$.getJSON('/api/v1/physical_sensors/' + this.physical_sensorId, function(physical_sensors) {
            this.physical_sensors = physical_sensors.data;
        }.bind(this));*/

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/physical_sensors/' + that.terrariumId,
            method: 'GET',
            success: function (data) {
                that.physical_sensors = data.data;
                window.eventHubVue.processEnded();
            },
            error: function (error) {
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });
    }

});

Vue.component('logical_sensors-widget', {

    template: '#logical_sensors-widget-template',

    data: function() {
        return {
            logical_sensors: []
        }
    },

    props: {
        logical_sensorId: {
            type: String,
            default: '',
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function(a) {
            var item = null;
            this.logical_sensors.forEach(function(data, index) {
                if (data.id === a.logical_sensor.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.logical_sensors.push(a.logical_sensor)
            }
            else if (item !== null) {
                this.logical_sensors.splice(item, 1, a.logical_sensor);
            }
        },

        delete: function(a) {
            var item = null;
            this.logical_sensors.forEach(function(data, index) {
                if (data.id === a.logical_sensor.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.logical_sensors.splice(item, 1);
            }
        }

    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('LogicalSensorUpdated', (e) => {
                this.update(e);
            }).listen('LogicalSensorDeleted', (e) => {
                this.delete(e);
            });

        /*$.getJSON('/api/v1/logical_sensors/' + this.logical_sensorId, function(logical_sensors) {
            this.logical_sensors = logical_sensors.data;
        }.bind(this));*/

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/logical_sensors/' + that.terrariumId,
            method: 'GET',
            success: function (data) {
                that.logical_sensors = data.data;
                window.eventHubVue.processEnded();
            },
            error: function (error) {
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });
    }

});

Vue.component('valves-widget', {

    template: '#valves-widget-template',

    data: function() {
        return {
            valves: []
        }
    },

    props: {
        valveId: {
            type: String,
            default: '',
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function(a) {
            var item = null;
            this.valves.forEach(function(data, index) {
                if (data.id === a.valve.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.valves.push(a.valve)
            }
            else if (item !== null) {
                this.valves.splice(item, 1, a.valve);
            }
        },

        delete: function(a) {
            var item = null;
            this.valves.forEach(function(data, index) {
                if (data.id === a.valve.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.valves.splice(item, 1);
            }
        }

    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('ValveUpdated', (e) => {
                this.update(e);
            }).listen('ValveDeleted', (e) => {
                this.delete(e);
            });

        /*$.getJSON('/api/v1/valves/' + this.valveId, function(valves) {
            this.valves = valves.data;
        }.bind(this));*/

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/valves/' + that.valveId,
            method: 'GET',
            success: function (data) {
                that.valves = data.data;
                window.eventHubVue.processEnded();
            },
            error: function (error) {
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });
    }

});

Vue.component('controlunits-widget', {

    template: '#controlunits-widget-template',

    data: function() {
        return {
            controlunits: []
        }
    },

    props: {
        controlunitId: {
            type: String,
            default: '',
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function(a) {
            var item = null;
            this.controlunits.forEach(function(data, index) {
                if (data.id === a.controlunit.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.controlunits.push(a.controlunit)
            }
            else if (item !== null) {
                this.controlunits.splice(item, 1, a.controlunit);
            }
        },

        delete: function(a) {
            var item = null;
            this.controlunits.forEach(function(data, index) {
                if (data.id === a.controlunit.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.controlunits.splice(item, 1);
            }
        }

    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('ControlunitUpdated', (e) => {
                this.update(e);
            }).listen('ControlunitDeleted', (e) => {
                this.delete(e);
            });

        /*$.getJSON('/api/v1/controlunits/' + this.controlunitId, function(controlunits) {
            this.controlunits = controlunits.data;
        }.bind(this));*/

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/controlunits/' + that.terrariumId,
            method: 'GET',
            success: function (data) {
                that.controlunits = data.data;
                window.eventHubVue.processEnded();
            },
            error: function (error) {
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });
    }

});

Vue.component('files-widget', {

    template: '#files-widget-template',

    data: function() {
        return {
            files: []
        }
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
        update: function(a) {
            var item = null;
            this.files.forEach(function(data, index) {
                if (data.id === a.file.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.files.push(a.animal)
            }
            else if (item !== null) {
                this.files.splice(item, 1, a.animal);
            }
        },

        delete: function(a) {
            var item = null;
            this.files.forEach(function(data, index) {
                if (data.id === a.files.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.files.splice(item, 1);
            }
        }

    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('FileUpdated', (e) => {
                this.update(e);
            }).listen('FileDeleted', (e) => {
                this.delete(e);
            });

        /*$.getJSON('/api/v1/files/' + this.sourceFilter, function(files) {
            this.files = files.data;
        }.bind(this));*/

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/files/' + that.sourceFilter,
            method: 'GET',
            success: function (data) {
                that.files = data.data;
                window.eventHubVue.processEnded();
            },
            error: function (error) {
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });
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

    methods: {
        create: function(cs) {
            this.criticalstates.push(cs.critical_state)
        },
        delete: function(cs) {
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

        window.echo.private('dashboard-updates')
            .listen('CriticalStateCreated', (e) => {
                this.create(e);
            }).listen('CriticalStateDeleted', (e) => {
                this.delete(e);
            });

        /*$.getJSON('/api/v1/critical_states', function(critical_states) {
            this.criticalstates = critical_states.data;
        }.bind(this));*/

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/critical_states',
            method: 'GET',
            success: function (data) {
                that.criticalstates = data.data;
                window.eventHubVue.processEnded();
            },
            error: function (error) {
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });
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
        'peity': Peity
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