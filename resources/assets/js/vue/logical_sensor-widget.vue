<template>
    <div :class="wrapperClasses" v-if="logical_sensor.data">
        <div class="card">
            <div class="card-header">
                <i class="material-icons">transform</i>
                {{ $t("labels.logical_sensor") }}
            </div>

            <div class="card-content">
                <span class="card-title activator">
                    {{ logical_sensor.data.name }}
                    <i class="material-icons right">more_vert</i>
                </span>

                <div v-if="logical_sensor.data.type.length">
                    <span>{{ $t("labels.type") }}: {{ $t("labels." + logical_sensor.data.type) }}</span>
                </div>
            </div>

            <div class="card-action">
                <a v-bind:href="'/logical_sensors/' + logical_sensor.data.id">{{ $t("buttons.details") }}</a>
                <a v-bind:href="'/logical_sensors/' + logical_sensor.data.id + '/edit'">{{ $t("buttons.edit") }}</a>
            </div>

            <div class="card-reveal">
                <span class="card-title">{{ $tc("labels.physical_sensors", 1) }}<i class="material-icons right">close</i></span>

                <div>
                    <span v-if="physical_sensor && physical_sensor.data">
                        {{ $t("labels.physical_sensor") }}:
                        <a v-bind:href="'/physical_sensors/' + physical_sensor.data.id">{{ physical_sensor.data.name }}</a>
                    </span>
                </div>

                <span class="card-title">{{ $tc("labels.logical_sensor_thresholds", 2) }}</span>

                <div v-for="lst in logical_sensor_thresholds">
                    {{ $t("labels.starts_at") }} {{ lst.data.timestamps.starts }}:
                    <strong>
                        <span v-show="lst.data.rawvalue_lowerlimit && !lst.data.rawvalue_upperlimit">
                            min {{ lst.data.rawvalue_lowerlimit }}{{ $t("units." + logical_sensor.data.type) }}
                        </span>
                        <span v-show="!lst.data.rawvalue_lowerlimit && lst.data.rawvalue_upperlimit">
                            max {{ lst.data.rawvalue_upperlimit }}{{ $t("units." + logical_sensor.data.type) }}
                        </span>
                        <span v-show="lst.data.rawvalue_lowerlimit && lst.data.rawvalue_upperlimit">
                            {{ lst.data.rawvalue_lowerlimit }} - {{ lst.data.rawvalue_upperlimit }}{{ $t("units." + logical_sensor.data.type) }}
                        </span>
                    </strong>

                    <span v-show="lst.data.id === logical_sensor.data.current_threshold_id">
                        <span class="new badge teal" v-bind:data-badge-caption="$t('labels.active')"> </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                physical_sensor_id: [],
                logical_sensor_threshold_ids: []
            }
        },

        props: {
            logicalSensorId: {
                type: String,
                required: true
            },
            wrapperClasses: {
                type: String,
                default: '',
                required: false
            }
        },

        computed: {
            logical_sensor () {
                let sensor = this.$store.state.logical_sensors.filter(l => l.id === this.logicalSensorId);
                return sensor.length > 0 ? sensor[0] : {};
            },

            physical_sensor () {
                let sensor = this.$store.state.physical_sensors.filter(p => p.id === this.physical_sensor_id);
                return sensor.length > 0 ? sensor[0] : {};
            },

            logical_sensor_thresholds () {
                return this.$store.state.logical_sensor_thresholds.filter(l => this.logical_sensor_threshold_ids.includes(l.id));
            }
        },

        methods: {
            load_data: function() {
                let that = this;

                $.ajax({
                    url: '/api/v1/logical_sensors/' + that.logicalSensorId + '?with[]=physical_sensor&with[]=thresholds',
                    method: 'GET',
                    success: function (data) {
                        that.physical_sensor_id = data.data.physical_sensor_id;
                        that.logical_sensor_threshold_ids = data.data.thresholds.map(t => t.id);

                        that.$parent.ensureObject('logical_sensors', that.logicalSensorId, data.data);
                        that.$parent.ensureObject('physical_sensors', that.physical_sensor_id, data.data.physical_sensor);
                        that.$parent.ensureObjects('logical_sensor_thresholds', that.logical_sensor_threshold_ids, data.data.thresholds);
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            }
        },

        created: function() {
            this.load_data();
        }
    }
</script>