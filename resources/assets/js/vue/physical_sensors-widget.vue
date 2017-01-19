<template>
    <div :class="[containerClasses, 'masonry-grid']" :id="containerId">
        <div v-for="physical_sensor in physical_sensors">
            <div :class="wrapperClasses">
                <div class="card">
                    <div class="card-content teal lighten-1 white-text">
                        <i class="material-icons">memory</i>
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
                        <span class="card-title grey-text text-darken-4">{{ $tc("components.logical_sensors", 2) }} <i class="material-icons right">close</i></span>

                        <p v-for="logical_sensor in physical_sensor.logical_sensors">
                            <a v-bind:href="'/logical_sensors/' + logical_sensor.id">{{ logical_sensor.name }}</a>
                            <i>{{ $t("labels." + logical_sensor.type) }}</i>
                        </p>
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
            physical_sensors: []
        }
    },

    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        physical_sensorId: {
            type: String,
            default: '',
            required: false
        },
        sourceFilter: {
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
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        },
        containerId: {
            type: String,
            default: 'physical_sensors-masonry-grid',
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
            if (item === null && this.subscribeAdd === true) {
                this.physical_sensors.push(cu.physical_sensor);
            }
            else if (item !== null) {
                this.physical_sensors.splice(item, 1, cu.physical_sensor);
            }

            this.$nextTick(function() {
                this.refresh_grid();
            });
        },

        delete: function(cu) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.physical_sensors.forEach(function(data, index) {
                if (data.id === cu.physical_sensor_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.physical_sensors.splice(item, 1);
            }

            this.$nextTick(function() {
                this.refresh_grid();
            });
        },

        refresh_grid: function() {
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
        },

        load_data: function() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/physical_sensors/' + that.physical_sensorId + '?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    if (that.physical_sensorId !== '') {
                        that.physical_sensors = [data.data];
                    }
                    else {
                        that.physical_sensors = data.data;
                    }

                    that.$nextTick(function() {
                        $('#' + that.containerId).masonry({
                            columnWidth: '.col',
                            itemSelector: '.col',
                        });
                    });

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
                .listen('physical_sensorUpdated', (e) => {
                this.update(e);
        }).listen('physical_sensorDeleted', (e) => {
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
