<template>
    <div :class="[containerClasses, 'masonry-grid']" :id="containerId">
        <div v-for="controlunit in controlunits">
            <div :class="wrapperClasses">
                <div class="card">
                    <div class="card-content teal lighten-1 white-text">
                        <i class="material-icons">developer_board</i>
                        {{ $tc("components.controlunits", 2) }}
                    </div>

                    <div class="card-content">
                        <span class="card-title activator truncate">
                            <span>{{ controlunit.name }}</span>
                            <i class="material-icons right">more_vert</i>
                        </span>

                        <p>
                            {{ $t('labels.last_heartbeat') }}:
                            <!-- @TODO: there has to be a better way to do this -->
                            <span v-show="controlunit.timestamps.last_heartbeat_diff.days > 0"
                                  class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="controlunit.timestamps.last_heartbeat">
                                {{ $t('units.days_ago', {val: controlunit.timestamps.last_heartbeat_diff.days}) }}
                            </span>
                            <span v-show="controlunit.timestamps.last_heartbeat_diff.days < 1 &&
                                          controlunit.timestamps.last_heartbeat_diff.hours > 0"
                                          class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="controlunit.timestamps.last_heartbeat">
                                {{ $t('units.hours_ago', {val: controlunit.timestamps.last_heartbeat_diff.hours}) }}
                            </span>
                            <span v-show="controlunit.timestamps.last_heartbeat_diff.days < 1 &&
                                          controlunit.timestamps.last_heartbeat_diff.hours < 1 &&
                                          controlunit.timestamps.last_heartbeat_diff.minutes > 1"
                                          class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="controlunit.timestamps.last_heartbeat">
                                {{ $t('units.minutes_ago', {val: controlunit.timestamps.last_heartbeat_diff.minutes}) }}
                            </span>
                            <span v-show="controlunit.timestamps.last_heartbeat_diff.days < 1 &&
                                          controlunit.timestamps.last_heartbeat_diff.hours < 1 &&
                                          controlunit.timestamps.last_heartbeat_diff.minutes < 2"
                                          class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="controlunit.timestamps.last_heartbeat">
                                {{ $t('units.just_now') }}
                            </span>
                            <br />
                        </p>
                    </div>

                    <div class="card-action">
                        <a v-bind:href="'/controlunits/' + controlunit.id">{{ $t("buttons.details") }}</a>
                        <a v-bind:href="'/controlunits/' + controlunit.id + '/edit'">{{ $t("buttons.edit") }}</a>
                    </div>

                    <div class="card-reveal">
                        <span class="card-title">{{ $tc("components.physical_sensors", 2) }}<i class="material-icons right">close</i></span>

                        <p v-for="physical_sensor in controlunit.physical_sensors">
                            <a v-bind:href="'/physical_sensors/' + physical_sensor.id">{{ physical_sensor.name }}</a>
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
            controlunits: []
        }
    },

    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        controlunitId: {
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
            default: 'valves-masonry-grid',
            required: false
        }
    },

    methods: {
        update: function(cu) {
            var item = null;
            this.controlunits.forEach(function(data, index) {
                if (data.id === cu.controlunit.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true) {
                this.controlunits.push(cu.controlunit);
            }
            else if (item !== null) {
                this.controlunits.splice(item, 1, cu.controlunit);
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
            this.controlunits.forEach(function(data, index) {
                if (data.id === cu.controlunit_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.controlunits.splice(item, 1);
            }

            this.$nextTick(function() {
                this.refresh_grid();
            });
        },

        refresh_grid: function() {
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
            $('.tooltipped').tooltip({delay: 50});
        },

        load_data: function() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/controlunits/' + that.controlunitId + '?raw=true',
                method: 'GET',
                success: function (data) {
                    if (that.controlunitId !== '') {
                        that.controlunits = [data.data];
                    }
                    else {
                        that.controlunits = data.data;
                    }

                    that.$nextTick(function() {
                        $('#' + that.containerId).masonry({
                            columnWidth: '.col',
                            itemSelector: '.col',
                        });
                $('.tooltipped').tooltip({delay: 50});
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
                .listen('ControlunitUpdated', (e) => {
                this.update(e);
        }).listen('ControlunitDeleted', (e) => {
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
