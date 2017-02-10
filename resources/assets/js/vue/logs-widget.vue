<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight">
                <thead>
                <tr>
                    <th data-field="source">
                        <a href="#!" v-on:click="set_order('source_name')">{{ $t('labels.source') }}</a>
                        <i v-show="order.field == 'source_name' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'source_name' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_source" type="text" v-model="filter.source_name" v-on:keyup.enter="set_filter">
                            <label for="filter_source">Filter</label>
                        </div>
                    </th>
                    <th data-field="action">
                        <a href="#!" v-on:click="set_order('action')">{{ $t('labels.action') }}</a>
                        <i v-show="order.field == 'action' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'action' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_action" type="text" v-model="filter.action" v-on:keyup.enter="set_filter">
                            <label for="filter_action">Filter</label>
                        </div>
                    </th>
                    <th data-field="target">
                        <a href="#!" v-on:click="set_order('target_name')">{{ $t('labels.target') }}</a>
                        <i v-show="order.field == 'target_name' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'target_name' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_target" type="text" v-model="filter.target_name" v-on:keyup.enter="set_filter">
                            <label for="filter_target">Filter</label>
                        </div>
                    </th>
                    <th data-field="associated">
                        <a href="#!" v-on:click="set_order('associatedWith_name')">{{ $t('labels.associated_with') }}</a>
                        <i v-show="order.field == 'associatedWith_name' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'associatedWith_name' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_associated_with" type="text" v-model="filter.associatedWith_name" v-on:keyup.enter="set_filter">
                            <label for="filter_associated_with">Filter</label>
                        </div>
                    </th>
                    <th>
                        <a href="#!" v-on:click="set_order('created_at')">{{ $t('labels.created_at') }}</a>
                        <i v-show="order.field == 'created_at' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'created_at' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                    </th>
                </tr>
                </thead>

                <tbody>

                <tr v-for="log in logs">
                    <td>
                        <span v-if="log.source !== null">
                            <i class="material-icons" v-show="log.source_type == 'Controlunit'">developer_board</i>
                            <i class="material-icons" v-show="log.source_type == 'Pump'">rotate_right</i>
                            <i class="material-icons" v-show="log.source_type == 'Valve'">transform</i>
                            <i class="material-icons" v-show="log.source_type == 'PhysicalSensor'">memory</i>
                            <i class="material-icons" v-show="log.source_type == 'LogicalSensor'">memory</i>
                            <i class="material-icons" v-show="log.source_type == 'Animal'">pets</i>
                            <i class="material-icons" v-show="log.source_type == 'Terrarium'">video_label</i>
                            <i class="material-icons" v-show="log.source_type == 'User'">person</i>
                            <i class="material-icons" v-show="log.source_type == 'UserAbility'">security</i>
                            <a v-bind:href="log.source.url">{{ log.source.name }}</a>
                        </span>
                    </td>
                    <td>{{ log.action }}</td>
                    <td>
                        <span v-if="log.target !== null">
                            <i class="material-icons" v-show="log.target_type == 'Controlunit'">developer_board</i>
                            <i class="material-icons" v-show="log.target_type == 'Pump'">rotate_right</i>
                            <i class="material-icons" v-show="log.target_type == 'Valve'">transform</i>
                            <i class="material-icons" v-show="log.target_type == 'PhysicalSensor'">memory</i>
                            <i class="material-icons" v-show="log.target_type == 'LogicalSensor'">memory</i>
                            <i class="material-icons" v-show="log.target_type == 'Animal'">pets</i>
                            <i class="material-icons" v-show="log.target_type == 'Terrarium'">video_label</i>
                            <i class="material-icons" v-show="log.target_type == 'User'">person</i>
                            <i class="material-icons" v-show="log.target_type == 'UserAbility'">security</i>
                            <a v-bind:href="log.target.url">{{ log.target.name }}</a>
                        </span>
                    </td>
                    <td>
                        <span v-if="log.associated !== null">
                            <i class="material-icons" v-show="log.associated_type == 'Controlunit'">developer_board</i>
                            <i class="material-icons" v-show="log.associated_type == 'Pump'">rotate_right</i>
                            <i class="material-icons" v-show="log.associated_type == 'Valve'">transform</i>
                            <i class="material-icons" v-show="log.associated_type == 'PhysicalSensor'">memory</i>
                            <i class="material-icons" v-show="log.associated_type == 'LogicalSensor'">memory</i>
                            <i class="material-icons" v-show="log.associated_type == 'Animal'">pets</i>
                            <i class="material-icons" v-show="log.associated_type == 'Terrarium'">video_label</i>
                            <i class="material-icons" v-show="log.associated_type == 'User'">person</i>
                            <i class="material-icons" v-show="log.associated_type == 'UserAbility'">security</i>
                            <a v-bind:href="log.associated.url">{{ log.associated.name }}</a>
                        </span>
                    </td>
                    <td>
                        {{ log.timestamps.created }}
                    </td>
                </tr>
                </tbody>
            </table>

            <ul class="pagination">
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
            logs: [],
            meta: [],
            filter: {},
            filter_string: '',
            order: {
                field: 'created_at',
                direction: 'desc'
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
        }
    },

    methods: {
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

            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            this.load_data();
        },
        set_filter: function() {
            this.filter_string = '&';
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
            this.order_string = '&order[' + this.order.field + ']=' + this.order.direction;
            var that = this;
            $.ajax({
                url: '/api/v1/logs?page=' + this.page + this.filter_string + this.order_string,
                method: 'GET',
                success: function (data) {
                    that.meta = data.meta;
                    that.logs = data.data;
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
        this.load_data();
    }
}
</script>
