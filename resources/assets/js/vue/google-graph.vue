<template>
    <div>
        <div v-show="ShowFilterForm === true">
            <div class="row" style="margin-bottom: 0">
                <div class="input-field col s12 m4 l4">
                    <input class="datepicker" type="text" :placeholder="$t('labels.from')"
                           name="filter_from" :id="'filter_from_' + id" v-model="FilterFromDate">
                    <label :for="'filter_from_' + id">{{ $t('labels.from') }}</label>
                </div>
                <div class="input-field col s12 m4 l4">
                    <input class="datepicker" type="text" :placeholder="$t('labels.to')"
                           name="filter_to" :id="'filter_to_' + id" v-model="FilterToDate">
                    <label :for="'filter_to_' + id">{{ $t('labels.to') }}</label>
                </div>
                <div class="input-field col s12 m4 l4">
                    <button class="btn waves-effect waves-light" v-on:click="build">{{ $t('buttons.loadgraph') }}</button>
                </div>
            </div>
        </div>
        <div :id="'google_chart_' + id + '_loading'" class="center" style="display:none;">
            <div class="preloader-wrapper small active">
                <div class="spinner-layer spinner-green-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
                </div>
            </div>
        </div>
        <div :id="'google_chart_' + id"></div>

    </div>

</template>

<script>

export default {

    props: {
        id: {
            type: String,
            required: false,
            default: Math.floor(Math.random() * 1000000)
        },
        type: {
            type: String,
            default: 'line',
            required: false
        },
        eventType: {
            type: String,
            default: null,
            required: false
        },
        source: {
            type: String,
            required: true
        },
        HorizontalAxisTitle: {
            type: String,
            default: '',
            required: false
        },
        VerticalAxisTitle: {
            type: String,
            default: '',
            required: false
        },
        VerticalAxisGridlinesCount: {
            type: Number,
            default: 5,
            required: false
        },
        Height: {
            type: Number,
            default: 300,
            required: false
        },
        BackgroundColor: {
            type: String,
            default: '',
            required: false
        },
        FilterColumn: {
            type: String,
            default: null,
            required: true
        },
        ShowFilterForm: {
            type: Boolean,
            default: false,
            required: false
        },
        FilterFromDate: {
            type: String,
            default: (new Date((new Date).setFullYear((new Date).getFullYear() - 1))).toYmd(),
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
            data: []
        }
    },

    methods: {
        get_filter_from_date: function() {
            return this.FilterFromDate;
        },
        get_filter_to_date: function() {
            return this.FilterToDate + " 23:59:59";
        },
        init: function() {
            this.data = new google.visualization.DataTable();
            this.build();
        },
        build: function() {
            $('#google_chart_' + this.id + '_loading').show();
            var that = this;
            var url = this.source + '&filter[' + this.FilterColumn + ']=ge:' + this.get_filter_from_date() + ':and:le:' + this.get_filter_to_date();

            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    that.data.removeRows(0, that.data.getNumberOfRows())
                    that.data.removeColumns(0, that.data.getNumberOfColumns())

                    $.each(data.data.columns, function (item, col) {
                        that.data.addColumn(col.type, col.name);
                        if (col.type == 'date') { //parse dates
                            $.each(data.data.rows, function (ritem, r) {
                                data.data.rows[ritem][item] = new Date(r[item]);
                            });
                        }
                    });

                    that.data.addRows(data.data.rows);
                    that.chart = new google.visualization.LineChart(document.getElementById('google_chart_' + that.id));
                    that.draw();
                },
                error: function (error) {
                    $('#dygraph_' + this.id + '_loading').hide();
                    console.log(JSON.stringify(error));
                }
            });
        },
        draw: function() {
            this.options = {
                hAxis: {
                    title: this.HorizontalAxisTitle,
                    titleTextStyle: 'chartTextColor',
                    gridlines: {
                        count: this.VerticalAxisGridlinesCount,
                        color: '#666'
                    }
                },
                vAxis: {
                    title: this.VerticalAxisTitle,
                    titleTextStyle: 'chartTextColor',
                    gridlines: {
                        color: '#666'
                    }
                },
                annotations: {
                    textStyle: 'chartTextColor'
                },
                height: this.Height,
                width: '100%',
                backgroundColor: 'transparent',
                curveType: 'function',
                pointSize: 4,
                tooltip: {
                    isHtml: true
                }
            };

            if (this.chart) {
                this.chart.draw(this.data, this.options);
            }

            $('#google_chart_' + this.id + '_loading').hide();
        }
    },

    created: function() {
        window.eventHubVue.processStarted();

        google.charts.load('current', {packages: ['corechart', this.type]});
        google.charts.setOnLoadCallback(this.init);

        let that = this;
        this.$nextTick(function() {
            $('#filter_from_' + that.id).datepicker({
                format: 'yyyy-mm-dd',
                autoClose: true,
                defaultDate: new Date(that.FilterFromDate),
                setDefaultDate: true,
                onSelect: () => {
                    that.FilterFromDate = this.toYmd();
                }
            });
            $('#filter_to_' + that.id).datepicker({
                format: 'yyyy-mm-dd',
                autoClose: true,
                defaultDate: new Date(that.FilterToDate),
                setDefaultDate: true,
                onSelect: () => {
                    that.FilterToDate = this.toYmd();
                }
            });
        });

        window.eventHubVue.$on('ForceRerender', this.draw);
        if (this.eventType !== null) {
            window.echo.private('dashboard-updates')
                .listen(this.eventType, (e) => {
                    this.build();
                });
        }

        window.eventHubVue.processEnded();
    }
};
</script>
