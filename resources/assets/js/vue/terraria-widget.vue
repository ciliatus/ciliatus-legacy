<template>

    <div>

        <pagination ref="pagination"
                    v-show="terrariumId === null"
                    :source-filter="sourceFilter"
                    :show-filters="showFilters"
                    :filter-fields="[{name: 'display_name', path: 'display_name'}]">
        </pagination>

        <div :class="containerClasses" :id="containerId">
            <div :class="wrapperClasses" v-for="terrarium in terraria">
                <template v-if="terrarium.data">
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
                                    <input type="hidden" name="template" value="ventilate">
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
                </template>

                <!-- Card -->
                <div class="card" v-if="terrarium.data">
                    <div class="card-image terrarium-card-image"
                         v-bind:style="terrarium.data.default_background_filepath ?
                                        'background-image: url(\'' + terrarium.data.default_background_filepath + '\');' :
                                        'background-image: url(\'/svg/Ciliatus_Logo.svg\'); background-position: top center;'">
                        <div>
                            <inline-graph v-if="terrarium.data"
                                          :points="terrarium.data.humidity_history"
                                          :options="{'fill': null, 'strokeWidth': '2', 'stroke': '#2196f3',
                                                    width: '100%', height:'140px',
                                                    min: humidityGraphMin, max: humidityGraphMax}"
                                          source-type="terraria"
                                          :source-id="terrarium.id"
                                          source-field="humidity_history"
                                          type="line">
                            </inline-graph>
                        </div>

                        <div style="position: relative; top: -145px">
                            <inline-graph v-if="terrarium.data"
                                          :points="terrarium.data.temperature_history"
                                          :options="{'fill': null, 'strokeWidth': '2', 'stroke': '#b71c1c',
                                                    width: '100%', height:'140px',
                                                    min: temperatureGraphMin, max: temperatureGraphMax}"
                                          source-type="terraria"
                                          :source-id="terrarium.id"
                                          source-field="temperature_history"
                                          type="line">
                            </inline-graph>
                        </div>

                        <div class="card-title">
                            <span><a :href="'/terraria/' + terrarium.data.id">{{ terrarium.data.display_name }}</a></span>
                            <loading-indicator :size="20" v-show="terrarium.refreshing"> </loading-indicator>
                            <a href="#!"><i class="mdi mdi-24px mdi-dots-vertical right activator white-text"></i></a>
                        </div>
                    </div>

                    <div class="card-content"
                         v-if="terrarium.data.cooked_temperature_celsius !== null ||
                               terrarium.data.cooked_humidity_percent !== null ||
                               terrarium.data.heartbeat_critical">

                        <div v-show="terrarium.data.cooked_temperature_celsius !== null" v-bind:class="{ 'red-text': terrarium.data.temperature_critical, 'darken-3': terrarium.data.temperature_critical }">

                            {{ $t("labels.temperature") }}: {{ terrarium.data.cooked_temperature_celsius }}°C

                            <i v-if="terrarium.data.heartbeat_critical"
                               class="mdi mdi-sync-off deep-orange-text tooltipped"
                               data-delay="50" data-html="true"
                               :data-tooltip="'<div style=\'max-width: 300px\'>' + $t('tooltips.heartbeat_critical') + '</div>'"></i>

                        </div>

                        <div v-show="terrarium.data.cooked_humidity_percent !== null"
                             v-bind:class="{ 'red-text': terrarium.data.humidity_critical, 'darken-3': terrarium.data.humidity_critical }">

                            {{ $t("labels.humidity") }}: {{ terrarium.data.cooked_humidity_percent }}%

                            <i v-if="terrarium.data.heartbeat_critical"
                               class="mdi mdi-sync-off deep-orange-text tooltipped"
                               data-delay="50" data-html="true"
                               :data-tooltip="'<div style=\'max-width: 300px\'>' + $t('tooltips.heartbeat_critical') + '</div>'"></i>

                        </div>
                    </div>

                    <div class="card-reveal">
                        <div>
                            <strong>{{ terrarium.data.display_name }}</strong>
                            <i class="mdi mdi-24px mdi-close right card-title card-title-small"></i>
                        </div>

                        <p v-for="animal in animals.filter(a => a.data.terrarium_id === terrarium.id)">
                            <i class="mdi mdi-18px mdi-paw"></i>
                            <a v-bind:href="'/animals/' + animal.data.id">{{ animal.data.display_name }}</a> <i>{{ animal.data.common_name }}</i>
                        </p>

                        <p v-if="terrarium.data.capabilities.irrigate">
                            <i class="mdi mdi-18px mdi-weather-pouring"></i>
                            <a href="#!" v-on:click="action_sequence_modal(terrarium.data.id, 'irrigate')">{{ $t('buttons.irrigate') }}</a>
                        </p>
                        <p v-if="terrarium.data.capabilities.ventilate">
                            <i class="mdi mdi-18px mdi-weather-windy"></i>
                            <a href="#!" v-on:click="action_sequence_modal(terrarium.data.id, 'ventilate')">{{ $t('buttons.ventilate') }}</a>
                        </p>
                        <p v-if="terrarium.data.capabilities.heat_up">
                            <i class="mdi mdi-18px mdi-weather-sunny"></i>
                            <a href="#!" v-on:click="action_sequence_modal(terrarium.data.id, 'heat_up')">{{ $t('buttons.heat_up') }}</a>
                        </p>
                        <p v-if="terrarium.data.capabilities.cool_down">
                            <i class="mdi mdi-18px mdi-snowflake"></i>
                            <a href="#!" v-on:click="action_sequence_modal(terrarium.data.id, 'cool_down')">{{ $t('buttons.cool_down') }}</a>
                        </p>
                    </div>
                </div>
                <div v-else>
                    <loading-card-widget> </loading-card-widget>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
    import LoadingCardWidget from './loading-card-widget';
    import LoadingIndicator from './loading-indicator.vue';
    import InlineGraph from './inline-graph.vue';
    import pagination from './mixins/pagination.vue';

    export default {

        data () {
            return {
                ids: [],
                animal_ids: [],
                initial: true
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
            itemsPerPage: {
                type: Number,
                default: 9,
                required: false
            },

            sourceFilter: {
                type: String,
                default: '',
                required: false
            },
            showFilters: {
                type: Boolean,
                default: false,
                required: false
            },
            temperatureGraphMin: {
                type: Number,
                default: 10,
                required: false
            },
            temperatureGraphMax: {
                type: Number,
                default: 40,
                required: false
            },
            humidityGraphMin: {
                type: Number,
                default: 1,
                required: false
            },
            humidityGraphMax: {
                type: Number,
                default: 99,
                required: false
            }
        },

        components: {
            pagination,
            'inline-graph': InlineGraph,
            'loading-indicator': LoadingIndicator,
            'loading-card-widget': LoadingCardWidget
        },

        computed: {
            terraria () {
                let that = this;
                return this.$store.state.terraria.filter(function(t) {
                    return that.ids.includes(t.id) && t.data !== null
                });
            },

            animals () {
                let that = this;
                return this.$store.state.animals.filter(function(a) {
                    return that.animal_ids.includes(a.id) && a.data !== null
                })
            },

            pagination () {
                return this.$refs.pagination;
            }
        },

        watch: {
            'terraria': function() {
                this.rerender();
            }
        },

        methods: {
            action_sequence_modal: function(terrarium_id, action) {
                $('#' + terrarium_id + '_' + action).modal('open');
            },

            submit: function(e) {
                window.submit_form(e);
            },

            load_data: function() {
                if (this.terrariumId === null) {
                    let that = this;

                    $.ajax({
                        url: '/api/v1/terraria/?with[]=animals&pagination[per_page]=' + that.itemsPerPage + '&page=' +
                                that.$refs.pagination.page +
                                that.$refs.pagination.filter_string +
                                that.$refs.pagination.order_string,
                        method: 'GET',
                        success: function (data) {
                            that.ids = data.data.map(t => t.id);
                            that.animal_ids = [].concat.apply([], data.data.map(p => p.animals.map(l => l.id)));
                            that.$refs.pagination.meta = data.meta;

                            that.$parent.ensureObjects('terraria', that.ids, data.data);
                            that.$parent.ensureObjects('animals', that.animal_ids, [].concat.apply([], data.data.map(p => p.animals)));
                        },
                        error: function (error) {
                            console.log(JSON.stringify(error));
                        }
                    });
                }
                else {
                    this.ids = [this.terrariumId];
                    this.$parent.ensureObject('terraria', this.terrariumId);
                }
            },

            rerender () {
                this.$nextTick(function() {
                    let grid = $('#' + this.containerId + '.masonry-grid');
                    if (grid.length > 0) {
                        grid.masonry('reloadItems');
                        grid.masonry('layout');
                    }
                    $('.modal').modal();
                    $('.tooltipped').tooltip({delay: 50});
                });
            },

            unsubscribe_all () {
                this.terraria.forEach((t) => t.unsubscribe());
                this.animals.forEach((a) => a.unsubscribe());
            }
        },

        created: function() {
            let that = this;
            setTimeout(function() {
                that.$refs.pagination.init();
            }, 100);
        }

    }
</script>