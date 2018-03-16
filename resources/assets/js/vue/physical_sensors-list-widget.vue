<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight collapsible" data-collapsible="expandable">
                <table-filter ref="table_filter"
                              :cols="5"
                              :hide-cols="hideCols"
                              :filter-fields="[{name: 'name', path: 'name', col: 0},
                                               {name: 'model', path: 'model', col: 1},
                                               {name: 'controlunit', noSort: true, path: 'controlunit.name', col: 2, class: 'hide-on-small-only'},
                                               {name: 'terrarium', noSort:true, path: 'terrarium.display_name', col: 3, class: 'hide-on-med-and-down'},
                                               {noSort: true, noFilter: true, col: 4, class: 'hide-on-small-only'}]">
                </table-filter>

                <template v-for="physical_sensor in physical_sensors">
                    <tbody>
                        <tr class="collapsible-header">

                            <td>
                                <span>
                                    <i class="mdi mdi-24px mdi-switch"></i>
                                    <a v-bind:href="'/physical_sensors/' + physical_sensor.data.id">{{ physical_sensor.data.name }}</a>
                                    <span v-if="!physical_sensor.data.active"> - {{ $t('labels.inactive') }}</span>
                                </span>
                            </td>

                            <td>
                                <span>
                                    {{ physical_sensor.data.model }}
                                </span>
                            </td>

                            <td class="hide-on-small-only" v-if="hideCols.indexOf('controlunit') === -1">
                                <span v-if="(controlunit = controlunits.filter(c => c.data.id === physical_sensor.data.controlunit_id)).length > 0">
                                    <i class="mdi mdi-24px mdi-developer-board"></i>
                                    <a v-bind:href="'/controlunits/' + controlunit[0].data.id">{{ controlunit[0].data.name }}</a>
                                </span>
                            </td>

                            <td class="hide-on-med-and-down" v-if="hideCols.indexOf('terrarium') === -1">
                                <span v-if="physical_sensor.data.terrarium &&
                                            (terrarium = terraria.filter(t => t.data.id === physical_sensor.data.terrarium.id)).length > 0">
                                    <i class="mdi mdi-24px mdi-trackpad"></i>
                                    <a v-bind:href="'/terraria/' + terrarium[0].data.id">{{ terrarium[0].data.display_name }}</a>
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                <span>
                                    <a v-bind:href="'/physical_sensors/' + physical_sensor.data.id + '/edit'">
                                        <i class="mdi mdi-24px mdi-pencil"></i>
                                    </a>
                                </span>
                            </td>

                        </tr>
                        <tr class="collapsible-body">
                            <td colspan="4">
                                {{ $t('labels.last_heartbeat') }}:
                                {{ $t(
                                    'units.' + $getMatchingTimeDiff(physical_sensor.data.timestamps.last_heartbeat_diff).unit,
                                    {val: $getMatchingTimeDiff(physical_sensor.data.timestamps.last_heartbeat_diff).val}
                                )}}
                                <br />
                                {{ $tc('labels.logical_sensors', 2) }}:
                                <span v-for="(logical_sensor, index) in logical_sensors.filter(l => l.data.physical_sensor_id === physical_sensor.data.id)">
                                    <i class="mdi mdi-24px mdi-pulse"></i>
                                    <a v-bind:href="'/logical_sensors/' + logical_sensor.data.id">{{ logical_sensor.data.name }}</a>
                                    <template v-if="index < logical_sensors.filter(l => l.data.physical_sensor_id === physical_sensor.data.id).length-1">, </template>
                                </span>
                            </td>
                            <td class="hide-on-med-and-down"> </td>
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
        data() {
            return {
                ids: [],
                controlunit_ids: [],
                terraria_ids: [],
                logical_sensor_ids: []
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
                default: function () {
                    return [];
                },
                required: false
            },
            itemsPerPage: {
                type: Number,
                default: 9,
                required: false
            }
        },

        computed: {
            physical_sensors () {
                let that = this;
                return this.$store.state.physical_sensors.filter(function (p) {
                    return that.ids.includes(p.id) && p.data !== null
                }).sort(function (a, b) {
                    let c = a.data[that.$refs.pagination.order.field] > b.data[that.$refs.pagination.order.field];
                    if ( c && that.$refs.pagination.order.direction === 'asc' ||
                        !c && that.$refs.pagination.order.direction === 'desc') {
                        return 1;
                    }
                    return -1;
                });
            },

            logical_sensors () {
                let that = this;
                return this.$store.state.logical_sensors.filter(function (l) {
                    return that.logical_sensor_ids.includes(l.id) && l.data !== null
                });
            },

            controlunits () {
                let that = this;
                return this.$store.state.controlunits.filter(function (c) {
                    return that.controlunit_ids.includes(c.id) && c.data !== null
                });
            },

            terraria () {
                let that = this;
                return this.$store.state.terraria.filter(function (t) {
                    return that.terraria_ids.includes(t.id) && t.data !== null
                });
            }
        },

        components: {
            pagination,
            'table-filter': table_filter
        },

        methods: {
            load_data: function () {
                let that = this;

                $.ajax({
                    url: '/api/v1/physical_sensors/?with[]=terrarium&with[]=controlunit&with[]=logical_sensors&' +
                         that.sourceFilter + '&' +
                         'pagination[per_page]=' + that.itemsPerPage + '&page=' +
                         that.$refs.pagination.page +
                         that.$refs.pagination.filter_string +
                         that.$refs.pagination.order_string,
                    method: 'GET',
                    success: function (data) {
                        that.ids = data.data.map(p => p.id);
                        that.controlunit_ids = data.data.map(p => p.controlunit_id);
                        that.terraria_ids = data.data.map(p => p.terrarium ? p.terrarium.id : null);
                        that.logical_sensor_ids = [].concat.apply([], data.data.map(p => p.logical_sensors.map(l => l.id)));

                        that.$refs.pagination.meta = data.meta;

                        that.$parent.ensureObjects('physical_sensors', that.ids, data.data);
                        that.$parent.ensureObjects('logical_sensors', that.logical_sensor_ids, [].concat.apply([], data.data.map(p => p.logical_sensors)));
                        that.$parent.ensureObjects('controlunits', that.controlunit_ids, data.data.map(p => p.controlunit));
                        that.$parent.ensureObjects('terraria', that.terraria_ids, data.data.map(p => p.terrarium));
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            }
        },

        created: function () {
            let that = this;
            setTimeout(function () {
                that.$refs.pagination.init('name');
            }, 100);
        }
    }
</script>