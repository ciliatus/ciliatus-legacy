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
            <div class="col s2 right-align" v-if="!animalId">
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
        <div :class="[containerClasses, 'masonry-grid']" :id="containerId">
            <div :class="wrapperClasses" v-for="terrarium in terraria">
                <!-- Modals -->
                <div :id="terrarium.id + '_irrigate'" class="modal" v-if="terrarium.capabilities.irrigate">
                    <form :action="'/api/v1/terraria/' + terrarium.id + '/action_sequence'" data-method="POST"
                          :id="'form_irrigate_' + terrarium.id" v-on:submit="submit">
                        <div class="modal-content">
                            <h4>{{ $t('labels.irrigate') }}</h4>
                            <p>
                                <input type="hidden" name="template" value="irrigate">
                                <input type="hidden" name="runonce" value="On">
                                <input type="hidden" name="schedule_now" value="On">
                                <input :id="'duration_minutes_irrigate_' + terrarium.id" type="text" :placeholder="$tc('units.minutes', 2)" name="duration_minutes" value="1">
                                <label :for="'duration_minutes_irrigate_' + terrarium.id">{{ $t('labels.duration') }} {{ ($tc('units.minutes', 2)) }}</label>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button class="modal-action modal-close btn waves-effect waves-green" type="submit">{{ $t('buttons.start') }}</button>
                        </div>
                    </form>
                </div>

                <div :id="terrarium.id + '_ventilate'" class="modal" v-if="terrarium.capabilities.ventilate">
                    <form :action="'/api/v1/terraria/' + terrarium.id + '/action_sequence'" data-method="POST"
                          :id="'form_ventilate_' + terrarium.id" v-on:submit="submit">
                        <div class="modal-content">
                            <h4>{{ $t('labels.ventilate') }}</h4>
                            <p>
                                <input type="hidden" name="template" value="ventilation">
                                <input type="hidden" name="runonce" value="On">
                                <input type="hidden" name="schedule_now" value="On">
                                <input :id="'duration_minutes_ventilate_' + terrarium.id" type="text" :placeholder="$tc('units.minutes', 2)" name="duration_minutes" value="3">
                                <label :for="'duration_minutes_ventilate_' + terrarium.id">{{ $t('labels.duration') }} {{ ($tc('units.minutes', 2)) }}</label>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button class="modal-action modal-close btn waves-effect waves-green" type="submit">{{ $t('buttons.start') }}</button>
                        </div>
                    </form>
                </div>

                <div :id="terrarium.id + '_heat_up'" class="modal" v-if="terrarium.capabilities.heat_up">
                    <form :action="'/api/v1/terraria/' + terrarium.id + '/action_sequence'" data-method="POST"
                          :id="'form_heat_up_' + terrarium.id" v-on:submit="submit">
                        <div class="modal-content">
                            <h4>{{ $t('labels.heat_up') }}</h4>
                            <p>
                                <input type="hidden" name="template" value="heat_up">
                                <input type="hidden" name="runonce" value="On">
                                <input type="hidden" name="schedule_now" value="On">
                                <input :id="'duration_minutes_heat_up_' + terrarium.id" type="text" :placeholder="$tc('units.minutes', 2)" name="duration_minutes" value="3">
                                <label :for="'duration_minutes_heat_up_' + terrarium.id">{{ $t('labels.duration') }} {{ ($tc('units.minutes', 2)) }}</label>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button class="modal-action modal-close btn waves-effect waves-green" type="submit">{{ $t('buttons.start') }}</button>
                        </div>
                    </form>
                </div>

                <div :id="terrarium.id + '_cool_down'" class="modal" v-if="terrarium.capabilities.cool_down">
                    <form :action="'/api/v1/terraria/' + terrarium.id + '/action_sequence'" data-method="POST"
                          :id="'form_cool_down_' + terrarium.id" v-on:submit="submit">
                        <div class="modal-content">
                            <h4>{{ $t('labels.cool_down') }}</h4>
                            <p>
                                <input type="hidden" name="template" value="cool_down">
                                <input type="hidden" name="runonce" value="On">
                                <input type="hidden" name="schedule_now" value="On">
                                <input :id="'duration_minutes_cool_down_' + terrarium.id" type="text" :placeholder="$tc('units.minutes', 2)" name="duration_minutes" value="3">
                                <label :for="'duration_minutes_cool_down_' + terrarium.id">{{ $t('labels.duration') }} {{ ($tc('units.minutes', 2)) }}</label>
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
                         v-bind:style="terrarium.default_background_filepath ? 'background-image: url(\'' + terrarium.default_background_filepath + '\');' : 'background-image: url(\'/svg/Ciliatus_Logo.svg\'); background-position: top center;'">
                        <div>
                            <inline-graph :parentid="terrarium.id" graphtype="humidity_percent" type="line"
                                          :options="{'fill': null, 'strokeWidth': '2', 'stroke': '#2196f3', width: '100%', height:'140px', min: 1, max: 99}"
                                          :source="'/api/v1/terraria/'+terrarium.id+'/sensorreadingsByType/humidity_percent'"
                                          :data-prefill="terrarium.humidity_history"></inline-graph>
                        </div>

                        <div style="position: relative; top: -145px">
                            <inline-graph :parentid="terrarium.id" graphtype="temperature_celsius" type="line"
                                          :options="{'fill': null, 'strokeWidth': '2', 'stroke': '#b71c1c', width: '100%', height:'140px', min: 1, max: 99}"
                                          :source="'/api/v1/terraria/'+terrarium.id+'/sensorreadingsByType/temperature_celsius'"
                                          :data-prefill="terrarium.temperature_history"></inline-graph>

                        </div>

                        <div class="card-title">
                            <span><a :href="'/terraria/' + terrarium.id">{{ terrarium.display_name }}</a></span>
                            <loading-indicator :size="20" v-show="terrarium.loading_data"></loading-indicator>
                            <a href="#"><i class="material-icons right activator">more_vert</i></a>
                        </div>
                    </div>
                    <div class="card-content" v-if="terrarium.cooked_temperature_celsius !== null || terrarium.cooked_humidity_percent !== null || terrarium.heartbeat_critical">
                        <p>
                            <span v-show="terrarium.cooked_temperature_celsius !== null" v-bind:class="{ 'red-text': terrarium.temperature_critical, 'darken-3': terrarium.temperature_critical }">
                                {{ $t("labels.temperature") }}: {{ terrarium.cooked_temperature_celsius }}°C
                                <br />
                            </span>
                            <span v-show="terrarium.cooked_humidity_percent !== null" v-bind:class="{ 'red-text': terrarium.humidity_critical, 'darken-3': terrarium.humidity_critical }">
                                {{ $t("labels.humidity") }}: {{ terrarium.cooked_humidity_percent }}%
                            </span>
                            <span v-show="terrarium.heartbeat_critical" class="red-text darken-3">
                                <br />
                                {{ $t("tooltips.heartbeat_critical") }}
                            </span>
                        </p>
                    </div>

                    <div class="card-reveal">
                        <span class="card-title card-title-small">{{ $tc("components.animals", 2) }}<i class="material-icons right">close</i></span>

                        <p v-for="animal in terrarium.animals">
                            <a v-bind:href="'/animals/' + animal.id">{{ animal.display_name }}</a> <i>{{ animal.common_name }}</i>
                        </p>

                        <span class="card-title card-title-small">{{ $t("labels.start_action_sequence") }}</span>

                        <p v-if="terrarium.capabilities.irrigate">
                            <a href="#" v-on:click="action_sequence_modal(terrarium.id, 'irrigate')">{{ $t('buttons.irrigate') }}</a>
                        </p>
                        <p v-if="terrarium.capabilities.ventilate">
                            <a href="#" v-on:click="action_sequence_modal(terrarium.id, 'ventilate')">{{ $t('buttons.ventilate') }}</a>
                        </p>
                        <p v-if="terrarium.capabilities.heat_up">
                            <a href="#" v-on:click="action_sequence_modal(terrarium.id, 'heat_up')">{{ $t('buttons.heat_up') }}</a>
                        </p>
                        <p v-if="terrarium.capabilities.cool_down">
                            <a href="#" v-on:click="action_sequence_modal(terrarium.id, 'cool_down')">{{ $t('buttons.cool_down') }}</a>
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
            terraria: [],
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
        update: function(t) {
            var item = null;
            this.terraria.forEach(function(data, index) {
                if (data.id === t.terrarium_id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true) {
                var that = this;
                $.ajax({
                    url: '/api/v1/terraria/' + t.terrarium_id + '?default_history_minutes=true',
                    method: 'GET',
                    success: function (data) {
                        that.terraria.push(data.data);
                        window.eventHubVue.$emit('TerrariumGraphUpdated', data.data);

                        that.$nextTick(function() {
                            that.refresh_grid();
                        });
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            }
            else if (item !== null) {
                this.$set(this.terraria[item], 'loading_data', true);
                var that = this;
                $.ajax({
                    url: '/api/v1/terraria/' + t.terrarium_id + '?default_history_minutes=true',
                    method: 'GET',
                    success: function (data) {
                        that.terraria.splice(item, 1, data.data);
                        window.eventHubVue.$emit('TerrariumGraphUpdated', data.data);

                        that.$nextTick(function() {
                            that.refresh_grid();
                        });
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                        this.$set(this.terraria[item], 'loading_data', false);
                    }
                });
            }
        },
        delete: function(t) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.terraria.forEach(function(data, index) {
                if (data.id === t.terrarium_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.terraria.splice(item, 1);
            }

            this.$nextTick(function() {
                this.refresh_grid();
            });
        },

        submit: function(e) {
            window.submit_form(e);
        },

        load_data: function() {
            var that = this;
            var source_url = '';
            this.order_string = 'order[' + this.order.field + ']=' + this.order.direction;
            if (this.terrariumId !== null) {
                source_url = '/api/v1/terraria/' + this.terrariumId + '?with[]=action_sequences&with[]=animals&' +
                             'with[]=files&with[]=physical_sensors&with[]=valves';
            }
            else {
                source_url = '/api/v1/terraria/?with[]=action_sequences&with[]=animals&with[]=files&' +
                             'with[]=physical_sensors&with[]=valves&pagination[per_page]=6&page=' +
                             this.page + this.filter_string + this.order_string;
            }

            window.eventHubVue.processStarted();
            $.ajax({
                url: source_url,
                method: 'GET',
                success: function (data) {
                    if (that.terrariumId !== null) {
                        that.terraria = [data.data];
                    }
                    else {
                        that.meta = data.meta;
                        that.terraria = data.data;
                    }

                    that.$nextTick(function() {
                        if (that.initial) {
                            var element = '#' + this.containerId;
                            $(element).masonry({
                                columnWidth: '.col',
                                itemSelector: '.col',
                            });
                            that.initial = false;
                        }
                        that.refresh_grid();
                    });

                    window.eventHubVue.processEnded();
                },
                error: function (error) {
                    console.log(JSON.stringify(error));
                    window.eventHubVue.processEnded();
                }
            });
        },

        refresh_grid: function() {
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
            $('.modal').modal();
        }
    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('TerrariumUpdated', (e) => {
                this.update(e);
            }).listen('TerrariumDeleted', (e) => {
                this.delete(e);
            });

        var that = this;
        setTimeout(function() {
            that.set_filter();
        }, 100);

        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function() {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000)
        }
    }

}
</script>