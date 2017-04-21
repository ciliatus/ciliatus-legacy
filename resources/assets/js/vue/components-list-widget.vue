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

                <template v-for="component in components">
                    <tbody>
                        <tr class="collapsible-header">

                            <td>
                                <span>
                                    <i class="material-icons">{{ component.icon }}</i>
                                    <a v-bind:href="component.url">{{ component.name }}</a>
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                <span v-if="component.controlunit">
                                    <i class="material-icons">developer_board</i>
                                    <a v-bind:href="'/controlunits/' + component.controlunit.id">{{ component.controlunit.name }}</a>
                                </span>
                            </td>

                            <td>
                                <span>
                                    <a class="waves-effect waves-light btn-small" v-bind:href="component.url + '/edit'">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </span>
                            </td>

                        </tr>
                        <tr class="collapsible-body">
                            <td colspan="3">

                            </td>
                        </tr>
                    </tbody>
                </template>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            components: [],
            meta: [],
            filter: {
                name: '',
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
        sourceApiBaseUrl: {
            type: String,
            required: true
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
        update: function(e) {
            var item = null;
            var component = this.get_component_from_event(e);
            
            this.components.forEach(function(data, index) {
                if (data.id === component.id) {
                    item = index;
                }
            });
            if (item !== null) {
                this.components.splice(item, 1, component);
            }
        },

        delete: function(e) {
            var item = null;
            var component = this.get_component_from_event(e);

            this.components.forEach(function(data, index) {
                if (data.id === component.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.components.splice(item, 1);
            }
        },

        get_component_from_event: function(e) {
            var component_types = [
                'physical_sensor', 'logical_sensor', 'valve', 'pump', 'generic_component'
            ];

            var component = null;

            component_types.forEach(function(item) {
                if (e[item] !== undefined) {
                    component = e[item];
                    return;
                }
            });

            return component;
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
                url: '/api/v1/' + that.sourceApiBaseUrl + '?' + that.filter_string + that.order_string + '&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    that.meta = data.meta;
                    that.components = data.data;
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
            }).listen('PhysicalSensorDeleted', (e) => {
                this.delete(e);
            }).listen('LogicalSensorUpdated', (e) => {
                this.update(e);
            }).listen('LogicalSensorDeleted', (e) => {
                this.delete(e);
            }).listen('ValveUpdated', (e) => {
                this.update(e);
            }).listen('ValveDeleted', (e) => {
                this.delete(e);
            }).listen('PumpUpdated', (e) => {
                this.update(e);
            }).listen('PumpDeleted', (e) => {
                this.delete(e);
            }).listen('GenericComponentUpdated', (e) => {
                this.update(e);
            }).listen('GenericComponentDeleted', (e) => {
                this.delete(e);
        });

        this.set_filter();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function() {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000)
        }
    }
}
</script>
