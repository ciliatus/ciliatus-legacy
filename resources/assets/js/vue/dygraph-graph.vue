<template>
    <div>
        <div v-if="ShowFilterForm === true">
            <div class="row" style="margin-bottom: 0">
                <div class="input-field col s12 m4 l4">
                    <input class="datepicker" type="date" :placeholder="$t('labels.from')" name="filter_from" :id="'filter_from_' + id"
                           :data-default="FilterFromDate" :value="FilterFromDate">
                    <label :for="'filter_from_' + id">{{ $t('labels.from') }}</label>
                </div>
                <div class="input-field col s12 m4 l4">
                    <input class="datepicker" type="date" :placeholder="$t('labels.to')" name="filter_to" :id="'filter_to_' + id"
                           :data-default="FilterToDate" :value="FilterToDate">
                    <label :for="'filter_to_' + id">{{ $t('labels.to') }}</label>
                </div>
                <div class="input-field col s12 m4 l4">
                    <button class="btn waves-effect waves-light" v-on:click="build">{{ $t('buttons.loadgraph') }}</button>
                </div>
            </div>
        </div>
        <div :id="'dygraph_' + id + '_loading'" class="center" style="display:none;">
            <loading-indicator :size="100"></loading-indicator>
        </div>
        <div :id="'dygraph_' + id" style="width: 100%;"></div>

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
            $('#dygraph_' + this.id + '_loading').show();
            var that = this;
            var url = this.source + '?csv=true&filter[' + this.FilterColumn + ']=ge:' + this.get_filter_from_date() +
                ':and:le:' + this.get_filter_to_date() + '&filter[is_anomaly]=0';

            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    that.data = data.data;
                    if (that.data.split(/\r\n|\r|\n/).length > 1) {
                        that.draw();
                    }
                    else {
                        $('#dygraph_' + that.id + '_loading').hide();
                    }
                },
                error: function (error) {
                    $('#dygraph_' + that.id + '_loading').hide();
                    console.log(JSON.stringify(error));
                }
            });
        },
        draw: function() {
            if (this.data === null) {
                return;
            }

            var that = this;
            var g = new Dygraph(
                document.getElementById('dygraph_' + this.id),
                this.data,
                {
                    'connectSeparatedPoints': true,
                    rollPeriod: 4,
                    showRoller: true,
                    showRangeSelector: true,
                    legend: 'always',
                    colors: ['#2196f3', '#CC5555'],
                    axisLineColor: '#D4D4D4'
                }
            );
            g.ready(function() {
                $('#dygraph_' + that.id + '_loading').hide();
            });
        }
    },

    created: function() {
        window.eventHubVue.processStarted();

        window.eventHubVue.$on('ForceRerender', this.draw);

        var that = this;
        this.$nextTick(function() {
            $('.datepicker').pickadate({
                format: 'yyyy-mm-dd',
            });

            that.build();
        });

        window.eventHubVue.processEnded();
    }
};
</script>
