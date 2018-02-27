<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight collapsible" data-collapsible="expandable">
                <table-filter ref="table_filter"
                              :cols="4"
                              :hide-cols="hideCols"
                              :filter-fields="[{name: 'id', path: 'id', col: 0, class: 'hide-on-med-and-down'},
                                               {name: 'name', path: 'name', col: 1},
                                               {name: 'email', noFilter: true, col: 2},
                                               {noSort: true, noFilter: true, col: 3, class: 'hide-on-small-only'}]">
                </table-filter>

                <tbody>
                    <tr v-for="user in users">
                        <td class="hide-on-med-and-down">{{ user.data.id }}</td>
                        <td>{{ user.data.name }}</td>
                        <td>{{ user.data.email }}</td>
                        <td class="hide-on-small-only">
                            <span>
                                <a :href="'/users/' + user.data.id + '/edit'">
                                    <i class="material-icons">edit</i>
                                </a>
                            </span>
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
            users () {
                let that = this;
                return this.$store.state.users.filter(function(u) {
                    return that.ids.includes(u.id) && u.data !== null
                });
            }
        },

        components: {
            pagination,
            'table-filter': table_filter
        },

        methods: {
            load_data: function() {
                let that = this;

                $.ajax({
                    url: '/api/v1/users/?pagination[per_page]=' + that.itemsPerPage + '&page=' +
                         that.$refs.pagination.page +
                         that.$refs.pagination.filter_string +
                         that.$refs.pagination.order_string,
                    method: 'GET',
                    success: function (data) {
                        that.ids = data.data.map(u => u.id);

                        that.$refs.pagination.meta = data.meta;

                        that.$parent.ensureObjects('users', that.ids, data.data);
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
                that.$refs.pagination.init('name');
            }, 100);
        }
    }
</script>