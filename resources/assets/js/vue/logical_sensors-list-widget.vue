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
                        <th data-field="physical_sensor" class="hide-on-small-only">
                            <a href="#!" v-on:click="set_order('physical_sensors.name')">{{ $tc('components.physical_sensors', 1) }}</a>
                            <i v-show="order.field == 'physical_sensor.name' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                            <i v-show="order.field == 'physical_sensor.name' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                            <div class="input-field inline">
                                <input id="filter_physical_sensor" type="text" v-model="filter['physical_sensor.name']" v-on:keyup.enter="set_filter">
                                <label for="filter_physical_sensor">Filter</label>
                            </div>
                        </th>
                        <th data-field="terrarium" class="hide-on-med-and-down">
                            <a href="#!" v-on:click="set_order('terraria.display_name')">{{ $tc('components.terraria', 1) }}</a>
                            <i v-show="order.field == 'physical_sensor.terrarium.display_name' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                            <i v-show="order.field == 'physical_sensor.terrarium.display_name' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                            <div class="input-field inline">
                                <input id="filter_terrarium" type="text" v-model="filter['physical_sensor.terrarium.display_name']" v-on:keyup.enter="set_filter">
                                <label for="filter_terrarium">Filter</label>
                            </div>
                        </th>
                        <th data-field="type">
                            <a href="#!" v-on:click="set_order('type')">{{ $t('labels.type') }}</a>
                            <i v-show="order.field == 'type' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                            <i v-show="order.field == 'type' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                            <div class="input-field inline">
                                <input id="filter_type" type="text" v-model="filter.type" v-on:keyup.enter="set_filter">
                                <label for="filter_type">Filter</label>
                            </div>
                        </th>
                        <th data-field="rawvalue" class="hide-on-small-only">
                            <a href="#!" v-on:click="set_order('rawvalue')">{{ $t('labels.rawvalue', 1) }}</a>
                            <i v-show="order.field == 'rawvalue' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                            <i v-show="order.field == 'rawvalue' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                        </th>
                        <th style="width: 40px">
                        </th>
                    </tr>
                </thead>

                <template v-for="logical_sensor in logical_sensors">
                    <tbody>
                        <tr class="collapsible-header">

                            <td>
                                <span>
                                    <i class="material-icons">memory</i>
                                    <a v-bind:href="'/logical_sensors/' + logical_sensor.id">{{ logical_sensor.name }}</a>
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                <span v-if="logical_sensor.physical_sensor">
                                    <i class="material-icons">memory</i>
                                    <a v-bind:href="'/physical_sensors/' + logical_sensor.physical_sensor.id">{{ logical_sensor.physical_sensor.name }}</a>
                                </span>
                            </td>

                            <td class="hide-on-med-and-down">
                                <span v-if="logical_sensor.physical_sensor && logical_sensor.physical_sensor.terrarium">
                                    <i class="material-icons">video_label</i>
                                    <a v-bind:href="'/terraria/' + logical_sensor.physical_sensor.terrarium.id">{{ logical_sensor.physical_sensor.terrarium.display_name }}</a>
                                </span>
                            </td>

                            <td>
                                {{ logical_sensor.type }}
                            </td>

                            <td class="hide-on-small-only">
                                <span>{{ Math.round(logical_sensor.rawvalue, 2) }}</span>
                                <span v-if="get_accuracy_adjustment(logical_sensor) !== null">
                                    <span v-if="get_accuracy_adjustment(logical_sensor) > 0">
                                        <span class="green-text darken-2">(+{{ get_accuracy_adjustment(logical_sensor) }})</span>
                                    </span>
                                    <span v-else>
                                        <span class="red darken-2">({{ get_accuracy_adjustment(logical_sensor) }})</span>
                                    </span>
                                </span>
                            </td>

                            <td>
                                <span>
                                    <a v-bind:href="'/logical_sensors/' + logical_sensor.id + '/edit'">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </span>
                            </td>

                        </tr>
                        <tr class="collapsible-body">
                            <td>
                                {{ $t('labels.rawlimitlo') }}: {{ logical_sensor.rawvalue_lowerlimit }}<br />
                                {{ $t('labels.rawlimithi') }}: {{ logical_sensor.rawvalue_upperlimit }}
                            </td>
                            <td class="hide-on-small-only">
                                <span v-if="logical_sensor.physical_sensor.controlunit">
                                    {{ $tc('components.controlunits', 1) }}:
                                    <i class="material-icons">developer_board</i>
                                    <a v-bind:href="'/controlunits/' + logical_sensor.physical_sensor.controlunit.id">{{ logical_sensor.physical_sensor.controlunit.name }}</a>
                                </span>
                                <br />
                                <span>{{ $t('labels.model') }}: {{ logical_sensor.physical_sensor.model }}</span>
                            </td>
                            <td class="hide-on-med-and-down">
                                <span v-if="logical_sensor.physical_sensor.terrarium">
                                    {{ $tc('components.terraria', 1) }} {{ $t('labels.temperature_celsius') }}: {{ logical_sensor.physical_sensor.terrarium.cooked_temperature_celsius }}°C<br />
                                    {{ $tc('components.terraria', 1) }} {{ $t('labels.humidity_percent') }}: {{ logical_sensor.physical_sensor.terrarium.cooked_humidity_percent }}%
                                </span>
                            </td>
                            <td> </td>
                            <td  class="hide-on-small-only"> </td>
                            <td> </td>
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
            logical_sensors: [],
            meta: [],
            filter: {
                name: '',
                type: '',
                'physical_sensor.terrarium.display_name': '',
                'physical_sensor.name': ''
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
        sourceFilter: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        get_accuracy_adjustment: function(ls) {
            if (ls.properties === undefined) {
                return null;
            }

            var adjustment = ls.properties.filter(function (el) {
                return  el.type == 'LogicalSensorAccuracy' &&
                        el.name == 'adjust_rawvalue' &&
                        el.value.length > 0;
            });
            if (adjustment.length > 0) {
                return adjustment[0].value;
            }
            return null;
        },
        update: function(ls) {
            var item = null;
            this.logical_sensors.forEach(function(data, index) {
                if (data.id === ls.logical_sensor.id) {
                    item = index;
                }
            });
            if (item !== null) {
                this.logical_sensors.splice(item, 1, ls.logical_sensor);
            }
        },

        delete: function(ls) {
            var item = null;
            this.logical_sensors.forEach(function(data, index) {
                if (data.id === ls.logical_sensor.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.logical_sensors.splice(item, 1);
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

            this.order_string = 'order[' + this.order.field + ']=' + this.order.direction;
            this.load_data();
        },
        set_filter: function() {
            this.filter_string = '&';
            if (this.sourceFilter !== '') {
                this.filter_string += this.sourceFilter + '&';
            }
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
            this.order_string = 'order[' + this.order.field + ']=' + this.order.direction;
            var that = this;
            $.ajax({
                url: '/api/v1/logical_sensors?with[]=physical_sensor&with[]=thresholds&page=' +
                     that.page + that.filter_string + that.order_string,
                method: 'GET',
                success: function (data) {
                    that.meta = data.meta;
                    that.logical_sensors = data.data;
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

        var that = this;
        setTimeout(function() {
            that.set_filter();
        }, 100);
    }
}
</script>
