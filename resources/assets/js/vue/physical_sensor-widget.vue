<template>
    <div :class="wrapperClasses" v-if="physical_sensor.data">
        <div class="card">
            <div class="card-header">
                <i class="material-icons">memory</i>
                {{ $t("labels.physical_sensor") }}
            </div>

            <div class="card-content">
                <span class="card-title">
                    {{ physical_sensor.data.name }}
                </span>

                <div v-for="logical_sensor in logical_sensors">
                    <template v-if="logical_sensor.data">
                        <a :href="'/logical_sensors/' + logical_sensor.data.id">{{ logical_sensor.data.name }}</a>
                        <i>{{ $t("labels." + logical_sensor.data.type) }}</i>
                    </template>
                </div>
            </div>

            <div class="card-action">
                <a v-bind:href="'/physical_sensors/' + physical_sensor.data.id">{{ $t("buttons.details") }}</a>
                <a v-bind:href="'/physical_sensors/' + physical_sensor.data.id + '/edit'">{{ $t("buttons.edit") }}</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    data () {
        return {
            logical_sensor_ids: []
        }
    },

    props: {
        physicalSensorId: {
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

    computed: {
        physical_sensor () {
            let sensor = this.$store.state.physical_sensors.filter(p => p.id = this.physicalSensorId);
            return sensor.length > 0 ? sensor[0] : {};
        },

        logical_sensors () {
            return this.$store.state.logical_sensors.filter(l => this.logical_sensor_ids.includes(l.id));
        }
    },

    methods: {
        load_data: function() {
            this.$parent.ensureObject('physical_sensors', this.physicalSensorId);

            let that = this;

            $.ajax({
                url: '/api/v1/logical_sensors/?all=true&filter[physical_sensor_id]=' + that.physicalSensorId,
                method: 'GET',
                success: function (data) {
                    that.logical_sensor_ids = data.data.map(l => l.id);

                    that.$parent.ensureObjects('logical_sensors', that.logical_sensor_ids, data.data);
                },
                error: function (error) {
                    console.log(JSON.stringify(error));
                }
            });
        }
    },

    created: function() {
        let that = this;
        setTimeout(function() {
            that.load_data();
        }, 2000);
    }
}
</script>
