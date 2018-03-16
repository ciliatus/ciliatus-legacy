<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight collapsible" data-collapsible="expandable">
                <table-filter ref="table_filter"
                              :cols="3"
                              :hide-cols="hideCols"
                              :filter-fields="[{name: 'name', path: 'name', col: 0},
                                               {name: 'terrarium', path: 'terrarium.display_name', noSort: true, col: 1},
                                               {noSort: true, noFilter: true, col: 2}]">
                </table-filter>

                <template v-for="sequence in sequences">
                    <tbody v-if="sequence.data">
                        <tr class="collapsible-header">

                            <td>
                                <span>
                                    <i class="mdi mdi-24px mdi-playlist-play"></i>
                                    <a v-bind:href="'/action_sequences/' + sequence.data.id + '/edit'">{{ sequence.data.name }}</a>
                                </span>
                            </td>

                            <td>
                                <span v-if="(terrarium = terraria.filter(t => t.id === sequence.data.terrarium.id)).length > 0">
                                    <template v-if="terrarium[0].data">
                                        <i class="mdi mdi-24px mdi-trackpad"></i>
                                        <a v-bind:href="'/terraria/' + terrarium[0].data.id">{{ terrarium[0].data.name }}</a>
                                    </template>
                                </span>
                            </td>

                            <td>
                                <span>
                                    <a v-bind:href="'/action_sequences/' + sequence.data.id + '/edit'">
                                        <i class="mdi mdi-24px mdi-pencil"></i>
                                    </a>
                                </span>
                            </td>

                        </tr>
                        <tr class="collapsible-body">
                            <td colspan="3" class="table-details-collapsible">
                                <ul v-if="(sequence_schedules = schedules.filter(s => s.data.action_sequence_id === sequence.data.id)).length > 0">
                                    <strong>{{ $tc('labels.action_sequence_schedules', 2) }}</strong>
                                    <li v-for="(schedule, index) in sequence_schedules">
                                        <template v-if="schedule.data">
                                            <i class="mdi mdi-18px mdi-clock"></i>
                                            <span>{{ schedule.data.timestamps.starts }}</span>
                                            <span v-if="schedule.data.timestamps.runonce"><i>{{ $t('labels.runonce') }}</i></span>
                                            <span v-else><i>{{ $t('labels.daily') }}</i></span>
                                        </template>
                                    </li>
                                </ul>

                                <ul v-if="(sequence_triggers = triggers.filter(t => t.data.action_sequence_id === sequence.data.id)).length > 0">
                                    <strong>{{ $tc('labels.action_sequence_triggers', 2) }}</strong>
                                    <li v-for="(trigger, index) in sequence_triggers">
                                        <template v-if="trigger.data">
                                            <i class="mdi mdi-18px mdi-vanish"></i>
                                            <span>{{ $t('labels.' + trigger.data.logical_sensor.type) }}</span>
                                            <span v-if="trigger.data.reference_value_comparison_type == 'greater'">&gt;</span>
                                            <span v-if="trigger.data.reference_value_comparison_type == 'lesser'">&lt;</span>
                                            <span>{{ trigger.data.reference_value }}</span>
                                            <span>
                                                {{ $t('labels.between') }} {{ trigger.data.timeframe.start }}
                                                {{ $t('labels.and') }} {{ trigger.data.timeframe.end }},
                                            </span>
                                            <span>
                                                {{ $t('labels.minimum_timeout') }}: {{ trigger.data.minimum_timeout_minutes }}
                                                {{ $tc('units.minutes', trigger.data.minimum_timeout_minutes) }}
                                            </span>
                                        </template>
                                    </li>
                                </ul>

                                <ul v-if="(sequence_intentions = intentions.filter(i => i.data.action_sequence_id === sequence.data.id)).length > 0">
                                    <strong>{{ $tc('labels.action_sequence_intentions', 2) }}</strong>
                                    <li v-for="(intention, index) in sequence_intentions">
                                        <template v-if="intention.data">
                                            <i class="mdi mdi-18px mdi-compass"></i>
                                            <span>{{ $t('labels.' + intention.data.intention) }}</span>
                                            <span>{{ $t('labels.' + intention.data.type) }}</span>
                                            <span>
                                                {{ $t('labels.between') }} {{ intention.data.timeframe.start }}
                                                {{ $t('labels.and') }} {{ intention.data.timeframe.end }},
                                            </span>
                                            <span>
                                                {{ $t('labels.minimum_timeout') }}: {{ intention.data.minimum_timeout_minutes }}
                                                {{ $tc('units.minutes', intention.data.minimum_timeout_minutes) }}
                                            </span>
                                        </template>
                                    </li>
                                </ul>
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
    data () {
        return {
            ids: [],
            intention_ids: [],
            schedule_ids: [],
            trigger_ids: [],
            terrarium_ids: []
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
            default: 10,
            required: false
        }
    },

    computed: {
        sequences() {
            let that = this;
            return this.$store.state.action_sequences.filter(function (s) {
                return that.ids.includes(s.id) && s.data !== null
            }).sort(function (a, b) {
                let c = a.data[that.$refs.pagination.order.field] > b.data[that.$refs.pagination.order.field];
                if (c && that.$refs.pagination.order.direction === 'asc' ||
                    !c && that.$refs.pagination.order.direction === 'desc') {
                    return 1;
                }
                return -1;
            });
        },

        intentions() {
            let that = this;
            return this.$store.state.action_sequence_intentions.filter(function (i) {
                return that.intention_ids.includes(i.id) && i.data !== null
            });
        },

        schedules() {
            let that = this;
            return this.$store.state.action_sequence_schedules.filter(function (s) {
                return that.schedule_ids.includes(s.id) && s.data !== null
            });
        },

        triggers() {
            let that = this;
            return this.$store.state.action_sequence_triggers.filter(function (t) {
                return that.trigger_ids.includes(t.id) && t.data !== null
            });
        },

        terraria() {
            let that = this;
            return this.$store.state.terraria.filter(function (t) {
                return that.terrarium_ids.includes(t.id) && t.data !== null
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
                url: '/api/v1/action_sequences/?with[]=intentions&with[]=schedules&with[]=triggers&with[]=terrarium&' +
                     that.sourceFilter + '&' +
                     'pagination[per_page]=' + that.itemsPerPage + '&page=' +
                     that.$refs.pagination.page +
                     that.$refs.pagination.filter_string +
                     that.$refs.pagination.order_string,
                method: 'GET',
                success: function (data) {
                    that.ids = data.data.map(s => s.id);
                    that.terrarium_ids = data.data.map(s => s.terrarium.id);
                    that.intention_ids = [].concat.apply([], data.data.map(s => s.intentions.map(i => i.id)));
                    that.schedule_ids = [].concat.apply([], data.data.map(s => s.schedules.map(s => s.id)));
                    that.trigger_ids = [].concat.apply([], data.data.map(s => s.triggers.map(t => t.id)));

                    that.$refs.pagination.meta = data.meta;

                    that.$parent.ensureObjects('action_sequences', that.ids, data.data);
                    that.$parent.ensureObjects('terraria', that.terrarium_ids, data.data.map(s => s.terrarium));
                    that.$parent.ensureObjects(
                        'action_sequence_intentions',
                        that.intention_ids,
                        [].concat.apply([], data.data.map(s => s.intentions))
                    );
                    that.$parent.ensureObjects(
                        'action_sequence_schedules',
                        that.schedule_ids,
                        [].concat.apply([], data.data.map(s => s.schedules))
                    );
                    that.$parent.ensureObjects(
                        'action_sequence_triggers',
                        that.trigger_ids,
                        [].concat.apply([],
                            data.data.map(s => s.triggers))
                    );
                },
                error: function (error) {
                    console.log(JSON.stringify(error));
                }
            });
        },

        unsubscribe_all () {
            this.sequences.forEach((s) => s.unsubscribe());
            this.intentions.forEach((i) => i.unsubscribe());
            this.schedules.forEach((s) => s.unsubscribe());
            this.triggers.forEach((t) => t.unsubscribe());
            this.terraria.forEach((t) => t.unsubscribe());
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
