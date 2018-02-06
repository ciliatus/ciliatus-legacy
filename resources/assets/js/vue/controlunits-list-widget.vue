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

                    <th  class="hide-on-small-only" data-field="software_version">
                        <a href="#!" v-on:click="set_order('software_version')">{{ $t('labels.software_version') }}</a>
                        <i v-show="order.field == 'software_version' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'software_version' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_software_version" type="text" v-model="filter.software_version" v-on:keyup.enter="set_filter">
                            <label for="filter_software_version">Filter</label>
                        </div>
                    </th>

                    <th data-field="timestamps.last_heartbeat">
                        {{ $t('labels.last_heartbeat') }}
                    </th>

                    <th class="hide-on-small-only" data-field="client_server_time_diff_seconds">
                        {{ $t('labels.client_server_time_diff') }}
                    </th>

                    <th class="hide-on-small-only" style="width: 40px">
                    </th>
                </tr>
                </thead>

                <template v-for="controlunit in controlunits">
                    <tbody>
                        <tr class="collapsible-header">

                            <td>
                                <span>
                                    <i class="material-icons">developer_board</i>
                                    <a v-bind:href="'/controlunits/' + controlunit.id">{{ controlunit.name }}</a>
                                    <span v-if="!controlunit.active"> - {{ $t('labels.inactive') }}</span>
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                <span>
                                    {{ controlunit.software_version }}
                                </span>
                            </td>

                            <td>
                                {{ $t(
                                    'units.' + $getMatchingTimeDiff(controlunit.timestamps.last_heartbeat_diff).unit,
                                    {val: $getMatchingTimeDiff(controlunit.timestamps.last_heartbeat_diff).val}
                                )}}
                            </td>

                            <td class="hide-on-small-only">
                                <span>
                                    {{ controlunit.client_server_time_diff_seconds }}s
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                <span>
                                    <a v-bind:href="'/controlunits/' + controlunit.id + '/edit'">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </span>
                            </td>

                        </tr>
                        <tr class="collapsible-body">
                            <td colspan="3">
                                {{ $tc('components.physical_sensors', 2) }}:
                                <span v-for="(physical_sensor, index) in controlunit.physical_sensors">
                                    <i class="material-icons">memory</i>
                                    <a v-bind:href="'/physical_sensors/' + physical_sensor.id">{{ physical_sensor.name }}</a>
                                    <template v-if="index < controlunit.physical_sensors.length-1">, </template>
                                </span>
                                <br />
                                {{ $tc('components.valves', 2) }}:
                                <span v-for="(valve, index) in controlunit.valves">
                                    <i class="material-icons">transform</i>
                                    <a v-bind:href="'/valves/' + valve.id">{{ valve.name }}</a>
                                    <template v-if="index < controlunit.valves.length-1">, </template>
                                </span>
                                <br />
                                {{ $tc('components.pumps', 2) }}:
                                <span v-for="(pump, index) in controlunit.pumps">
                                    <i class="material-icons">rotate_right</i>
                                    <a v-bind:href="'/pumps/' + pump.id">{{ pump.name }}</a>
                                    <template v-if="index < controlunit.pumps.length-1">, </template>
                                </span>
                                <br />
                                {{ $tc('components.generic_components', 2) }}:
                                <span v-for="(generic_component, index) in controlunit.generic_components">
                                    <i class="material-icons">{{ generic_component.type.icon }}</i>
                                    <a v-bind:href="'/generic_components/' + generic_component.id">{{ generic_component.name }}</a>
                                    <template v-if="index < controlunit.generic_components.length-1">, </template>
                                </span>
                                <br />
                            </td>
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
            controlunits: [],
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
        sourceFilter: {
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
        update: function(cu) {
            var item = null;
            this.controlunits.forEach(function(data, index) {
                if (data.id === cu.controlunit.id) {
                    item = index;
                }
            });
            if (item !== null) {
                var that = this;
                $.ajax({
                    url: '/api/v1/controlunits/' + cu.controlunit.id,
                    method: 'GET',
                    success: function (data) {
                        that.controlunits.splice(item, 1, data.data);
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            }
        },

        delete: function(cu) {
            var item = null;
            this.controlunits.forEach(function(data, index) {
                if (data.id === cu.controlunit.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.controlunits.splice(item, 1);
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
                url: '/api/v1/controlunits?with[]=physical_sensors&with[]=valves&with[]=pumps&with[]=generic_components' +
                     '&page=' + that.page + that.filter_string + that.order_string + '&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    that.meta = data.meta;
                    that.controlunits = data.data;
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

        var that = this;
        setTimeout(function() {
            that.set_filter();
        }, 100);

        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function() {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000)
        }
    }
}
</script>
