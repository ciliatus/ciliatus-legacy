<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight">

                <table-filter ref="table_filter"
                              :cols="5"
                              :hide-cols="hideCols"
                              :filter-fields="[{name: 'source', path: 'name', col: 0},
                                               {name: 'action', path: 'action', col: 1},
                                               {name: 'target', path: 'target_name', col: 2},
                                               {name: 'associated_with', path: 'associatedWith_name', col: 3},
                                               {name: 'created_at', path: 'created_at', col: 4}]">
                </table-filter>

                <tbody>
                    <tr v-for="log in logs">
                        <td>
                            <span v-if="log.source != null">
                                <i class="material-icons" v-show="log.source">{{ log.source.icon }}</i>
                                <a v-bind:href="log.source.url">{{ log.source.name }}</a>
                            </span>
                            <span v-else>
                                {{ log.source_name }}
                            </span>
                        </td>

                        <td>
                            <span v-if="log.action == 'start'" class="material-icons">play_arrow</span>
                            <span v-if="log.action == 'finish'" class="material-icons">done</span>
                            <span v-if="log.action == 'create'" class="material-icons">add</span>
                            <span v-if="log.action == 'delete'" class="material-icons">delete</span>
                            <span v-if="log.action == 'update'" class="material-icons">update</span>
                            <span v-if="log.action == 'recover'" class="material-icons">settings_backup_restore</span>
                            <span v-if="log.action == 'notify_recovered'" class="material-icons">notifications_none</span>
                            <span v-if="log.action == 'notify'" class="material-icons">notifications_active</span>
                            {{ log.action }}
                        </td>

                        <td>
                            <span v-if="log.target != null">
                                <i class="material-icons" v-show="log.target">{{ log.target.icon }}</i>
                                <a v-bind:href="log.target.url">{{ log.target.name }}</a>
                            </span>
                            <span v-else>
                                {{ log.target_name }}
                            </span>
                        </td>

                        <td>
                            <span v-if="log.associated != null">
                                <i class="material-icons" v-show="log.associated">{{ log.associated.icon }}</i>
                                <a v-bind:href="log.associated.url">{{ log.associated.name }}</a>
                            </span>
                            <span v-else>
                                {{ log.associated_name }}
                            </span>
                        </td>

                        <td>
                            {{ log.timestamps.created }}
                        </td>
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
                logs: []
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

        components: {
            pagination,
            'table-filter': table_filter
        },

        methods: {
            load_data: function() {
                window.eventHubVue.processStarted();
                let that = this;
                $.ajax({
                    url: '/api/v1/logs/?' +
                         'pagination[per_page]=' + that.itemsPerPage + '&page=' +
                         that.$refs.pagination.page +
                         that.$refs.pagination.filter_string +
                         that.$refs.pagination.order_string,
                    method: 'GET',
                    success: function (data) {
                        that.logs = data.data;
                        that.$refs.pagination.meta = data.meta;
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
            let that = this;
            setTimeout(function() {
                that.$refs.pagination.init('created_at', 'desc');
            }, 100);
        }
    }
</script>
