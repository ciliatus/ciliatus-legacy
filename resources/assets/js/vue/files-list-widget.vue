<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight collapsible" data-collapsible="expandable">
                <table-filter ref="table_filter"
                              :cols="4"
                              :hide-cols="hideCols"
                              :filter-fields="[{name: 'display_name', path: 'display_name', col: 0},
                                               {name: 'type', path: 'mimetype', col: 1},
                                               {name: 'size', path: 'size', noFilter: true, col: 2},
                                               {name: 'created_at', path: 'created_at', noFilter: true, col: 3},
                                               {noSort: true, noFilter: true, col: 4, class: 'hide-on-small-only'}]">
                </table-filter>

                <template v-for="file in files">
                    <tbody>
                        <tr class="collapsible-tr-header" onclick="window.collapseTr($(this))">
                            <td>
                                <span v-if="showOptionSelect">
                                    <input name="file" type="radio" :id="file.data.id" :value="file.data.id">
                                    <label :for="file.data.id"> </label>
                                </span>
                                <span>
                                    <i :class="'mdi mdi-24px mdi-' + file.data.icon"></i>
                                    <a v-bind:href="'/files/' + file.data.id">{{ file.data.display_name }}</a>
                                </span>
                            </td>

                            <td>
                                <span>
                                    {{ file.data.mimetype }}
                                </span>
                            </td>

                            <td>
                                <span v-if="file.data.size / 1024 > 1024">
                                    {{ Math.round(file.data.size / 1024 / 1024, 1) }} MB
                                </span>
                                <span v-else>
                                    {{ Math.round(file.data.size / 1024, 0) }} KB
                                </span>
                            </td>

                            <td>
                                {{ $t(
                                    'units.' + $getMatchingTimeDiff(file.data.timestamps.created_diff).unit,
                                    {val: $getMatchingTimeDiff(file.data.timestamps.created_diff).val}
                                )}}
                            </td>

                            <td class="hide-on-small-only">
                                <span v-if="backgroundSelectorClassName && backgroundSelectorId && file.data.mimetype.indexOf('image') !== -1">
                                    <a v-bind:href="'/files/set-background/' + backgroundSelectorClassName + '/' + backgroundSelectorId + '/' + file.data.id">
                                        <i class="mdi mdi-24px mdi-image tooltipped"
                                           data-delay="50" data-html="true"
                                           :data-tooltip="'<div style=\'max-width: 300px\'>' + $t('tooltips.set_as_background') + '</div>'"></i>
                                    </a>
                                </span>
                                <span>
                                    <a v-bind:href="'/files/' + file.data.id + '/edit'">
                                        <i class="mdi mdi-24px mdi-pencil"></i>
                                    </a>
                                </span>
                            </td>

                        </tr>
                        <tr class="collapsible-tr-body">
                            <td v-if="showOptionSelect">
                            </td>
                            <td colspan="4">
                                <img v-if="file.data.mimetype.startsWith('image') && file.data.thumb != undefined" :src="file.data.thumb.path_external">
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
                ids: []
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
            },
            showOptionSelect: {
                type: Boolean,
                default: false,
                required: false
            },
            backgroundSelectorClassName: {
                type: String,
                default: null,
                required: false
            },
            backgroundSelectorId: {
                type: String,
                default: null,
                required: false
            }
        },

        computed: {
            files () {
                let that = this;
                return this.$store.state.files.filter(function (f) {
                    return that.ids.includes(f.id) && f.data !== null
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

        components: {
            pagination,
            'table-filter': table_filter
        },

        methods: {
            load_data: function () {
                let that = this;

                $.ajax({
                    url: '/api/v1/files?' +
                         that.sourceFilter + '&' +
                         'filter[usage]=ne:thumb:or:null&' +
                         'pagination[per_page]=' + that.itemsPerPage + '&page=' +
                         that.$refs.pagination.page +
                         that.$refs.pagination.filter_string +
                         that.$refs.pagination.order_string,
                    method: 'GET',
                    success: function (data) {
                        that.ids = data.data.map(g => g.id);

                        that.$refs.pagination.meta = data.meta;

                        that.$parent.ensureObjects('files', that.ids, data.data);
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            },

            unsubscribe_all () {
                this.files.forEach((f) => f.unsubscribe());
            }
        },

        created: function () {
            let that = this;
            setTimeout(function () {
                that.$refs.pagination.init();
            }, 100);
        }
    }
</script>
