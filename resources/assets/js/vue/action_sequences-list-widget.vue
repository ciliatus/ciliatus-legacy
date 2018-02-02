<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight collapsible" data-collapsible="expandable">
                <thead>
                <tr>
                    <th data-field="name">
                        <a href="#!" v-on:click="set_order('name')">{{ $t('labels.name') }}</a>
                        <i v-show="order.field == 'name' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'name' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_name" type="text" v-model="filter.name" v-on:keyup.enter="set_filter">
                            <label for="filter_name">Filter</label>
                        </div>
                    </th>

                    <th data-field="terrarium">
                        {{ $tc('components.terraria', 1) }}
                        <div class="input-field inline">
                            <input id="filter_terrarium_name" type="text" v-model="filter['terrarium.name']" v-on:keyup.enter="set_filter">
                            <label for="filter_terrarium_name">Filter</label>
                        </div>
                    </th>

                    <th style="width: 40px">
                    </th>
                </tr>
                </thead>

                <template v-for="action_sequence in action_sequences">
                    <tbody>
                        <tr class="collapsible-header">

                            <td>
                                <span>
                                    <i class="material-icons">playlist_play</i>
                                    <a v-bind:href="'/action_sequences/' + action_sequence.id + '/edit'">{{ action_sequence.name }}</a>
                                </span>
                            </td>

                            <td>
                                <span>
                                    <i class="material-icons">video_label</i>
                                    <a v-bind:href="'/terraria/' + action_sequence.terrarium.id">{{ action_sequence.terrarium.name }}</a>
                                </span>
                            </td>

                            <td>
                                <span>
                                    <a v-bind:href="'/action_sequences/' + action_sequence.id + '/edit'">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </span>
                            </td>

                        </tr>
                        <tr class="collapsible-body">
                            <td colspan="3" class="table-details-collapsible">
                                <ul v-show="action_sequence.schedules.length > 0">
                                    <strong>{{ $tc('components.action_sequence_schedules', 2) }}</strong>
                                    <li v-for="(schedule, index) in action_sequence.schedules">
                                        <i class="material-icons">schedule</i>
                                        <span>{{ schedule.timestamps.starts }}</span>
                                        <span v-if="schedule.timestamps.runonce"><i>{{ $t('labels.runonce') }}</i></span>
                                        <span v-else><i>{{ $t('labels.daily') }}</i></span>
                                    </li>
                                </ul>

                                <ul v-show="action_sequence.triggers.length > 0">
                                    <strong>{{ $tc('components.action_sequence_triggers', 2) }}</strong>
                                    <li v-for="(trigger, index) in action_sequence.triggers">
                                        <i class="material-icons">flare</i>
                                        <span>{{ $t('labels.' + trigger.logical_sensor.type) }}</span>
                                        <span v-if="trigger.reference_value_comparison_type == 'greater'">&gt;</span>
                                        <span v-if="trigger.reference_value_comparison_type == 'lesser'">&lt;</span>
                                        <span>{{ trigger.reference_value }}</span>
                                        <span>{{ $t('labels.between') }} {{ trigger.timeframe.start }} {{ $t('labels.and') }} {{ trigger.timeframe.end }},</span>
                                        <span>{{ $t('labels.minimum_timeout') }}: {{ trigger.minimum_timeout_minutes }} {{ $tc('units.minutes', trigger.minimum_timeout_minutes) }}</span>
                                    </li>
                                </ul>

                                <ul v-show="action_sequence.intentions.length > 0">
                                    <strong>{{ $tc('components.action_sequence_intentions', 2) }}</strong>
                                    <li v-for="(intention, index) in action_sequence.intentions">
                                        <i class="material-icons">explore</i>
                                        <span>{{ $t('labels.' + intention.intention) }}</span>
                                        <span>{{ $t('labels.' + intention.type) }}</span>
                                        <span>{{ $t('labels.between') }} {{ intention.timeframe.start }} {{ $t('labels.and') }} {{ intention.timeframe.end }},</span>
                                        <span>{{ $t('labels.minimum_timeout') }}: {{ intention.minimum_timeout_minutes }} {{ $tc('units.minutes', intention.minimum_timeout_minutes) }}</span>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </template>
            </table>

            <ul class="pagination" v-if="meta.hasOwnProperty('pagination')">
                <li v-bind:class="{ 'disabled': meta.pagination.current_page == 1, 'waves-effect': meta.pagination.current_page != 1 }">
                    <a href="#!" v-on:click="set_page(1)"><i class="material-icons">first_page</i></a>
                </li>
                <li v-bind:class="{ 'disabled': meta.pagination.current_page == 1, 'waves-effect': meta.pagination.current_page != 1 }">
                    <a href="#!" v-on:click="set_page(meta.pagination.current_page-1)"><i class="material-icons">chevron_left</i></a>
                </li>

                <li v-if="meta.pagination.current_page-3 > 0" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page-3)">{{ meta.pagination.current_page-3 }}</a></li>
                <li v-if="meta.pagination.current_page-2 > 0" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page-2)">{{ meta.pagination.current_page-2 }}</a></li>
                <li v-if="meta.pagination.current_page-1 > 0" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page-1)">{{ meta.pagination.current_page-1 }}</a></li>

                <li class="active"><a href="#!">{{ meta.pagination.current_page }}</a></li>

                <li v-if="meta.pagination.current_page+1 <= meta.pagination.total_pages" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page+1)">{{ meta.pagination.current_page+1 }}</a></li>
                <li v-if="meta.pagination.current_page+2 <= meta.pagination.total_pages" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page+2)">{{ meta.pagination.current_page+2 }}</a></li>
                <li v-if="meta.pagination.current_page+3 <= meta.pagination.total_pages" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page+3)">{{ meta.pagination.current_page+3 }}</a></li>

                <li v-bind:class="{ 'disabled': meta.pagination.current_page == meta.pagination.total_pages, 'waves-effect': meta.pagination.current_page != meta.pagination.total_pages }">
                    <a href="#!" v-on:click="set_page(meta.pagination.current_page+1)"><i class="material-icons">chevron_right</i></a>
                </li>
                <li v-bind:class="{ 'disabled': meta.pagination.current_page == meta.pagination.total_pages, 'waves-effect': meta.pagination.current_page != meta.pagination.total_pages }">
                    <a href="#!" v-on:click="set_page(meta.pagination.total_pages)"><i class="material-icons">last_page</i></a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            action_sequences: [],
            meta: [],
            filter: {
                name: '',
                model: '',
                'terrarium.name': ''
            },
            filter_string: '',
            order: {
                field: 'name',
                direction: 'asc'
            },
            order_string: '',
            page: 1
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
        refreshTimeoutSeconds: {
            type: Number,
            default: 60,
            required: false
        }
    },

    methods: {
        update: function(cu) {
            var item = null;
            this.action_sequences.forEach(function(data, index) {
                if (data.id === cu.action_sequence.id) {
                    item = index;
                }
            });
            if (item !== null) {
                var that = this;
                $.ajax({
                    url: '/api/v1/action_sequences/' + cu.action_sequence.id,
                    method: 'GET',
                    success: function (data) {
                        that.action_sequences.splice(item, 1, data.data);
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            }
        },

        delete: function(cu) {
            var item = null;
            this.action_sequences.forEach(function(data, index) {
                if (data.id === cu.action_sequence.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.action_sequences.splice(item, 1);
            }
        },
        
        set_order: function(field) {
            if (this.order.field == field || field === null) {
                if (this.order.direction == 'asc') {
                    this.order.direction = 'desc';
                }
                else {
                    this.order.direction = 'asc';
                }
            }
            else {
                this.order.field = field;
            }

            this.order_string = 'order[' + this.order.field + ']=' + this.order.direction;
            this.load_data();
        },
        set_filter: function() {
            this.filter_string = '&';
            if (this.sourceFilter !== '') {
                this.filter_string += this.sourceFilter + '&';
            }
            for (var prop in this.filter) {
                if (this.filter.hasOwnProperty(prop)) {
                    if (this.filter[prop] !== null
                        && this.filter[prop] !== '') {

                        this.filter_string += 'filter[' + prop + ']=like:*' + this.filter[prop] + '*&';
                    }
                }
            }
            this.load_data();
        },
        set_page: function(page) {
            this.page = page;
            this.load_data();
        },
        load_data: function() {
            window.eventHubVue.processStarted();
            this.order_string = 'order[' + this.order.field + ']=' + this.order.direction;
            var that = this;
            $.ajax({
                url: '/api/v1/action_sequences?with[]=schedules&with[]=triggers&with[]=intentions&with[]=terrarium&page=' +
                     that.page + that.filter_string + that.order_string + '&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    that.meta = data.meta;
                    that.action_sequences = data.data;
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
        window.echo.private('dashboard-updates').listen('ActionSequenceUpdated', (e) => {
            this.update(e);
        }).listen('ActionSequenceDeleted', (e) => {
            this.delete(e);
        });

        var that = this;
        setTimeout(function() {
            that.set_filter();
        }, 100);

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function() {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000)
        }
    }
}
</script>
