<template>
    <div :class="containerClasses" :id="containerId">
        <div :class="wrapperClasses" v-for="terrarium in terraria">
            <br />
            <a v-bind:href="'/terraria/' + terrarium.id">
                <strong>{{ terrarium.display_name }}</strong>
            </a>

            <div style="margin-left: 20px">
                <strong>{{ $tc('components.animals', 2) }}</strong>

                <div v-for="animal in terrarium.animals" style="margin-left: 20px">
                    <a v-bind:href="'/animals/' + animal.id">
                        <strong>{{ animal.display_name }}</strong>
                    </a>
                    <i>{{ animal.common_name }} {{ animal.latin_name }}</i>
                </div>
            </div>

            <div style="margin-left: 20px">
                <strong>{{ $tc('components.action_sequences', 2) }}</strong>

                <div v-for="action_sequence in terrarium.action_sequences" style="margin-left: 20px">
                    <a v-bind:href="'/action_sequences/' + action_sequence.id">
                        <strong>{{ action_sequence.name }}</strong>
                    </a>
                    <i v-for="schedule in action_sequence.schedules">{{ schedule.timestamps.starts }}</i>

                    <div style="margin-left: 20px">
                        <strong>{{ $tc('components.actions', 2) }}</strong>

                        <div v-for="action in action_sequence.actions" style="margin-left: 20px">
                            <div v-if="action.target_object != undefined">
                                <a v-bind:href="'/actions/' + action.id">
                                    <strong>{{ action.target_object.name }}</strong>
                                </a>
                                <i class="material-icons">keyboard_arrow_right</i>
                                {{ action.desired_state }}
                                <i>{{ action.duration_minutes }} {{ $tc('units.minutes', action.duration_minutes) }}</i>

                                <div v-if="action.target_object == undefined">
                                    <strong class="red-text">unknown</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-left: 20px">
                <strong>{{ $tc('components.physical_sensors', 2) }}</strong>

                <div v-for="physical_sensor in terrarium.physical_sensors" style="margin-left: 20px">
                    <a v-bind:href="'/physical_sensors/' + physical_sensor.id">
                        <strong>{{ physical_sensor.name }}</strong>
                    </a>
                    <div style="margin-left: 20px">
                        <strong>{{ $tc('components.controlunit', 1) }}</strong>
                        <div v-if="physical_sensor.controlunit != undefined" style="margin-left: 20px">
                            <a v-bind:href="'/controlunits/' + physical_sensor.controlunit.id">
                                <strong>{{ physical_sensor.controlunit.name }}</strong>
                            </a>
                        </div>

                        <strong>{{ $tc('components.logical_sensors', 2) }}</strong>

                        <div v-for="logical_sensor in physical_sensor.logical_sensors" style="margin-left: 20px">
                            <a v-bind:href="'/logical_sensors/' + logical_sensor.id">
                                <strong>{{ logical_sensor.name }}</strong>
                            </a>

                            <i>{{ logical_sensor.type }}</i>
                            <br />
                            {{ logical_sensor.rawvalue }}
                        </div>
                    </div>
                </div>
            </div>


            <div style="margin-left: 20px">
                <strong>{{ $tc('components.valves', 2) }}</strong>

                <div v-for="valve in terrarium.valves" style="margin-left: 20px">
                    <a v-bind:href="'/valves/' + valve.id">
                        <strong>{{ valve.name }}</strong> <i>{{ valve.state }}</i>
                    </a>

                    <div style="margin-left: 20px">
                        <strong>{{ $tc('components.controlunit', 1) }}</strong>
                        <div v-if="valve.controlunit != undefined" style="margin-left: 20px">
                            <a v-bind:href="'/controlunits/' + valve.controlunit.id">
                                <strong>{{ valve.controlunit.name }}</strong>
                            </a>
                        </div>


                        <strong>{{ $tc('components.pumps', 2) }}</strong>
                        <div v-if="valve.pump != undefined" style="margin-left: 20px">

                            <a v-bind:href="'/pumps/' + valve.pump.id">
                                <strong>{{ valve.pump.name }}</strong>
                            </a>

                            <div style="margin-left: 20px">
                                <strong>{{ $tc('components.controlunit', 1) }}</strong>
                                <div v-if="valve.pump.controlunit != undefined" style="margin-left: 20px">
                                    <a v-bind:href="'/controlunits/' + valve.pump.controlunit.id">
                                        <strong>{{ valve.pump.controlunit.name }}</strong>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    data () {
        return {
            terraria: []
        }
    },

    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
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
        },
        containerClasses: {
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
            }
            window.eventHubVue.$emit('TerrariumGraphUpdated', t);
        },

        delete: function(t) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.terraria.forEach(function(data, index) {
                if (data.id === t.terrarium_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.terraria.splice(item, 1);
            }
        },

        submit: function(e) {
            window.submit_form(e);
        },

        load_data: function() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/terraria/?raw=true&history_minutes=0',
                method: 'GET',
                success: function (data) {
                    that.terraria = data.data;

                    window.eventHubVue.processEnded();
                },
                error: function (error) {
                    console.log(JSON.stringify(error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('TerrariumUpdated', (e) => {
                this.update(e);
            }).listen('TerrariumDeleted', (e) => {
                this.delete(e);
        });


        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function() {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000)
        }
    }

}
</script>