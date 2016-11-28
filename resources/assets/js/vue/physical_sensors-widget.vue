<template>
    <div>
        <div :class="wrapperClasses" v-for="physical_sensor in physical_sensors">
            <div class="card">
                <div class="card-content teal lighten-2 white-text">
                    {{ $tc("components.physical_sensors", 2) }}
                </div>

                <div class="card-content">
                    <span class="card-title activator truncate">
                        <span>{{ physical_sensor.name }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>
                    <p>
                    </p>
                </div>

                <div class="card-action">
                    <a v-bind:href="'/physical_sensors/' + physical_sensor.id">{{ $t("buttons.details") }}</a>
                    <a v-bind:href="'/physical_sensors/' + physical_sensor.id + '/edit'">{{ $t("buttons.edit") }}</a>
                </div>

                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">{{ $tc("components.logical_sensors", 2) }}</span>

                    <p v-for="logical_sensor in physical_sensor.logical_sensors">
                        <a v-bind:href="'/logical_sensors/' + logical_sensor.id">{{ logical_sensor.name }}</a>
                        <i>{{ $t("labels." + logical_sensor.type) }}</i>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
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
        update: function(cu) {
            var item = null;
            this.physical_sensors.forEach(function(data, index) {
                if (data.id === cu.physical_sensor.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true1) {
                this.physical_sensors.push(cu.physical_sensor);
            }
            else if (item !== null) {
                this.physical_sensors.splice(item, 1, cu.physical_sensor);
            }
        },

        delete: function(cu) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.physical_sensors.forEach(function(data, index) {
                if (data.id === cu.physical_sensor.id) {
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
                .listen('physical_sensorUpdated', (e) => {
                this.update(e);
        }).listen('physical_sensorDeleted', (e) => {
                this.delete(e);
        });

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/physical_sensors/' + that.physical_sensorId,
            method: 'GET',
            success: function (data) {
                if (that.physical_sensorId !== '') {
                    that.physical_sensors = [data.data];
                }
                else {
                    that.physical_sensors = data.data;
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
