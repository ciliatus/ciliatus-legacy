<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight collapsible" data-collapsible="expandable">
                <table-filter ref="table_filter"
                              :cols="4"
                              :hide-cols="hideCols"
                              :filter-fields="[{name: 'name', path: 'name', col: 0},
                                               {name: 'model', path: 'model', col: 1},
                                               {name: 'controlunit', noSort:true, path: 'controlunit.name', col: 2},
                                               {noSort: true, noFilter: true, col: 3, class: 'hide-on-small-only'}]">
                </table-filter>

                <template v-for="pump in pumps">
                    <tbody>
                        <tr class="collapsible-header">
                            <td>
                                <span>
                                    <i class="material-icons">rotate_right</i>
                                    <a v-bind:href="'/pumps/' + pump.data.id">{{ pump.data.name }}</a>
                                    <span v-if="!pump.data.active"> - {{ $t('labels.inactive') }}</span>
                                </span>
                            </td>

                            <td>
                                <span>
                                    {{ pump.data.model }}
                                </span>
                            </td>

                            <td v-if="hideCols.indexOf('controlunit') === -1">
                                <span v-if="(controlunit = controlunits.filter(c => c.data.id === pump.data.controlunit_id)).length > 0">
                                    <i class="material-icons">developer_board</i>
                                    <a v-bind:href="'/controlunits/' + controlunit[0].data.id">{{ controlunit[0].data.name }}</a>
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                <span>
                                    <a v-bind:href="'/pumps/' + pump.data.id + '/edit'">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </span>
                            </td>
                        </tr>

                        <tr class="collapsible-body">
                            <td colspan="3">
                                {{ $tc('labels.valves', 2) }}:
                                <span v-for="(valve, index) in valves.filter(v => v.data.pump_id === pump.data.id)">
                                        <i class="material-icons">transform</i>
                                        <a v-bind:href="'/valves/' + valve.data.id">{{ valve.data.name }}</a>
                                        <template v-if="index < valves.filter(v => v.data.pump_id === pump.data.id).length-1">, </template>
                                    </span>
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
        data() {
            return {
                ids: [],
                valve_ids: [],
                controlunit_ids: []
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
            pumps () {
                let that = this;
                return this.$store.state.pumps.filter(function (p) {
                    return that.ids.includes(p.id) && p.data !== null
                }).sort(function (a, b) {
                    let c = a.data[that.$refs.pagination.order.field] > b.data[that.$refs.pagination.order.field];
                    if ( c && that.$refs.pagination.order.direction === 'asc' ||
                        !c && that.$refs.pagination.order.direction === 'desc') {
                        return 1;
                    }
                    return -1;
                });;
            },

            valves () {
                let that = this;
                return this.$store.state.valves.filter(function (v) {
                    return that.valve_ids.includes(v.id) && v.data !== null
                });
            },

            controlunits () {
                let that = this;
                return this.$store.state.controlunits.filter(function (c) {
                    return that.controlunit_ids.includes(c.id) && c.data !== null
                });
            },
        },

        components: {
            pagination,
            'table-filter': table_filter
        },

        methods: {
            load_data: function () {
                let that = this;

                $.ajax({
                    url: '/api/v1/pumps/?with[]=valves&with[]=controlunit&' +
                         'pagination[per_page]=' + that.itemsPerPage + '&page=' +
                         that.$refs.pagination.page +
                         that.$refs.pagination.filter_string +
                         that.$refs.pagination.order_string,
                    method: 'GET',
                    success: function (data) {
                        that.ids = data.data.map(p => p.id);
                        that.controlunit_ids = data.data.map(p => p.controlunit_id);
                        that.valve_ids = [].concat.apply([], data.data.map(p => p.valves.map(v => v.id)));

                        that.$refs.pagination.meta = data.meta;

                        that.$parent.ensureObjects('pumps', that.ids, data.data);
                        that.$parent.ensureObjects('controlunits', that.controlunit_ids, data.data.map(p => p.controlunit));
                        that.$parent.ensureObjects('valves', that.valve_ids, [].concat.apply([], data.data.map(p => p.valves)));
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
                that.$refs.pagination.set_filter();
            }, 100);
        }
    }
</script>