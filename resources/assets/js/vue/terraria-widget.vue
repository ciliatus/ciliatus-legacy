<template>
    <div>
        <div class="row" v-if="!terrariumId">
            <div class="col s10">
                <ul class="pagination" v-if="meta.hasOwnProperty('pagination')">
                    <li v-bind:class="['hide-on-small-only', { 'disabled': meta.pagination.current_page == 1, 'waves-effect': meta.pagination.current_page != 1 }]">
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
                    <li v-bind:class="['hide-on-small-only', { 'disabled': meta.pagination.current_page == meta.pagination.total_pages, 'waves-effect': meta.pagination.current_page != meta.pagination.total_pages }]">
                        <a href="#!" v-on:click="set_page(meta.pagination.total_pages)"><i class="material-icons">last_page</i></a>
                    </li>
                </ul>
            </div>
            <div class="col s2 right-align" v-if="!terrariumId">
                <ul class="pagination">
                    <li class="waves-effect">
                        <a href="#!"><i class="material-icons" v-on:click="toggle_filters">filter_list</i></a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row" v-if="!terrariumId" v-show="showFilters">
            <div class="col s12">
                <div class="input-field inline">
                    <input id="filter_display_name" type="text" :placeholder="$t('labels.display_name')"
                           v-model="filter.display_name" v-on:keyup.enter="set_filter">
                    <label for="filter_display_name">{{ $t('labels.display_name') }}</label>
                </div>
                <div class="input-field inline">
                    <input id="filter_animal_display_name" type="text" :placeholder="$tc('components.animal', 1) + ' ' + $t('labels.display_name')"
                           v-model="filter['animals.display_name']" v-on:keyup.enter="set_filter">
                    <label for="filter_animal_display_name">{{ $tc('components.animal', 1) }} {{ $t('labels.display_name') }}</label>
                </div>
                <div class="input-field inline">
                    <input id="filter_animal_latin_name" type="text" :placeholder="$tc('components.animal', 1) + ' ' + $t('labels.latin_name')"
                           v-model="filter['animals.lat_name']" v-on:keyup.enter="set_filter">
                    <label for="filter_animal_latin_name">{{ $tc('components.animal', 1) }} {{ $t('labels.latin_name') }}</label>
                </div>
                <div class="input-field inline">
                    <input id="filter_animal_common_name" type="text" :placeholder="$tc('components.animal', 1) + ' ' + $t('labels.common_name')"
                           v-model="filter['animals.common_name']" v-on:keyup.enter="set_filter">
                    <label for="filter_animal_common_name">{{ $tc('components.animal', 1) }} {{ $t('labels.common_name') }}</label>
                </div>
            </div>
        </div>

        <div :class="containerClasses" :id="containerId">
            <div :class="wrapperClasses" v-for="terrarium in terraria">
                <!-- Modals -->
                <div :id="terrarium.data.id + '_irrigate'" class="modal" v-if="terrarium.data.capabilities.irrigate">
                    <form :action="'/api/v1/terraria/' + terrarium.data.id + '/action_sequence'" data-method="POST"
                          :id="'form_irrigate_' + terrarium.data.id" v-on:submit="submit">
                        <div class="modal-content">
                            <h4>{{ $t('labels.irrigate') }}</h4>
                            <p>
                                <input type="hidden" name="template" value="irrigate">
                                <input type="hidden" name="runonce" value="On">
                                <input type="hidden" name="schedule_now" value="On">
                                <input :id="'duration_minutes_irrigate_' + terrarium.data.id" type="text" :placeholder="$tc('units.minutes', 2)" name="duration_minutes" value="1">
                                <label :for="'duration_minutes_irrigate_' + terrarium.data.id">{{ $t('labels.duration') }} {{ ($tc('units.minutes', 2)) }}</label>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button class="modal-action modal-close btn waves-effect waves-green" type="submit">{{ $t('buttons.start') }}</button>
                        </div>
                    </form>
                </div>

                <div :id="terrarium.data.id + '_ventilate'" class="modal" v-if="terrarium.data.capabilities.ventilate">
                    <form :action="'/api/v1/terraria/' + terrarium.data.id + '/action_sequence'" data-method="POST"
                          :id="'form_ventilate_' + terrarium.data.id" v-on:submit="submit">
                        <div class="modal-content">
                            <h4>{{ $t('labels.ventilate') }}</h4>
                            <p>
                                <input type="hidden" name="template" value="ventilation">
                                <input type="hidden" name="runonce" value="On">
                                <input type="hidden" name="schedule_now" value="On">
                                <input :id="'duration_minutes_ventilate_' + terrarium.data.id" type="text" :placeholder="$tc('units.minutes', 2)" name="duration_minutes" value="3">
                                <label :for="'duration_minutes_ventilate_' + terrarium.data.id">{{ $t('labels.duration') }} {{ ($tc('units.minutes', 2)) }}</label>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button class="modal-action modal-close btn waves-effect waves-green" type="submit">{{ $t('buttons.start') }}</button>
                        </div>
                    </form>
                </div>

                <div :id="terrarium.data.id + '_heat_up'" class="modal" v-if="terrarium.data.capabilities.heat_up">
                    <form :action="'/api/v1/terraria/' + terrarium.data.id + '/action_sequence'" data-method="POST"
                          :id="'form_heat_up_' + terrarium.data.id" v-on:submit="submit">
                        <div class="modal-content">
                            <h4>{{ $t('labels.heat_up') }}</h4>
                            <p>
                                <input type="hidden" name="template" value="heat_up">
                                <input type="hidden" name="runonce" value="On">
                                <input type="hidden" name="schedule_now" value="On">
                                <input :id="'duration_minutes_heat_up_' + terrarium.data.id" type="text" :placeholder="$tc('units.minutes', 2)" name="duration_minutes" value="3">
                                <label :for="'duration_minutes_heat_up_' + terrarium.data.id">{{ $t('labels.duration') }} {{ ($tc('units.minutes', 2)) }}</label>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button class="modal-action modal-close btn waves-effect waves-green" type="submit">{{ $t('buttons.start') }}</button>
                        </div>
                    </form>
                </div>

                <div :id="terrarium.data.id + '_cool_down'" class="modal" v-if="terrarium.data.capabilities.cool_down">
                    <form :action="'/api/v1/terraria/' + terrarium.data.id + '/action_sequence'" data-method="POST"
                          :id="'form_cool_down_' + terrarium.data.id" v-on:submit="submit">
                        <div class="modal-content">
                            <h4>{{ $t('labels.cool_down') }}</h4>
                            <p>
                                <input type="hidden" name="template" value="cool_down">
                                <input type="hidden" name="runonce" value="On">
                                <input type="hidden" name="schedule_now" value="On">
                                <input :id="'duration_minutes_cool_down_' + terrarium.data.id" type="text" :placeholder="$tc('units.minutes', 2)" name="duration_minutes" value="3">
                                <label :for="'duration_minutes_cool_down_' + terrarium.data.id">{{ $t('labels.duration') }} {{ ($tc('units.minutes', 2)) }}</label>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button class="modal-action modal-close btn waves-effect waves-green" type="submit">{{ $t('buttons.start') }}</button>
                        </div>
                    </form>
                </div>

                <!-- Card -->
                <div class="card">
                    <div class="card-image terrarium-card-image"
                         v-bind:style="terrarium.data.default_background_filepath ? 'background-image: url(\'' + terrarium.data.default_background_filepath + '\');' : 'background-image: url(\'/svg/Ciliatus_Logo.svg\'); background-position: top center;'">
                        <div>
                            <inline-graph :parentid="terrarium.data.id" graphtype="humidity_percent" type="line"
                                          :options="{'fill': null, 'strokeWidth': '2', 'stroke': '#2196f3', width: '100%', height:'140px', min: 1, max: 99}"
                                          :source="'/api/v1/terraria/'+terrarium.data.id+'/sensorreadingsByType/humidity_percent'"
                                          :data-prefill="terrarium.data.humidity_history"></inline-graph>
                        </div>

                        <div style="position: relative; top: -145px">
                            <inline-graph :parentid="terrarium.data.id" graphtype="temperature_celsius" type="line"
                                          :options="{'fill': null, 'strokeWidth': '2', 'stroke': '#b71c1c', width: '100%', height:'140px', min: 1, max: 99}"
                                          :source="'/api/v1/terraria/'+terrarium.data.id+'/sensorreadingsByType/temperature_celsius'"
                                          :data-prefill="terrarium.data.temperature_history"></inline-graph>

                        </div>

                        <div class="card-title">
                            <span><a :href="'/terraria/' + terrarium.data.id">{{ terrarium.data.display_name }}</a></span>
                            <loading-indicator :size="20" v-show="terrarium.data.loading_data"></loading-indicator>
                            <a href="#!"><i class="material-icons right activator">more_vert</i></a>
                        </div>
                    </div>

                    <div class="card-content" v-if="terrarium.data.cooked_temperature_celsius !== null || terrarium.data.cooked_humidity_percent !== null || terrarium.data.heartbeat_critical">
                        <p>
                            <span v-show="terrarium.data.cooked_temperature_celsius !== null" v-bind:class="{ 'red-text': terrarium.data.temperature_critical, 'darken-3': terrarium.data.temperature_critical }">
                                {{ $t("labels.temperature") }}: {{ terrarium.data.cooked_temperature_celsius }}°C
                                <br />
                            </span>
                            <span v-show="terrarium.data.cooked_humidity_percent !== null" v-bind:class="{ 'red-text': terrarium.data.humidity_critical, 'darken-3': terrarium.data.humidity_critical }">
                                {{ $t("labels.humidity") }}: {{ terrarium.data.cooked_humidity_percent }}%
                            </span>
                            <span v-show="terrarium.data.heartbeat_critical" class="red-text darken-3">
                                <br />
                                {{ $t("tooltips.heartbeat_critical") }}
                            </span>
                        </p>
                    </div>

                    <div class="card-reveal">
                        <div>
                            <strong>{{ terrarium.data.display_name }}</strong>
                            <i class="material-icons right card-title card-title-small">close</i>
                        </div>

                        <p v-for="animal in animals.filter(a => a.data.terrarium_id === terrarium.id)">
                            <i class="material-icons">pets</i>
                            <a v-bind:href="'/animals/' + animal.data.id">{{ animal.data.display_name }}</a> <i>{{ animal.data.common_name }}</i>
                        </p>

                        <p v-if="terrarium.data.capabilities.irrigate">
                            <i class="material-icons">play_arrow</i>
                            <a href="#!" v-on:click="action_sequence_modal(terrarium.data.id, 'irrigate')">{{ $t('buttons.irrigate') }}</a>
                        </p>
                        <p v-if="terrarium.data.capabilities.ventilate">
                            <i class="material-icons">play_arrow</i>
                            <a href="#!" v-on:click="action_sequence_modal(terrarium.data.id, 'ventilate')">{{ $t('buttons.ventilate') }}</a>
                        </p>
                        <p v-if="terrarium.data.capabilities.heat_up">
                            <i class="material-icons">play_arrow</i>
                            <a href="#!" v-on:click="action_sequence_modal(terrarium.data.id, 'heat_up')">{{ $t('buttons.heat_up') }}</a>
                        </p>
                        <p v-if="terrarium.data.capabilities.cool_down">
                            <i class="material-icons">play_arrow</i>
                            <a href="#!" v-on:click="action_sequence_modal(terrarium.data.id, 'cool_down')">{{ $t('buttons.cool_down') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import LoadingIndicator from './loading-indicator.vue';
import InlineGraph from './inline-graph.vue';

export default {
    data () {
        return {
            ids: [],
            animal_ids: [],
            initial: true,
            meta: [],
            filter: {},
            filter_string: '',
            order: {
                field: 'display_name',
                direction: 'asc'
            },
            order_string: '',
            page: 1
        }
    },

    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        terrariumId: {
            type: String,
            default: null,
            required: false
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        subscribeAdd: {
            type: Boolean,
            default: true,
            required: false
        },
        subscribeDelete: {
            type: Boolean,
            default: true,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        },
        containerId: {
            type: String,
            default: 'terraria-masonry-grid',
            required: false
        },
        showFilters: {
            type: Boolean,
            default: false,
            required: false
        }
    },

    computed: {
        terraria () {
            let that = this;
            return this.$store.state.terraria.filter(function(t) {
                return that.ids.includes(t.id) && t.data !== null
            });
        },

        animals() {
            let that = this;
            return this.$store.state.animals.filter(function(a) {
                return that.animal_ids.includes(a.id) && a.data !== null
            })
        }
    },

    watch: {
        'terraria': function() {
            this.$nextTick(function() {
                let grid = $('#' + this.containerId + '.masonry-grid');
                if (grid.length > 0) {
                    grid.masonry('reloadItems');
                    grid.masonry('layout');
                }
                $('.modal').modal();
            });
        }
    },

    components: {
        'inline-graph': InlineGraph,
        'loading-indicator': LoadingIndicator
    },

    methods: {
        toggle_filters: function() {
            this.showFilters = !this.showFilters;
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
        action_sequence_modal: function(terrarium_id, action) {
            $('#' + terrarium_id + '_' + action).modal('open');
        },

        submit: function(e) {
            window.submit_form(e);
        },

        load_data: function() {
            if (this.terrariumId === null) {
                let that = this;

                this.order_string = 'order[' + this.order.field + ']=' + this.order.direction;

                $.ajax({
                    url: '/api/v1/terraria/?pagination[per_page]=6&page=' +
                         that.page + that.filter_string + that.order_string,
                    method: 'GET',
                    success: function (data) {
                        that.ids = data.data.map(t => t.id);
                        that.meta = data.meta;
                        that.$parent.ensureObjects('terraria', that.ids, data.data);
                        that.load_animals();
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            }
            else {
                this.ids = [this.terrariumId];
                this.$parent.ensureObjects('terraria', this.ids);
            }
        },

        load_animals: function() {
            let that = this;

            let url = '/api/v1/animals/?all=true&filter[terrarium_id]=' + this.ids.join(':or:');

            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    that.animal_ids = data.data.map(a => a.id);
                    that.$parent.ensureObjects('animals', that.animal_ids, data.data);
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
            that.set_filter();
        }, 100);
    }

}
</script>