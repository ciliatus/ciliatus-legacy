<template>

    <div>

        <pagination ref="pagination"
                    v-show="roomId === null"
                    :source-filter="sourceFilter"
                    :show-filters="showFilters"
                    :filter-fields="[{name: 'display_name', path: 'display_name'}]">
        </pagination>

        <div :class="containerClasses" :id="containerId">
            <div :class="wrapperClasses" v-for="room in rooms">
                <div class="card" v-if="room.data">
                    <div class="card-image room-card-image"
                         v-bind:style="room.data.default_background_filepath ?
                                        'background-image: url(\'' + room.data.default_background_filepath + '\');' :
                                        'background-image: url(\'/svg/Ciliatus_Logo.svg\'); background-position: top center;'">
                        <div>
                            <inline-graph v-if="room.data"
                                          :points="room.data.humidity_history"
                                          :options="{'fill': null, 'strokeWidth': '2', 'stroke': '#2196f3',
                                                    width: '100%', height:'140px',
                                                    min: humidityGraphMin, max: humidityGraphMax}"
                                          source-type="rooms"
                                          :source-id="room.id"
                                          source-field="humidity_history"
                                          type="line">
                            </inline-graph>
                        </div>

                        <div style="position: relative; top: -145px">
                            <inline-graph v-if="room.data"
                                          :points="room.data.temperature_history"
                                          :options="{'fill': null, 'strokeWidth': '2', 'stroke': '#b71c1c',
                                                    width: '100%', height:'140px',
                                                    min: temperatureGraphMin, max: temperatureGraphMax}"
                                          source-type="rooms"
                                          :source-id="room.id"
                                          source-field="temperature_history"
                                          type="line">
                            </inline-graph>
                        </div>

                        <div class="card-title">
                            <span><a :href="'/rooms/' + room.data.id">{{ room.data.display_name }}</a></span>
                            <loading-indicator :size="20" v-show="room.refreshing"> </loading-indicator>
                        </div>
                    </div>

                    <div class="card-content">

                        <div class="ellipsis card-content-row">
                            {{ $t("labels.temperature") }}:
                            <template v-if="room.data.cooked_temperature_celsius !== null"
                                 v-bind:class="{ 'red-text': room.data.temperature_critical, 'darken-3': room.data.temperature_critical }">

                                <template v-if="room.data.cooked_temperature_celsius_age_minutes === null ||
                                                room.data.cooked_temperature_celsius_age_minutes > 60">
                                    <span class="muted">
                                        {{ $t('messages.cards.data_too_old') }}
                                    </span>
                                </template>
                                    <template v-else>
                                    {{ room.data.cooked_temperature_celsius }}°C

                                    <i v-if="room.data.heartbeat_critical"
                                       class="mdi mdi-sync-off deep-orange-text tooltipped"
                                       data-delay="50" data-html="true"
                                       :data-tooltip="'<div style=\'max-width: 300px\'>' + $t('tooltips.heartbeat_critical') + '</div>'"></i>
                                </template>

                            </template>
                            <template v-else>
                                <span class="muted">
                                    {{ $t('messages.cards.no_temperature') }}
                                </span>
                            </template>
                        </div>

                        <div class="ellipsis card-content-row">
                            {{ $t("labels.humidity") }}:
                            <template v-if="room.data.cooked_humidity_percent !== null"
                                 v-bind:class="{ 'red-text': room.data.humidity_critical, 'darken-3': room.data.humidity_critical }">

                                <template v-if="room.data.cooked_humidity_percent_age_minutes === null ||
                                                room.data.cooked_humidity_percent_age_minutes > 60">
                                    <span class="muted">
                                        {{ $t('messages.cards.data_too_old') }}
                                    </span>
                                </template>
                                <template v-else>
                                    {{ room.data.cooked_humidity_percent }}%

                                    <i v-if="room.data.heartbeat_critical"
                                       class="mdi mdi-sync-off deep-orange-text tooltipped"
                                       data-delay="50" data-html="true"
                                       :data-tooltip="'<div style=\'max-width: 300px\'>' + $t('tooltips.heartbeat_critical') + '</div>'"></i>
                                </template>

                            </template>
                            <template v-else>
                                <span class="muted">
                                    {{ $t('messages.cards.no_humidity') }}
                                </span>
                            </template>
                        </div>
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
                initial: true
            }
        },

        props: {
            refreshTimeoutSeconds: {
                type: Number,
                default: null,
                required: false
            },
            roomId: {
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
                default: 'rooms-grid',
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
            rooms () {
                let that = this;
                return this.$store.state.rooms.filter(function(t) {
                    return that.ids.includes(t.id) && t.data !== null
                });
            },

            pagination () {
                return this.$refs.pagination;
            }
        },

        watch: {
            'rooms': function() {
                this.rerender();
            }
        },

        methods: {
            submit: function(e) {
                window.submit_form(e);
            },

            load_data: function() {
                if (this.roomId === null) {
                    let that = this;

                    $.ajax({
                        url: '/api/v1/rooms/?pagination[per_page]=' + that.itemsPerPage + '&page=' +
                                that.$refs.pagination.page +
                                that.$refs.pagination.filter_string +
                                that.$refs.pagination.order_string,
                        method: 'GET',
                        success: function (data) {
                            that.ids = data.data.map(t => t.id);
                            that.$refs.pagination.meta = data.meta;

                            that.$parent.ensureObjects('rooms', that.ids, data.data);
                        },
                        error: function (error) {
                            console.log(JSON.stringify(error));
                        }
                    });
                }
                else {
                    this.ids = [this.roomId];
                    this.$parent.ensureObject('rooms', this.roomId);
                }
            },

            rerender () {
                this.$nextTick(function() {
                    $('.tooltipped').tooltip({delay: 50});
                });
            },

            unsubscribe_all () {
                this.rooms.forEach((t) => t.unsubscribe());
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