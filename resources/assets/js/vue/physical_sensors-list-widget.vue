<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight collapsible" data-collapsible="expandable">
                <thead>
                <tr>
                    <th data-field="name">
                        <a href="#!" v-on:click="set_order('name')">{{ $t('labels.name') }}</a>
                        <i v-show="order.field == 'name' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'name' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_name" type="text" v-model="filter.name" v-on:keyup.enter="set_filter">
                            <label for="filter_name">Filter</label>
                        </div>
                    </th>
                    <th data-field="model">
                        <a href="#!" v-on:click="set_order('model')">{{ $t('labels.model') }}</a>
                        <i v-show="order.field == 'model' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'model' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_model" type="text" v-model="filter.model" v-on:keyup.enter="set_filter">
                            <label for="filter_model">Filter</label>
                        </div>
                    </th>
                    <th data-field="controlunit" class="hide-on-small-only">
                        <a href="#!" v-on:click="set_order('controlunit')">{{ $tc('components.controlunit', 1) }}</a>
                        <i v-show="order.field == 'controlunit' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'controlunit' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_controlunit" type="text" v-model="filter['controlunit.name']" v-on:keyup.enter="set_filter">
                            <label for="filter_controlunit">Filter</label>
                        </div>
                    </th>
                    <th data-field="terrarium" class="hide-on-med-and-down">
                        <a href="#!" v-on:click="set_order('terraria.display_name')">{{ $tc('components.terraria', 1) }}</a>
                        <i v-show="order.field == 'physical_sensor.terrarium.display_name' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'physical_sensor.terrarium.display_name' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_terrarium" type="text" v-model="filter['terrarium.display_name']" v-on:keyup.enter="set_filter">
                            <label for="filter_terrarium">Filter</label>
                        </div>
                    </th>
                    <th style="width: 40px">
                    </th>
                </tr>
                </thead>

                <template v-for="physical_sensor in physical_sensors">
                    <tbody>
                        <tr class="collapsible-header">

                            <td>
                                <span>
                                    <i class="material-icons">memory</i>
                                    <a v-bind:href="'/physical_sensors/' + physical_sensor.id">{{ physical_sensor.name }}</a>
                                </span>
                            </td>

                            <td>
                                <span>
                                    {{ physical_sensor.model }}
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                <span v-if="physical_sensor.controlunit">
                                    <i class="material-icons">developer_board</i>
                                    <a v-bind:href="'/controlunits/' + physical_sensor.controlunit.id">{{ physical_sensor.controlunit.name }}</a>
                                </span>
                            </td>

                            <td class="hide-on-med-and-down">
                                <span v-if="physical_sensor.terrarium">
                                    <i class="material-icons">video_label</i>
                                    <a v-bind:href="'/terraria/' + physical_sensor.terrarium.id">{{ physical_sensor.terrarium.display_name }}</a>
                                </span>
                            </td>

                            <td>
                                <span>
                                    <a class="waves-effect waves-light btn-small" v-bind:href="'/physical_sensors/' + physical_sensor.id + '/edit'">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </span>
                            </td>

                        </tr>
                        <tr class="collapsible-body">
                            <td colspan="4">
                                {{ $t('labels.last_heartbeat') }}:
                                <!-- @TODO: there has to be a better way to do this -->
                                <span v-show="physical_sensor.timestamps.last_heartbeat_diff.days > 0"
                                      class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="physical_sensor.timestamps.last_heartbeat">
                                        {{ $t('units.days_ago', {val: physical_sensor.timestamps.last_heartbeat_diff.days}) }}
                                    </span>
                                <span v-show="physical_sensor.timestamps.last_heartbeat_diff.days < 1 &&
                                              physical_sensor.timestamps.last_heartbeat_diff.hours > 0"
                                      class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="physical_sensor.timestamps.last_heartbeat">
                                        {{ $t('units.hours_ago', {val: physical_sensor.timestamps.last_heartbeat_diff.hours}) }}
                                    </span>
                                <span v-show="physical_sensor.timestamps.last_heartbeat_diff.days < 1 &&
                                              physical_sensor.timestamps.last_heartbeat_diff.hours < 1 &&
                                              physical_sensor.timestamps.last_heartbeat_diff.minutes > 1"
                                      class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="physical_sensor.timestamps.last_heartbeat">
                                        {{ $t('units.minutes_ago', {val: physical_sensor.timestamps.last_heartbeat_diff.minutes}) }}
                                    </span>
                                <span v-show="physical_sensor.timestamps.last_heartbeat_diff.days < 1 &&
                                              physical_sensor.timestamps.last_heartbeat_diff.hours < 1 &&
                                              physical_sensor.timestamps.last_heartbeat_diff.minutes < 2"
                                      class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="physical_sensor.timestamps.last_heartbeat">
                                        {{ $t('units.just_now') }}
                                    </span>
                                <br />
                                {{ $tc('components.logical_sensors', 2) }}:
                                <span v-for="(logical_sensor, index) in physical_sensor.logical_sensors">
                                    <i class="material-icons">memory</i>
                                    <a v-bind:href="'/logical_sensors/' + logical_sensor.id">{{ logical_sensor.name }}</a>
                                    <template v-if="index < physical_sensor.logical_sensors.length-1">, </template>
                                </span>
                            </td>
                            <td class="hide-on-med-and-down"> </td>
                        </tr>
                    </tbody>
                </template>
            </table>

            <ul class="pagination" v-if="meta.hasOwnProperty('pagination')">
                <li v-bind:class="{ 'disabled': meta.pagination.current_page == 1, 'waves-effect': meta.pagination.current_page != 1 }">
                    <a href="#!" v-on:click="set_page(1)"><i class="material-icons">first_page</i></a>
                </li>
                <li v-bind:class="{ 'disabled': meta.pagination.current_page == 1, 'waves-effect': meta.pagination.current_page != 1 }">
                    <a href="#!" v-on:click="set_page(meta.pagination.current_page-1)"><i class="material-icons">chevron_left</i></a>
                </li>

                <li v-if="meta.pagination.current_page-3 > 0" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page-3)">{{ meta.pagination.current_page-3 }}</a></li>
                <li v-if="meta.pagination.current_page-2 > 0" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page-2)">{{ meta.pagination.current_page-2 }}</a></li>
                <li v-if="meta.pagination.current_page-1 > 0" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page-1)">{{ meta.pagination.current_page-1 }}</a></li>

                <li class="active"><a href="#!">{{ meta.pagination.current_page }}</a></li>

                <li v-if="meta.pagination.current_page+1 <= meta.pagination.total_pages" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page+1)">{{ meta.pagination.current_page+1 }}</a></li>
                <li v-if="meta.pagination.current_page+2 <= meta.pagination.total_pages" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page+2)">{{ meta.pagination.current_page+2 }}</a></li>
                <li v-if="meta.pagination.current_page+3 <= meta.pagination.total_pages" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page+3)">{{ meta.pagination.current_page+3 }}</a></li>

                <li v-bind:class="{ 'disabled': meta.pagination.current_page == meta.pagination.total_pages, 'waves-effect': meta.pagination.current_page != meta.pagination.total_pages }">
                    <a href="#!" v-on:click="set_page(meta.pagination.current_page+1)"><i class="material-icons">chevron_right</i></a>
                </li>
                <li v-bind:class="{ 'disabled': meta.pagination.current_page == meta.pagination.total_pages, 'waves-effect': meta.pagination.current_page != meta.pagination.total_pages }">
                    <a href="#!" v-on:click="set_page(meta.pagination.total_pages)"><i class="material-icons">last_page</i></a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            physical_sensors: [],
            meta: [],
            filter: {
                name: '',
                model: '',
                'controlunit.name': '',
                'terrarium.display_name': ''
            },
            filter_string: '',
            order: {
                field: 'name',
                direction: 'asc'
            },
            order_string: '',
            page: 1
        }
    },

    props: {
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        refreshTimeoutSeconds: {
            type: Number,
            default: 60,
            required: false
        }
    },

    methods: {
        update: function(ps) {
            var item = null;
            this.physical_sensors.forEach(function(data, index) {
                if (data.id === ps.physical_sensor.id) {
                    item = index;
                }
            });
            if (item !== null) {
                this.physical_sensors.splice(item, 1, ps.physical_sensor);
            }
        },

        delete: function(ps) {
            var item = null;
            this.physical_sensors.forEach(function(data, index) {
                if (data.id === ps.physical_sensor.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.physical_sensors.splice(item, 1);
            }
        },
        
        set_order: function(field) {
            if (this.order.field == field || field === null) {
                if (this.order.direction == 'asc') {
                    this.order.direction = 'desc';
                }
                else {
                    this.order.direction = 'asc';
                }
            }
            else {
                this.order.field = field;
            }

            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            this.load_data();
        },
        set_filter: function() {
            this.filter_string = '&';
            for (var prop in this.filter) {
                if (this.filter.hasOwnProperty(prop)) {
                    if (this.filter[prop] !== null
                        && this.filter[prop] !== '') {

                        this.filter_string += 'filter[' + prop + ']=like:*' + this.filter[prop] + '*&';
                    }
                }
            }
            this.load_data();
        },
        set_page: function(page) {
            this.page = page;
            this.load_data();
        },
        load_data: function() {
            window.eventHubVue.processStarted();
            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            var that = this;
            $.ajax({
                url: '/api/v1/physical_sensors?page=' + that.page + that.filter_string + that.order_string + '&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    that.meta = data.meta;
                    that.physical_sensors = data.data;
                    that.$nextTick(function() {
                        $('table.collapsible').collapsibletable();
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
                .listen('PhysicalSensorUpdated', (e) => {
                this.update(e);
        }).listen('PhysicalensorDeleted', (e) => {
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
