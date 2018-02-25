<template>
    <div>
        <div :class="wrapperClasses">
            <div class="timeline">
                <div class="timeline-event" v-for="entry in entries">
                    <template v-if="entry.data">
                        <div class="timeline-date">
                            <!-- @TODO: there has to be a better way to do this -->
                            <span v-show="entry.data.timestamps.created_diff.days > 0"
                                  class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="entry.data.timestamps.created">
                                    {{ $t('units.days_ago', {val: entry.data.timestamps.created_diff.days}) }}
                        </span>
                            <span v-show="entry.data.timestamps.created_diff.days < 1 &&
                                      entry.data.timestamps.created_diff.hours > 0"
                                  class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="entry.data.timestamps.created">
                                    {{ $t('units.hours_ago', {val: entry.data.timestamps.created_diff.hours}) }}
                        </span>
                            <span v-show="entry.data.timestamps.created_diff.days < 1 &&
                                      entry.data.timestamps.created_diff.hours < 1 &&
                                      entry.data.timestamps.created_diff.minutes > 1"
                                  class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="entry.data.timestamps.created">
                                    {{ $t('units.minutes_ago', {val: entry.data.timestamps.created_diff.minutes}) }}
                        </span>
                            <span v-show="entry.data.timestamps.created_diff.days < 1 &&
                                      entry.data.timestamps.created_diff.hours < 1 &&
                                      entry.data.timestamps.created_diff.minutes < 2"
                                  class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="entry.data.timestamps.created">
                                    {{ $t('units.just_now') }}
                        </span>
                        </div>
                        <div class="card timeline-content">
                            <div class="card-content">
                                <h5>{{ entry.data.title }}</h5>

                                <p v-html="entry.data.text"> </p>

                                <p v-show="files.filter(f => f.data.belongsTo_id === entry.data.id).length > 0" style="margin-top: 15px;">
                                <span v-for="(file, index) in files.filter(f => f.data.belongsTo_id === entry.data.id)" style="margin-right: 15px;">
                                    <i class="material-icons">{{ file.data.icon }}</i>
                                    <a :href="file.data.url">{{ file.data.display_name }}</a>
                                </span>
                                </p>
                            </div>

                            <div class="card-action">
                                <a v-bind:href="'/biography_entries/' + entry.data.id + '/edit'">{{ $t("buttons.edit") }}</a>
                            </div>
                        </div>
                        <div class="timeline-badge teal darken-2 white-text">
                            <i v-if="entry.data.category" class="material-icons tooltipped" data-position="top" data-delay="50"
                               v-bind:data-tooltip="entry.data.category.name">
                                {{ entry.data.category.icon }}
                            </i>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <pagination ref="pagination"
                    :source-filter="sourceFilter"
                    :enable-filters="false">
        </pagination>
    </div>
</template>

<script>
    import pagination from './mixins/pagination.vue';

    export default {
        data () {
            return {
                ids: [],
                file_ids: []
            }
        },

        props: {
            wrapperClasses: {
                type: String,
                default: '',
                required: false
            },
            belongsToType: {
                type: String,
                required: true
            },
            belongsToId: {
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
            pagination
        },

        computed: {
            entries () {
                let that = this;
                return this.$store.state.biography_entries.filter(function(e) {
                    return that.ids.includes(e.id) && e.data !== null
                }).sort(function (a, b) {
                    let c = a.data[that.$refs.pagination.order.field] > b.data[that.$refs.pagination.order.field];
                    if ( c && that.$refs.pagination.order.direction === 'asc' ||
                        !c && that.$refs.pagination.order.direction === 'desc') {
                        return 1;
                    }
                    return -1;
                });
            },

            files () {
                let that = this;
                return this.$store.state.files.filter(function (f) {
                    return that.file_ids.includes(f.id) && f.data !== null
                });
            }
        },

        methods: {
            load_data: function() {
                let that = this;

                $.ajax({
                    url: '/api/v1/biography_entries/?with[]=files&' +
                         'filter[belongsTo_type]=' + that.belongsToType + '&' +
                         'filter[belongsTo_id]=' + that.belongsToId + '&' +
                         'pagination[per_page]=' + that.itemsPerPage + '&page=' +
                         that.$refs.pagination.page +
                         that.$refs.pagination.filter_string +
                         that.$refs.pagination.order_string,
                    method: 'GET',
                    success: function (data) {
                        that.ids = data.data.map(c => c.id);
                        that.file_ids = [].concat.apply([], data.data.map(f => f.files.map(f => f.id)));

                        that.$refs.pagination.meta = data.meta;

                        that.$parent.ensureObjects('biography_entries', that.ids, data.data);
                        that.$parent.ensureObjects('files', that.file_ids, [].concat.apply([], data.data.map(f => f.files)));
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
                that.$refs.pagination.order.field = 'created_at';
                that.$refs.pagination.order.direction = 'desc';
                that.$refs.pagination.init();
            }, 100);
        }
    }
</script>