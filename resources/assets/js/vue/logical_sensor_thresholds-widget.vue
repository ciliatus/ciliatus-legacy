<template>
    <div :class="wrapperClasses">
        <div class="card">

            <div class="card-header">
                <i class="material-icons">vertical_align_center</i>
                {{ $tc("labels.logical_sensor_thresholds", 2) }}
            </div>

            <template v-for="logical_sensor in logical_sensors">
                <template v-if="logical_sensor.data">
                    <div class="card-content">
                        <div class="card-sub-header">
                            <a v-bind:href="'/logical_sensors/' + logical_sensor.data.id"><strong>{{ logical_sensor.data.name }}</strong></a>
                        </div>

                        <template v-if="(threshold_list = thresholds.filter(t => t.data.logical_sensor_id === logical_sensor.data.id)).length > 0">
                            <div v-for="threshold in threshold_list" class="row row-no-margin">
                                <i class="material-icons">vertical_align_center</i>

                                <span>{{ threshold.data.timestamps.starts }}:</span>
                                <span v-if="threshold.data.rawvalue_lowerlimit !== null">
                                    {{ $t("labels.min_short") }}: {{ threshold.data.rawvalue_lowerlimit }}
                                </span>
                                <span v-if="threshold.data.rawvalue_upperlimit !== null">
                                    {{ $t("labels.max_short") }}: {{ threshold.data.rawvalue_upperlimit }}
                                </span>

                                <span class="right">
                                    <a :href="'/logical_sensor_thresholds/' + threshold.data.id + '/edit'">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </span>
                            </div>
                        </template>
                        <div class="row row-no-margin" v-else>
                            {{ $t("tooltips.no_data") }}
                        </div>

                    </div>

                    <div class="card-action">
                        <a :href="'/logical_sensor_thresholds/create?preset[belongsTo_type]=LogicalSensor&preset[belongsTo_id]=' + logical_sensor.data.id">
                            {{ $t("buttons.add") }}
                        </a>
                    </div>
                </template>
            </template>

        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                ids: [],
                threshold_ids: [],
                meta: []
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
                default: function () {
                    return [];
                },
                required: false
            },
            itemsPerPage: {
                type: Number,
                default: 9,
                required: false
            }
        },

        computed: {
            logical_sensors () {
                let that = this;
                return this.$store.state.logical_sensors.filter(function (l) {
                    return that.ids.includes(l.id) && l.data !== null
                });
            },

            thresholds () {
                let that = this;
                return this.$store.state.logical_sensor_thresholds.filter(function (l) {
                    return that.threshold_ids.includes(l.id) && l.data !== null
                });
            },
        },

        methods: {
            load_data: function () {
                let that = this;

                $.ajax({
                    url: '/api/v1/logical_sensors/?with[]=thresholds&all=true&' + that.sourceFilter,
                    method: 'GET',
                    success: function (data) {
                        that.ids = data.data.map(l => l.id);
                        that.threshold_ids = [].concat.apply([], data.data.map(l => l.thresholds.map(t => t.id)));

                        that.meta = data.meta;

                        that.$parent.ensureObjects('logical_sensors', that.ids);
                        that.$parent.ensureObjects('logical_sensor_thresholds', that.threshold_ids, [].concat.apply([], data.data.map(l => l.thresholds)));
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            }
        },

        created: function () {
            let that = this;
            setTimeout(function () {
                that.load_data();
            }, 100);
        }
    }
</script>