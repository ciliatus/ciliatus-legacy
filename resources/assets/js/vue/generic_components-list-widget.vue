<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight collapsible" data-collapsible="expandable">
                <table-filter ref="table_filter"
                              :cols="4"
                              :hide-cols="hideCols"
                              :filter-fields="[{name: 'name', path: 'name', col: 0},
                                               {name: 'type', noSort: true, path: 'type.name_singular', col: 1, class: 'hide-on-small-only'},
                                               {name: 'controlunit', noSort: true, path: 'controlunit.name', col: 2, class: 'hide-on-small-only'},
                                               {noSort: true, noFilter: true, col: 3, class: 'hide-on-small-only'}]">
                </table-filter>

                <template v-for="generic_component in generic_components">
                    <tbody>
                        <tr class="collapsible-header">
                            <td>
                                <span>
                                    <i class="material-icons">{{ generic_component.data.type.icon }}</i>
                                    <a v-bind:href="'/generic_components/' + generic_component.data.id">{{ generic_component.data.name }}</a>
                                    <span v-if="!generic_component.data.active"> - {{ $t('labels.inactive') }}</span>
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                <span>
                                    <a v-bind:href="'/generic_component_types/' + generic_component.data.type.id">{{ generic_component.data.type.name_singular }}</a>
                                </span>
                            </td>

                            <td class="hide-on-small-only" v-if="hideCols.indexOf('controlunit') === -1">
                                <span v-if="generic_component.data.controlunit">
                                    <i class="material-icons">developer_board</i>
                                    <a v-bind:href="'/controlunits/' + generic_component.data.controlunit.id">{{ generic_component.data.controlunit.name }}</a>
                                </span>
                            </td>

                            <td class="hide-on-small-only">
                                <span>
                                    <a :href="'/generic_components/' + generic_component.data.id + '/edit'">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </span>
                            </td>

                        </tr>
                        <tr class="collapsible-body">
                            <td colspan="4">
                                <div v-for="(prop, index) in generic_component.data.component_properties">
                                    {{ prop.name }}: {{ prop.value }}
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
        data() {
            return {
                ids: [],
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
            generic_components () {
                let that = this;
                return this.$store.state.generic_components.filter(function (g) {
                    return that.ids.includes(g.id) && g.data !== null
                }).sort(function (a, b) {
                    let c = a.data[that.$refs.pagination.order.field] > b.data[that.$refs.pagination.order.field];
                    if ( c && that.$refs.pagination.order.direction === 'asc' ||
                        !c && that.$refs.pagination.order.direction === 'desc') {
                        return 1;
                    }
                    return -1;
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
            load_data: function () {
                let that = this;

                $.ajax({
                    url: '/api/v1/generic_components/?with[]=component_properties&with[]=states&with[]=type&with[]=controlunit&' +
                         that.sourceFilter + '&' +
                         'pagination[per_page]=' + that.itemsPerPage + '&page=' +
                         that.$refs.pagination.page +
                         that.$refs.pagination.filter_string +
                         that.$refs.pagination.order_string,
                    method: 'GET',
                    success: function (data) {
                        that.ids = data.data.map(g => g.id);
                        that.controlunit_ids = data.data.map(g => g.controlunit_id);

                        that.$refs.pagination.meta = data.meta;

                        that.$parent.ensureObjects('generic_components', that.ids, data.data, ['component_properties', 'states', 'type']);
                        that.$parent.ensureObjects('controlunits', that.controlunit_ids, data.data.map(g => g.controlunit));
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