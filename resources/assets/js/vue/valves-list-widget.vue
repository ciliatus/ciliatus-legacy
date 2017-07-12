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
                    <th data-field="pump">
                        <a href="#!" v-on:click="set_order('pump')">{{ $tc('components.pump', 1) }}</a>
                        <i v-show="order.field == 'pump' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'pump' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_pump" type="text" v-model="filter['pump.name']" v-on:keyup.enter="set_filter">
                            <label for="filter_pump">Filter</label>
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
                        <i v-show="order.field == 'valve.terrarium.display_name' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'valve.terrarium.display_name' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_terrarium" type="text" v-model="filter['terrarium.display_name']" v-on:keyup.enter="set_filter">
                            <label for="filter_terrarium">Filter</label>
                        </div>
                    </th>
                    <th style="width: 40px">
                    </th>
                </tr>
                </thead>

                <template v-for="valve in valves">
                    <tbody>
                        <tr class="collapsible-header">

                            <td>
                                <span>
                                    <i class="material-icons">transform</i>
                                    <a v-bind:href="'/valves/' + valve.id">{{ valve.name }}</a>
                                </span>
                            </td>

                            <td>
                                <span v-if="valve.pump">
                                    <i class="material-icons">rotate_right</i>
                                    <a v-bind:href="'/pumps/' + valve.pump.id">{{ valve.pump.name }}</a>
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                <span v-if="valve.controlunit">
                                    <i class="material-icons">developer_board</i>
                                    <a v-bind:href="'/controlunits/' + valve.controlunit.id">{{ valve.controlunit.name }}</a>
                                </span>
                            </td>

                            <td class="hide-on-med-and-down">
                                <span v-if="valve.terrarium">
                                    <i class="material-icons">video_label</i>
                                    <a v-bind:href="'/terraria/' + valve.terrarium.id">{{ valve.terrarium.display_name }}</a>
                                </span>
                            </td>

                            <td>
                                <span>
                                    <a v-bind:href="'/valves/' + valve.id + '/edit'">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </span>
                            </td>

                        </tr>
                        <tr class="collapsible-body">
                            <td colspan="3">

                            </td>
                            <td class="hide-on-small-only"> </td>
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
            valves: [],
            meta: [],
            filter: {
                name: '',
                'pump.name': '',
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
        }
    },

    methods: {
        update: function(v) {
            var item = null;
            this.valves.forEach(function(data, index) {
                if (data.id === v.valve.id) {
                    item = index;
                }
            });
            if (item !== null) {
                this.valves.splice(item, 1, v.valve);
            }
        },

        delete: function(v) {
            var item = null;
            this.valves.forEach(function(data, index) {
                if (data.id === v.valve.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.valves.splice(item, 1);
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
                url: '/api/v1/valves?with[]=pump&with[]=terrarium&with[]=controlunit&page=' + that.page + that.filter_string + that.order_string + '&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    that.meta = data.meta;
                    that.valves = data.data;
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
                .listen('ValveUpdated', (e) => {
                this.update(e);
        }).listen('ValveDeleted', (e) => {
                this.delete(e);
        });

        var that = this;
        setTimeout(function() {
            that.set_filter();
        }, 100);
    }
}
</script>
