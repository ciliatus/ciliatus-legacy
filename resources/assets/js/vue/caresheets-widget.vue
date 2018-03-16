<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight collapsible" data-collapsible="expandable">
                <table-filter ref="table_filter"
                              :cols="2"
                              :hide-cols="hideCols"
                              :filter-fields="[{name: 'name', noFilter: true, path: 'name', col: 0},
                                               {name: 'created_at', noFilter: true, path: 'created_at', col: 1}]">
                </table-filter>


                <tbody>
                    <tr v-for="caresheet in caresheets">
                        <template v-if="caresheet.data">
                            <td>
                                <span><a :href="'/animals/' + animalId + '/caresheets/' + caresheet.data.id">
                                    {{ caresheet.data.title }}
                                </a></span>
                            </td>
                            <td>
                                <span>{{ caresheet.data.timestamps.created }}</span>
                            </td>
                        </template>
                        <template v-else>
                            <td colspan="2">{{ $t('labels.loading') }}</td>
                        </template>
                    </tr>
                </tbody>

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
                caresheet_ids: []
            }
        },

        props: {
            wrapperClasses: {
                type: String,
                default: '',
                required: false
            },
            animalId: {
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
            },
            itemsPerPage: {
                type: Number,
                default: 10,
                required: false
            }
        },

        components: {
            pagination,
            'table-filter': table_filter
        },

        computed: {
            caresheets () {
                let that = this;
                return this.$store.state.caresheets.filter(function(c) {
                    return that.caresheet_ids.includes(c.id) && c.data !== null
                }).sort(function (a, b) {
                    let c = a.data[that.$refs.pagination.order.field] > b.data[that.$refs.pagination.order.field];
                    if ( c && that.$refs.pagination.order.direction === 'asc' ||
                        !c && that.$refs.pagination.order.direction === 'desc') {
                        return 1;
                    }
                    return -1;
                });
            }
        },

        methods: {
            load_data: function() {
                let that = this;

                $.ajax({
                    url: '/api/v1/animals/' + that.animalId + '/caresheets/?' +
                         'pagination[per_page]=' + that.itemsPerPage + '&page=' +
                         that.$refs.pagination.page +
                         that.$refs.pagination.filter_string +
                         that.$refs.pagination.order_string,
                    method: 'GET',
                    success: function (data) {
                        that.caresheet_ids = data.data.map(c => c.id);

                        that.$refs.pagination.meta = data.meta;

                        that.$parent.ensureObjects('caresheets', that.caresheet_ids, data.data);
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            },

            unsubscribe_all () {
                this.caresheets.forEach((c) => c.unsubscribe());
            }
        },

        created: function() {
            let that = this;
            setTimeout(function() {
                that.$refs.pagination.order.field = 'created_at';
                that.$refs.pagination.order.direction = 'desc';
                that.$refs.pagination.init();
            }, 100);
        }
    }
</script>
