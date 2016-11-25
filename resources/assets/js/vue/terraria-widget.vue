<template>
    <div>
        <div :class="wrapperClasses" v-for="terrarium in terraria">
            <div class="card">
                <div class="card-image waves-effect waves-block waves-light terrarium-card-image"
                     v-bind:class="terrarium.default_background_filepath ? '' : 'teal darken-1'"
                     v-bind:style="terrarium.default_background_filepath ? 'background-image: url(\'' + terrarium.default_background_filepath + '\');' : ''">
                    <div>
                        <inline-graph :parentid="terrarium.id" graphtype="humidity_percent" type="line" :options="{'fill': null, 'strokeWidth': '3', 'stroke': '#2196f3', width: '100%', height:'140px', min: 1, max: 99}" :source="'/api/v1/terraria/'+terrarium.id+'/sensorreadingsByType/humidity_percent'"></inline-graph>
                    </div>

                    <div style="position: relative; top: -145px">
                        <inline-graph :parentid="terrarium.id" graphtype="temperature_celsius" type="line" :options="{'fill': null, 'strokeWidth': '3', 'stroke': '#b71c1c', width: '100%', height:'140px', min: 1, max: 99}" :source="'/api/v1/terraria/'+terrarium.id+'/sensorreadingsByType/temperature_celsius'"></inline-graph>
                    </div>
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4 truncate">
                        <span>{{ terrarium.display_name }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>
                    <p>
                        <span v-bind:class="{ 'red-text': !terrarium.temperature_ok, 'darken-3': !terrarium.temperature_ok }">{{ $t("labels.temperature") }}: {{ terrarium.cooked_temperature_celsius }}°C</span><br />
                        <span v-bind:class="{ 'red-text': !terrarium.humidity_ok, 'darken-3': !terrarium.humidity_ok }">{{ $t("labels.humidity") }}: {{ terrarium.cooked_humidity_percent }}%</span>
                        <span v-show="!terrarium.heartbeat_ok" class="red-text darken-3">
                            <br />{{ $t("tooltips.hearbeat_critical") }}
                        </span>
                    </p>
                </div>

                <div class="card-action">
                    <a v-bind:href="'/terraria/' + terrarium.id">{{ $t("buttons.details") }}</a>
                    <a v-bind:href="'/terraria/' + terrarium.id + '/edit'">{{ $t("buttons.edit") }}</a>
                </div>

                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">{{ $tc("components.animals", 2) }}<i class="material-icons right">close</i></span>
                    <p>
                        <div v-for="animal in terrarium.animals">
                            <a v-bind:href="'/animals/' + animal.id">{{ animal.display_name }}</a> <i>{{ animal.common_name }}</i>
                        </div>

                        <span class="card-title grey-text text-darken-4">{{ $tc("components.action_sequence_schedules", 2) }}</span>
                        <p>
                        <div v-for="as in terrarium.action_sequences">
                            <span v-for="ass in as.schedules">
                                <a v-bind:href="'/action_sequences/' + as.id">{{ as.name }}</a> <i>{{ $t("labels.starts_at") }} {{ ass.timestamps.starts }}</i>
                            </span>
                        </div>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import InlineGraph from './inline-graph.vue';

export default {
    data () {
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

    components: {
        'inline-graph': InlineGraph
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

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/terraria/' + that.terrariumId,
            method: 'GET',
            success: function (data) {
                if (that.terrariumId !== '') {
                    that.terraria = [data.data];
                }
                else {
                    that.terraria = data.data;
                }

                window.eventHubVue.processEnded();
            },
            error: function (error) {
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });
    }

}
</script>