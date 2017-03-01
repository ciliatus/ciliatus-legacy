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
                    <th data-field="generic_component.type.name">
                        <a href="#!" v-on:click="set_order('type.name_singular')">{{ $t('labels.type') }}</a>
                        <i v-show="order.field == 'type.name_singular' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'type.name_singular' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_generic_component_type_name_singular" type="text" v-model="filter['type.name_singular']" v-on:keyup.enter="set_filter">
                            <label for="filter_generic_component_type_name_singular">Filter</label>
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

                    <th style="width: 40px">
                    </th>
                </tr>
                </thead>

                <template v-for="generic_component in generic_components">
                    <tbody>
                        <tr class="collapsible-header">

                            <td>
                                <span>
                                    <i class="material-icons">{{ generic_component.type.icon }}</i>
                                    <a v-bind:href="'/generic_components/' + generic_component.id">{{ generic_component.name }}</a>
                                </span>
                            </td>

                            <td>
                                <span>
                                    <a v-bind:href="'/generic_component_types/' + generic_component.type.id">{{ generic_component.type.name_singular }}</a>
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                <span v-if="generic_component.controlunit">
                                    <i class="material-icons">developer_board</i>
                                    <a v-bind:href="'/controlunits/' + generic_component.controlunit.id">{{ generic_component.controlunit.name }}</a>
                                </span>
                            </td>

                            <td>
                                <span>
                                    <a class="waves-effect waves-light btn-small" v-bind:href="'/generic_components/' + generic_component.id + '/edit'">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </span>
                            </td>

                        </tr>
                        <tr class="collapsible-body">
                            <td colspan="4">
                                <span v-for="(value, name) in generic_component.properties">
                                    {{ name }}: {{ value }}
                                    <br />
                                </span>
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
            generic_components: [],
            meta: [],
            filter: {
                name: '',
                'type.name_singular': this.defaultTypeFilter,
                'controlunit.name': ''
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
        defaultTypeFilter: {
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
        update: function(ps) {
            var item = null;
            this.generic_components.forEach(function(data, index) {
                if (data.id === ps.generic_component.id) {
                    item = index;
                }
            });
            if (item !== null) {
                this.generic_components.splice(item, 1, ps.generic_component);
            }
        },

        delete: function(ps) {
            var item = null;
            this.generic_components.forEach(function(data, index) {
                if (data.id === ps.generic_component.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.generic_components.splice(item, 1);
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
                url: '/api/v1/generic_components?page=' + that.page + that.filter_string + that.order_string + '&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    that.meta = data.meta;
                    that.generic_components = data.data;
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
                .listen('GenericComponentUpdated', (e) => {
                this.update(e);
        }).listen('GenericComponentDeleted', (e) => {
                this.delete(e);
        });

        this.set_filter();
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
