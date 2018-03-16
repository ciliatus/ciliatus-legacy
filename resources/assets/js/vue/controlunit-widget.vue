<template>
    <div :class="wrapperClasses">
        <div class="card" v-if="controlunit.data">
            <div class="card-header">
                <i class="mdi mdi-18px mdi-water-pump"></i>
                {{ $tc("labels.controlunits", 1) }}
            </div>

            <div class="card-content">
                <span class="card-title activator">
                    {{ controlunit.data.name }}
                </span>

                <div>
                    <div v-if="!controlunit.data.active">
                        <strong>{{ $t('labels.inactive') }}</strong>
                    </div>
                    <div>
                        {{ $t('labels.last_heartbeat') }}:
                        <!-- @TODO: there has to be a better way to do this -->
                        <span v-show="controlunit.data.timestamps.last_heartbeat_diff.days > 0"
                              class="tooltipped" data-position="bottom" data-delay="50"
                              v-bind:data-tooltip="controlunit.data.timestamps.last_heartbeat">
                            {{ $t('units.days_ago', {val: controlunit.data.timestamps.last_heartbeat_diff.days}) }}
                        </span>

                        <span v-show="controlunit.data.timestamps.last_heartbeat_diff.days < 1 &&
                                      controlunit.data.timestamps.last_heartbeat_diff.hours > 0"
                              class="tooltipped" data-position="bottom" data-delay="50"
                              v-bind:data-tooltip="controlunit.data.timestamps.last_heartbeat">
                            {{ $t('units.hours_ago', {val: controlunit.data.timestamps.last_heartbeat_diff.hours}) }}
                        </span>

                        <span v-show="controlunit.data.timestamps.last_heartbeat_diff.days < 1 &&
                                      controlunit.data.timestamps.last_heartbeat_diff.hours < 1 &&
                                      controlunit.data.timestamps.last_heartbeat_diff.minutes > 1"
                              class="tooltipped" data-position="bottom" data-delay="50"
                              v-bind:data-tooltip="controlunit.data.timestamps.last_heartbeat">
                            {{ $t('units.minutes_ago', {val: controlunit.data.timestamps.last_heartbeat_diff.minutes}) }}
                        </span>

                        <span v-show="controlunit.data.timestamps.last_heartbeat_diff.days < 1 &&
                                      controlunit.data.timestamps.last_heartbeat_diff.hours < 1 &&
                                      controlunit.data.timestamps.last_heartbeat_diff.minutes < 2"
                              class="tooltipped" data-position="bottom" data-delay="50"
                              v-bind:data-tooltip="controlunit.data.timestamps.last_heartbeat">
                            {{ $t('units.just_now') }}
                        </span>
                    </div>
                    <div>
                        {{ $t('labels.client_server_time_diff') }}: {{ controlunit.data.client_server_time_diff_seconds }}s
                    </div>
                    <div v-if="controlunit.data.software_version">
                        {{ $t('labels.software_version') }}: {{ controlunit.data.software_version }}
                    </div>
                </div>
            </div>

            <div class="card-action">
                <a v-bind:href="'/controlunits/' + controlunit.data.id">{{ $t("buttons.details") }}</a>
                <a v-bind:href="'/controlunits/' + controlunit.data.id + '/edit'">{{ $t("buttons.edit") }}</a>
            </div>
        </div>
        <div v-else>
            <loading-card-widget> </loading-card-widget>
        </div>
    </div>
</template>

<script>
    import LoadingCardWidget from './loading-card-widget';

    export default {
        props: {
            controlunitId: {
                type: String,
                default: '',
                required: false
            },
            wrapperClasses: {
                type: String,
                default: '',
                required: false
            }
        },

        components: {
            'loading-card-widget': LoadingCardWidget
        },

        computed: {
            controlunit () {
                return this.$store.state.controlunits.filter(v => v.id = this.controlunitId)[0];
            }
        },

        methods: {
            load_data: function() {
                this.$parent.ensureObject('controlunits', this.controlunitId);
            }
        },

        created: function() {
            this.load_data();
        }
    }
</script>