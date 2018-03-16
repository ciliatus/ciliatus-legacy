<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight collapsible" data-collapsible="expandable">
                <table-filter ref="table_filter"
                              :cols="5"
                              :hide-cols="hideCols"
                              :filter-fields="[{name: 'name', path: 'name', col: 0},
                                               {name: 'physical_sensor', noSort:true, path: 'physical_sensor.name', col: 1},
                                               {name: 'terrarium', noSort:true, path: 'terrarium.name', col: 2, class: 'hide-on-med-and-down'},
                                               {name: 'type', path: 'type', col: 3, class: 'hide-on-small-only'},
                                               {name: 'rawvalue', path: 'rawvalue', col: 4, class: 'hide-on-small-only'},
                                               {noSort: true, noFilter: true, col: 5, class: 'hide-on-small-only'}]">
                </table-filter>

                <template v-for="logical_sensor in logical_sensors">
                    <tbody>
                        <tr class="collapsible-header">

                            <td>
                                <span>
                                    <i class="mdi mdi-24px mdi-pulse"></i>
                                    <a v-bind:href="'/logical_sensors/' + logical_sensor.data.id">{{ logical_sensor.data.name }}</a>
                                </span>
                            </td>

                            <td v-if="hideCols.indexOf('physical_sensor') === -1">
                                <span v-if="ps = physical_sensor(logical_sensor)">
                                    <i class="mdi mdi-24px mdi-switch"></i>
                                    <a :href="'/physical_sensors/' + ps.data.id">
                                        {{ ps.data.name }}
                                    </a>
                                </span>
                            </td>

                            <td class="hide-on-med-and-down" v-if="hideCols.indexOf('terrarium') === -1">
                                <span v-if="t = terrarium(logical_sensor)">
                                    <i class="mdi mdi-24px mdi-trackpad"></i>
                                    <a :href="'/terraria/' + t.data.id">{{ t.data.display_name }}</a>
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                {{ logical_sensor.data.type }}
                            </td>

                            <td class="hide-on-small-only">
                                <span>{{ Math.round(logical_sensor.data.rawvalue, 2) }}</span>
                                <span v-if="logical_sensor.data.rawvalue_adjustment !== 0">
                                    <span v-if="logical_sensor.data.rawvalue_adjustment > 0">
                                        <span class="green-text darken-2">(+{{ logical_sensor.data.rawvalue_adjustment }})</span>
                                    </span>
                                    <span v-else>
                                        <span class="red darken-2">({{ logical_sensor.data.rawvalue_adjustment }})</span>
                                    </span>
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                <span>
                                    <a v-bind:href="'/logical_sensors/' + logical_sensor.data.id + '/edit'">
                                        <i class="mdi mdi-24px mdi-pencil"></i>
                                    </a>
                                </span>
                            </td>

                        </tr>
                        <tr class="collapsible-body">
                            <td>
                                {{ $t('labels.rawlimitlo') }}: {{ logical_sensor.data.rawvalue_lowerlimit }}<br />
                                {{ $t('labels.rawlimithi') }}: {{ logical_sensor.data.rawvalue_upperlimit }}
                            </td>
                            <td class="hide-on-small-only">
                                <span v-if="c = controlunit(logical_sensor)">
                                    {{ $tc('labels.controlunits', 1) }}:
                                    <i class="mdi mdi-24px mdi-developer-board"></i>
                                    <a v-bind:href="'/controlunits/' + c.data.id">{{ c.data.name }}</a>
                                </span>
                                <br />
                                <span>{{ $t('labels.model') }}: {{ physical_sensor(logical_sensor).data.model }}</span>
                            </td>
                            <td class="hide-on-med-and-down">
                                <span v-if="t = terrarium(logical_sensor)">
                                    {{ $tc('labels.terraria', 1) }} {{ $t('labels.temperature_celsius') }}:
                                    {{ t.data.cooked_temperature_celsius }}°C<br />

                                    {{ $tc('labels.terraria', 1) }} {{ $t('labels.humidity_percent') }}:
                                    {{ t.data.cooked_humidity_percent }}%
                                </span>
                            </td>
                            <td> </td>
                            <td  class="hide-on-small-only"> </td>
                            <td> </td>
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
                physical_sensor_ids: [],
                controlunit_ids: [],
                terraria_ids: []
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
            logical_sensors () {
                let that = this;
                return this.$store.state.logical_sensors.filter(function (l) {
                    return that.ids.includes(l.id) && l.data !== null
                }).sort(function (a, b) {
                    let c = a.data[that.$refs.pagination.order.field] > b.data[that.$refs.pagination.order.field];
                    if ( c && that.$refs.pagination.order.direction === 'asc' ||
                        !c && that.$refs.pagination.order.direction === 'desc') {
                        return 1;
                    }
                    return -1;
                });
            },

            physical_sensors () {
                let that = this;
                return this.$store.state.physical_sensors.filter(function (p) {
                    return that.physical_sensor_ids.includes(p.id) && p.data !== null
                });
            },

            terraria () {
                let that = this;
                return this.$store.state.terraria.filter(function (t) {
                    return that.terraria_ids.includes(t.id) && t.data !== null
                });
            },

            controlunits () {
                let that = this;
                return this.$store.state.controlunits.filter(function (c) {
                    return that.controlunit_ids.includes(c.id) && c.data !== null
                });
            }
        },

        components: {
            pagination,
            'table-filter': table_filter
        },

        methods: {
            terrarium(logical_sensor) {
                let terrarium = this.terraria.filter(
                    l => l.data.id === this.physical_sensors.filter(
                        p => p.data.id === logical_sensor.data.physical_sensor.id
                    )[0].data.terrarium.id
                );

                if (terrarium.length > 0) {
                    return terrarium[0];
                }

                return null;
            },

            controlunit(logical_sensor) {
                let controlunit = this.controlunits.filter(
                    l => l.data.id === this.physical_sensors.filter(
                        p => p.data.id === logical_sensor.data.physical_sensor.id
                    )[0].data.controlunit.id
                );

                if (controlunit.length > 0) {
                    return controlunit[0];
                }

                return null;
            },

            physical_sensor(logical_sensor) {
                let physical_sensor = this.physical_sensors.filter(
                    p => p.data.id === logical_sensor.data.physical_sensor.id
                );
                if (physical_sensor.length > 0) {
                    return physical_sensor[0];
                }

                return null;
            },

            load_data: function () {
                let that = this;

                $.ajax({
                    url: '/api/v1/logical_sensors/?with[]=physical_sensor&with[]=physical_sensor.terrarium&with[]=physical_sensor.controlunit&' +
                         that.sourceFilter + '&' +
                         'pagination[per_page]=' + that.itemsPerPage + '&page=' +
                         that.$refs.pagination.page +
                         that.$refs.pagination.filter_string +
                         that.$refs.pagination.order_string,
                    method: 'GET',
                    success: function (data) {
                        that.ids = data.data.map(l => l.id);
                        that.physical_sensor_ids = data.data.map(l => l.physical_sensor.id);
                        that.controlunit_ids = data.data.filter(l => l.physical_sensor.controlunit)
                                                        .map(l => l.physical_sensor)
                                                        .map(p => p.controlunit.id);
                        that.terraria_ids = data.data.filter(l => l.physical_sensor.terrarium)
                                                     .map(l => l.physical_sensor)
                                                     .map(p => p.terrarium.id);

                        that.$refs.pagination.meta = data.meta;

                        that.$parent.ensureObjects('logical_sensors', that.ids, data.data);
                        that.$parent.ensureObjects('physical_sensors', that.physical_sensor_ids, [].concat.apply([], data.data.map(p => p.physical_sensor)));
                        that.$parent.ensureObjects('controlunits', that.controlunit_ids, [].concat.apply([], that.physical_sensors.map(p => p.data.controlunit)));
                        that.$parent.ensureObjects('terraria', that.terraria_ids, [].concat.apply([], that.physical_sensors.map(p => p.data.terrarium)));
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            },

            unsubscribe_all () {
                this.terraria.forEach((t) => t.unsubscribe());
                this.logical_sensors.forEach((l) => l.unsubscribe());
                this.physical_sensors.forEach((p) => p.unsubscribe());
                this.controlunits.forEach((c) => c.unsubscribe());
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