<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight collapsible" data-collapsible="expandable">
                <table-filter ref="table_filter"
                              :cols="3"
                              :hide-cols="hideCols"
                              :filter-fields="[{name: 'name', path: 'name', col: 0},
                                               {name: 'controlunit', path: 'controlunit.name', col: 1, class: 'hide-on-small-only'},
                                               {noSort: true, noFilter: true, col: 2, class: 'hide-on-small-only'}]">
                </table-filter>

                <template v-for="type in component_types">
                    <template v-if="(component_list = _self[type]).length > 0">
                        <template v-for="component in component_list">
                            <tbody v-if="component.data">
                                <tr class="collapsible-header">
                                    <td>
                                        <span>
                                            <i :class="'mdi mdi-18px mdi-' + component.data.icon"></i>
                                            <a v-bind:href="component.data.url">{{ component.data.name }}</a>
                                        </span>
                                    </td>

                                    <td class="hide-on-small-only" v-if="hideCols.indexOf('controlunit') === -1">
                                        <span v-if="component.data.controlunit">
                                            <i class="mdi mdi-24px mdi-developer-board"></i>
                                            <a v-bind:href="'/controlunits/' + component.data.controlunit.id">{{ component.data.controlunit.name }}</a>
                                        </span>
                                    </td>

                                    <td class="hide-on-small-only">
                                        <span>
                                            <a v-bind:href="component.data.url + '/edit'">
                                                <i class="mdi mdi-24px mdi-pencil"></i>
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
                    </template>
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
                pump_ids: [],
                valve_ids: [],
                generic_component_ids: [],
                physical_sensor_ids: [],
                controlunit_ids: [],
                component_types: ['pumps', 'valves', 'generic_components', 'physical_sensors']
            }
        },

        props: {
            wrapperClasses: {
                type: String,
                default: '',
                required: false
            },
            sourceApiUrl: {
                type: String,
                required: true
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
            }
        },

        components: {
            pagination,
            'table-filter': table_filter
        },

        computed: {
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

            controlunits () {
                let that = this;
                return this.$store.state.controlunits.filter(function(c) {
                    return that.controlunit_ids.includes(c.id) && c.data !== null
                });
            },
        },

        methods: {
            load_data: function() {
                let that = this;

                $.ajax({
                    url: '/api/v1/' + that.sourceApiUrl + '?' +
                         that.$refs.pagination.filter_string +
                         that.$refs.pagination.order_string,
                    method: 'GET',
                    success: function (data) {
                        that.pump_ids = data.data.filter(c => c.class === 'Pump').map(c => c.id);
                        that.valve_ids = data.data.filter(c => c.class === 'Valve').map(c => c.id);
                        that.generic_component_ids = data.data.filter(c => c.class === 'GenericComponent').map(c => c.id);
                        that.physical_sensor_ids = data.data.filter(c => c.class === 'PhysicalSensor').map(c => c.id);
                        that.controlunit_ids = data.data.map(c => c.controlunit_id);

                        that.$parent.ensureObjects('pumps', that.pump_ids, data.data.filter(c => c.class === 'Pump'));
                        that.$parent.ensureObjects('valves', that.valve_ids, data.data.filter(c => c.class === 'Valve'));
                        that.$parent.ensureObjects('generic_components', that.generic_component_ids, data.data.filter(c => c.class === 'GenericComponent'));
                        that.$parent.ensureObjects('physical_sensors', that.physical_sensor_ids, data.data.filter(c => c.class === 'PhysicalSensor'));
                        that.$parent.ensureObjects('controlunits', that.controlunit_ids, data.data.map(c => c.controlunit));
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