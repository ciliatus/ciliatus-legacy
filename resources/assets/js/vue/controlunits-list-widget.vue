<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight collapsible" data-collapsible="expandable">
                <table-filter ref="table_filter"
                              :cols="5"
                              :hide-cols="hideCols"
                              :filter-fields="[{name: 'name', path: 'name', col: 0},
                                               {name: 'software_version', path: 'software_version', col: 1, class: 'hide-on-small-only'},
                                               {name: 'heartbeat', path: 'heartbeat_at', noFilter: true, col: 2},
                                               {name: 'client_server_time_diff', noSort: true, noFilter: true, col: 3, class: 'hide-on-med-and-down'},
                                               {noSort: true, noFilter: true, col: 4, class: 'hide-on-small-only'}]">
                </table-filter>

                <template v-for="controlunit in controlunits">
                    <tbody>
                        <tr class="collapsible-header">
                            <td>
                                <span>
                                    <i class="mdi mdi-24px mdi-developer-board"></i>
                                    <a v-bind:href="'/controlunits/' + controlunit.data.id">{{ controlunit.data.name }}</a>
                                    <span v-if="!controlunit.data.active"> - {{ $t('labels.inactive') }}</span>
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                <span>
                                    {{ controlunit.data.software_version }}
                                </span>
                            </td>

                            <td>
                                {{ $t(
                                'units.' + $getMatchingTimeDiff(controlunit.data.timestamps.last_heartbeat_diff).unit,
                                {val: $getMatchingTimeDiff(controlunit.data.timestamps.last_heartbeat_diff).val}
                                )}}
                            </td>

                            <td class="hide-on-med-and-down">
                                <span>
                                    {{ controlunit.data.client_server_time_diff_seconds }}s
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                <span>
                                    <a v-bind:href="'/controlunits/' + controlunit.data.id + '/edit'">
                                        <i class="mdi mdi-24px mdi-pencil"></i>
                                    </a>
                                </span>
                            </td>

                        </tr>
                        <tr class="collapsible-body">
                            <td colspan="5">
                                <div v-if="controlunit.data" v-for="type in component_types">
                                    <template v-if="(component_list = _self[type].filter(c => c.data.controlunit_id === controlunit.id)).length > 0">
                                        {{ $tc('labels.' + type, 2) }}:
                                        <span v-for="component in component_list">
                                            <i :class="'mdi mdi-18px mdi-' + component.data.icon"></i>
                                            <a :href="component.data.url">{{ component.data.name }}</a>
                                        </span>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </template>
            </table>

            <pagination ref="pagination"
                        :source-filter="sourceFilter"
                        :enable-filters="false">
            </pagination>
        </div>
    </div>
</template>

<script>
    import pagination from './mixins/pagination.vue';
    import table_filter from './mixins/table_filter.vue';

    export default {
        data () {
            return {
                ids: [],
                pump_ids: [],
                valve_ids: [],
                generic_component_ids: [],
                physical_sensor_ids: [],
                component_types: ['pumps', 'valves', 'generic_components', 'physical_sensors']
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
            hideCols: {
                type: Array,
                default: function(){return [];},
                required: false
            },
            itemsPerPage: {
                type: Number,
                default: 9,
                required: false
            }
        },

        components: {
            pagination,
            'table-filter': table_filter
        },

        computed: {
            controlunits () {
                let that = this;
                return this.$store.state.controlunits.filter(function(c) {
                    return that.ids.includes(c.id) && c.data !== null
                }).sort(function (a, b) {
                    let c = a.data[that.$refs.pagination.order.field] > b.data[that.$refs.pagination.order.field];
                    if ( c && that.$refs.pagination.order.direction === 'asc' ||
                        !c && that.$refs.pagination.order.direction === 'desc') {
                        return 1;
                    }
                    return -1;
                });
            },

            pumps () {
                let that = this;
                return this.$store.state.pumps.filter(function(p) {
                    return that.pump_ids.includes(p.id) && p.data !== null
                });
            },

            valves () {
                let that = this;
                return this.$store.state.valves.filter(function(v) {
                    return that.valve_ids.includes(v.id) && v.data !== null
                });
            },

            generic_components () {
                let that = this;
                return this.$store.state.generic_components.filter(function(g) {
                    return that.generic_component_ids.includes(g.id) && g.data !== null
                });
            },

            physical_sensors () {
                let that = this;
                return this.$store.state.physical_sensors.filter(function(p) {
                    return that.physical_sensor_ids.includes(p.id) && p.data !== null
                });
            },
        },

        methods: {
            load_data: function() {
                let that = this;

                $.ajax({
                    url: '/api/v1/controlunits/?' +
                         'with[]=physical_sensors&with[]=valves&with[]=pumps&with[]=generic_components&with[]=generic_components.type&' +
                         that.sourceFilter + '&' +
                         'pagination[per_page]=' + that.itemsPerPage + '&page=' +
                         that.$refs.pagination.page +
                         that.$refs.pagination.filter_string +
                         that.$refs.pagination.order_string,
                    method: 'GET',
                    success: function (data) {
                        that.ids = data.data.map(c => c.id);
                        that.pump_ids = [].concat.apply([], data.data.map(c => c.pumps.map(p => p.id)));
                        that.valve_ids = [].concat.apply([], data.data.map(c => c.valves.map(v => v.id)));
                        that.generic_component_ids = [].concat.apply([], data.data.map(c => c.generic_components.map(g => g.id)));
                        that.physical_sensors_ids = [].concat.apply([], data.data.map(c => c.physical_sensors.map(p => p.id)));

                        that.$refs.pagination.meta = data.meta;

                        that.$parent.ensureObjects('controlunits', that.ids, data.data, ['physical_sensors', 'valves', 'pumps', 'generic_components']);
                        that.$parent.ensureObjects('pumps', that.pump_ids, [].concat.apply([], data.data.map(c => c.pumps)));
                        that.$parent.ensureObjects('valves', that.valve_ids, [].concat.apply([], data.data.map(c => c.valves)));
                        that.$parent.ensureObjects('generic_components', that.generic_component_ids, [].concat.apply([], data.data.map(c => c.generic_components)));
                        that.$parent.ensureObjects('physical_sensors', that.physical_sensors_ids, [].concat.apply([], data.data.map(c => c.physical_sensors)));
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            },

            unsubscribe_all () {
                this.pumps.forEach((p) => p.unsubscribe());
                this.valves.forEach((v) => v.unsubscribe());
                this.generic_components.forEach((g) => g.unsubscribe());
                this.physical_sensors.forEach((p) => p.unsubscribe());
                this.controlunits.forEach((c) => c.unsubscribe());
            }
        },

        created: function() {
            let that = this;
            setTimeout(function() {
                that.$refs.pagination.init('name');
            }, 100);
        }
    }
</script>