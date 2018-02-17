<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight collapsible" data-collapsible="expandable">
                <table-filter ref="table_filter"
                              :cols="6"
                              :hide-cols="hideCols"
                              :filter-fields="[{name: 'name', path: 'name', col: 0},
                                               {name: 'model', path: 'model', col: 1},
                                               {name: 'pump', noFilter: true, col: 2},
                                               {name: 'controlunit', noSort: true, col: 3, class: 'hide-on-small-only'},
                                               {name: 'terrarium', noSort: true, col: 4, class: 'hide-on-med-and-down'},
                                               {name: '', noFilter: true, noSort:true, col: 5, class: 'hide-on-small-only'}]">
                </table-filter>

                <template v-for="valve in valves">
                    <tbody>
                        <tr class="collapsible-header">

                            <td>
                                <span>
                                    <i class="material-icons">transform</i>
                                    <a v-bind:href="'/valves/' + valve.data.id">{{ valve.data.name }}</a>
                                    <span v-if="!valve.data.active"> - {{ $t('labels.inactive') }}</span>
                                </span>
                            </td>

                            <td>
                                <span>
                                    {{ valve.data.model }}
                                </span>
                            </td>

                            <td v-if="hideCols.indexOf('pump') === -1">
                                <span v-if="(pump = pumps.filter(p => p.data.id === valve.data.pump_id)).length > 0">
                                    <i class="material-icons">rotate_right</i>
                                    <a :href="'/pumps/' + pump[0].data.id">{{ pump[0].data.name }}</a>
                                </span>
                            </td>

                            <td class="hide-on-small-only" v-if="hideCols.indexOf('controlunit') === -1">
                                <span v-if="(controlunit = controlunits.filter(c => c.data.id === valve.data.controlunit_id)).length > 0">
                                    <i class="material-icons">developer_board</i>
                                    <a :href="'/controlunits/' + controlunit[0].data.id">{{ controlunit[0].data.name }}</a>
                                </span>
                            </td>

                            <td class="hide-on-med-and-down" v-if="hideCols.indexOf('terrarium') === -1">
                                <span v-if="(terrarium = terraria.filter(t => t.data.id === valve.data.terrarium_id)).length > 0">
                                    <i class="material-icons">video_label</i>
                                    <a :href="'/terraria/' + terrarium[0].data.id">{{ terrarium[0].data.display_name }}</a>
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                <span>
                                    <a v-bind:href="'/valves/' + valve.data.id + '/edit'">
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
            terraria_ids: [],
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
            default: function(){return [];},
            required: false
        },
        itemsPerPage: {
            type: Number,
            default: 9,
            required: false
        }
    },

    computed: {
        valves () {
            let that = this;
            return this.$store.state.valves.filter(function(v) {
                return that.ids.includes(v.id) && v.data !== null
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

        terraria () {
            let that = this;
            return this.$store.state.terraria.filter(function(t) {
                return that.terraria_ids.includes(t.id) && t.data !== null
            });
        },

        controlunits () {
            let that = this;
            return this.$store.state.controlunits.filter(function(c) {
                return that.controlunit_ids.includes(c.id) && c.data !== null
            });
        },
    },

    components: {
        pagination,
        'table-filter': table_filter
    },

    methods: {
        load_data: function() {
            let that = this;

            $.ajax({
                url: '/api/v1/valves/?with[]=pump&with[]=terrarium&with[]=controlunit&' +
                     'pagination[per_page]=' + that.itemsPerPage + '&page=' +
                     that.$refs.pagination.page +
                     that.$refs.pagination.filter_string +
                     that.$refs.pagination.order_string,
                method: 'GET',
                success: function (data) {
                    that.ids = data.data.map(v => v.id);
                    that.pump_ids = data.data.map(v => v.pump_id);
                    that.terraria_ids = data.data.map(v => v.terrarium_id);
                    that.controlunit_ids = data.data.map(v => v.controlunit_id);

                    that.$refs.pagination.meta = data.meta;

                    that.$parent.ensureObjects('valves', that.ids, data.data);
                    that.$parent.ensureObjects('pumps', that.pump_ids, data.data.map(v => v.pump));
                    that.$parent.ensureObjects('terraria', that.terraria_ids, data.data.map(v => v.terrarium));
                    that.$parent.ensureObjects('controlunits', that.controlunit_ids, data.data.map(v => v.controlunit));
                },
                error: function (error) {
                    console.log(JSON.stringify(error));
                }
            });
        }
    },

    created: function() {
        let that = this;
        setTimeout(function() {
            that.$refs.pagination.set_filter();
        }, 100);
    }
}
</script>
