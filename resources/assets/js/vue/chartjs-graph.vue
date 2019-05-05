<template>
    <div>
        <div v-if="ShowFilterForm === true">
            <div class="row" style="margin-bottom: 0">
                <div class="input-field col s12 m4 l4">
                    <input class="datepicker" type="text" :placeholder="$t('labels.from')" name="filter_from" :id="'filter_from_' + id"
                           :data-default="FilterFromDate" :value="FilterFromDate">
                    <label :for="'filter_from_' + id">{{ $t('labels.from') }}</label>
                </div>
                <div class="input-field col s12 m4 l4">
                    <input class="datepicker" type="text" :placeholder="$t('labels.to')" name="filter_to" :id="'filter_to_' + id"
                           :data-default="FilterToDate" :value="FilterToDate">
                    <label :for="'filter_to_' + id">{{ $t('labels.to') }}</label>
                </div>
                <div class="input-field col s12 m4 l4">
                    <button class="btn waves-effect waves-light" v-on:click="build">{{ $t('buttons.loadgraph') }}</button>
                </div>
            </div>
        </div>
        <div :id="'chartjs_' + id + '_loading'" class="center" style="display:none;">
            <loading-indicator :size="100"></loading-indicator>
        </div>
        <canvas :id="'chartjs_' + id" style="width: 100%;"></canvas>

    </div>

</template>

<script>
import LoadingIndicator from './loading-indicator.vue';

export default {

    props: {
        id: {
            type: Number,
            required: false,
            default: Math.floor(Math.random() * 1000000)
        },
        source: {
            type: String,
            required: true
        },
        FilterColumn: {
            type: String,
            default: 'created_at',
            required: false
        },
        ShowFilterForm: {
            type: Boolean,
            default: false,
            required: false
        },
        FilterFromDate: {
            type: String,
            default: (new Date((new Date).setDate((new Date).getDate() - 2))).toYmd(),
            required: false
        },
        FilterToDate: {
            type: String,
            default: (new Date).toYmd(),
            required: false
        }
    },

    data () {
        return {
            chart: null,
            options: {},
            data: null
        }
    },

    components: {
        'loading-indicator': LoadingIndicator
    },

    methods: {
        get_filter_from_date: function() {
            if ($('#filter_from_' + this.id).val() == undefined) {
                return this.FilterFromDate;
            }

            return $('#filter_from_' + this.id).val();
        },
        get_filter_to_date: function() {
            if ($('#filter_to_' + this.id).val() == undefined) {
                return this.FilterToDate + " 23:59:59";
            }

            return $('#filter_to_' + this.id).val() + " 23:59:59";
        },

        init: function() {
            this.build();
        },
        build: function() {
            $('#chartjs_' + this.id + '_loading').show();
            var that = this;
            var url = this.source + '?filter[' + this.FilterColumn + ']=ge:' + this.get_filter_from_date() +
                                    ':and:le:' + this.get_filter_to_date() + '&filter[is_anomaly]=0';

            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    that.data = [];
                    $.each(data.data, function (srg) {
                        var group = data.data[srg];
                        var group_data = group.map(function (sr) { return {y: sr.value, x: sr.created_at } });
                        that.data.push({
                            label: group[0].logical_sensor_name,
                            data: group_data
                        });
                    });

                    that.draw();
                },
                error: function (error) {
                    $('#chartjs_' + that.id + '_loading').hide();
                    console.log(JSON.stringify(error));
                }
            });
        },
        draw: function() {
            if (this.data === null) {
                return;
            }

            var that = this;
            var c = new Chart(document.getElementById('chartjs_' + this.id), {
                type: 'line',
                data: {
                    datasets: that.data
                },
                options: {
                    scales: {
                        xAxes: [{
                            type: 'time'
                        }]
                    }
                }
            });

            $('#chartjs_' + that.id + '_loading').hide();
        }
    },

    created: function() {
        window.eventHubVue.processStarted();

        window.eventHubVue.$on('ForceRerender', this.draw);

        var that = this;
        this.$nextTick(function() {
            $('#filter_from_' + that.id).datepicker({
                format: 'yyyy-mm-dd',
                autoClose: true,
                defaultDate: new Date(),
                setDefaultDate: true
            });

            that.build();
        });

        window.eventHubVue.processEnded();
    }
};
</script>
