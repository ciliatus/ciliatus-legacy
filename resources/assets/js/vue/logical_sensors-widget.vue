<template>
    <div>
        <div :class="wrapperClasses" v-for="logical_sensor in logical_sensors">
            <div class="card">
                <div class="card-content teal lighten-2 white-text">
                    {{ $tc("components.logical_sensors", 2) }}
                </div>

                <div class="card-content">
                    <span class="card-title activator truncate">
                        <span>{{ logical_sensor.name }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>
                    <p>
                    </p>
                </div>

                <div class="card-action">
                    <a v-bind:href="'/logical_sensors/' + logical_sensor.id">{{ $t("buttons.details") }}</a>
                    <a v-bind:href="'/logical_sensors/' + logical_sensor.id + '/edit'">{{ $t("buttons.edit") }}</a>
                </div>

                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4"><i class="material-icons right">close</i></span>
                    <p>

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
            logical_sensors: []
        }
    },

    props: {
        logical_sensorId: {
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
            this.logical_sensors.forEach(function(data, index) {
                if (data.id === cu.logical_sensor.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true1) {
                this.logical_sensors.push(cu.logical_sensor);
            }
            else if (item !== null) {
                this.logical_sensors.splice(item, 1, cu.logical_sensor);
            }
        },

        delete: function(cu) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.logical_sensors.forEach(function(data, index) {
                if (data.id === cu.logical_sensor.id) {
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
                .listen('logical_sensorUpdated', (e) => {
                this.update(e);
        }).listen('logical_sensorDeleted', (e) => {
                this.delete(e);
        });

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/logical_sensors/' + that.logical_sensorId,
            method: 'GET',
            success: function (data) {
                if (that.logical_sensorId !== '') {
                    that.logical_sensors = [data.data];
                }
                else {
                    that.logical_sensors = data.data;
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
