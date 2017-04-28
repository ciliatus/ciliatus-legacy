<template>
    <div :class="[containerClasses, 'masonry-grid']" :id="containerId">
        <div v-for="logical_sensor in logical_sensors">
            <div :class="wrapperClasses">
                <div class="card">
                    <div class="card-content orange darken-4 white-text">
                        <i class="material-icons">memory</i>
                        {{ $tc("components.logical_sensors", 2) }}
                    </div>

                    <div class="card-content">
                        <span class="card-title activator truncate">
                            <span>{{ logical_sensor.name }}</span>
                            <i class="material-icons right">more_vert</i>
                        </span>

                        <p>
                            <span>{{ $t("labels.type") }}: {{ $t("labels." + logical_sensor.type) }}</span>
                        </p>
                    </div>

                    <div class="card-action">
                        <a v-bind:href="'/logical_sensors/' + logical_sensor.id">{{ $t("buttons.details") }}</a>
                        <a v-bind:href="'/logical_sensors/' + logical_sensor.id + '/edit'">{{ $t("buttons.edit") }}</a>
                    </div>

                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">{{ $tc("components.physical_sensors", 1) }}<i class="material-icons right">close</i></span>

                        <p>
                            <span v-if="logical_sensor.physical_sensor">
                                {{ $tc("components.physical_sensor", 1) }}:
                                <a v-bind:href="'/physical_sensors/' + logical_sensor.physical_sensor.id">{{ logical_sensor.physical_sensor.name }}</a>
                            </span>
                        </p>

                        <span class="card-title grey-text text-darken-4">{{ $tc("components.logical_sensor_thresholds", 2) }}</span>

                        <p v-for="lst in logical_sensor.thresholds">
                            {{ $t("labels.starts_at") }} {{ lst.timestamps.starts }}:
                            <strong>
                                <span v-show="lst.rawvalue_lowerlimit && !lst.rawvalue_upperlimit">min {{ lst.rawvalue_lowerlimit }}{{ $t("units." + logical_sensor.type) }}</span>
                                <span v-show="!lst.rawvalue_lowerlimit && lst.rawvalue_upperlimit">max {{ lst.rawvalue_upperlimit }}{{ $t("units." + logical_sensor.type) }}</span>
                                <span v-show="lst.rawvalue_lowerlimit && lst.rawvalue_upperlimit">{{ lst.rawvalue_lowerlimit }} - {{ lst.rawvalue_upperlimit }}{{ $t("units." + logical_sensor.type) }}</span>
                            </strong>

                            <span v-show="lst.id == logical_sensor.current_threshold_id">
                                <span class="new badge" v-bind:data-badge-caption="$t('labels.active')"> </span>
                            </span>
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
            logical_sensors: []
        }
    },

    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        logical_sensorId: {
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
            default: 'logical_sensors-masonry-grid',
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
            if (item === null && this.subscribeAdd === true) {
                this.logical_sensors.push(cu.logical_sensor);
            }
            else if (item !== null) {
                this.logical_sensors.splice(item, 1, cu.logical_sensor);
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
            this.logical_sensors.forEach(function(data, index) {
                if (data.id === cu.logical_sensor_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.logical_sensors.splice(item, 1);
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
                url: '/api/v1/logical_sensors/' + that.logical_sensorId + '?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    if (that.logical_sensorId !== '') {
                        that.logical_sensors = [data.data];
                    }
                    else {
                        that.logical_sensors = data.data;
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
                .listen('LogicalSensorUpdated', (e) => {
                this.update(e);
        }).listen('LogicalSensorDeleted', (e) => {
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
