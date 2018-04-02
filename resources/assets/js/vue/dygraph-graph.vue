<template>
    <div>
        <div v-if="ShowFilterForm === true">
            <div class="row" style="margin-bottom: 0">
                <div class="input-field col s12 m6 l4">
                    <input class="datepicker" type="date" :placeholder="$t('labels.from')" name="filter_from" :id="'filter_from_' + id"
                           :data-default="FilterFromDate" :value="FilterFromDate">
                    <label :for="'filter_from_' + id">{{ $t('labels.from') }}</label>
                </div>
                <div class="input-field col s12 m6 l4">
                    <input class="datepicker" type="date" :placeholder="$t('labels.to')" name="filter_to" :id="'filter_to_' + id"
                           :data-default="FilterToDate" :value="FilterToDate">
                    <label :for="'filter_to_' + id">{{ $t('labels.to') }}</label>
                </div>
                <div class="input-field col s8 m8 l2">
                    <button class="btn waves-effect waves-light" v-on:click="build"><i class="mdi mdi-18px mdi-refresh"></i></button>
                </div>
                <div class="input-field col s4 m4 l2">
                    <input type="text" v-on:keyup.enter="set_rollperiod" :id="'dygraph_' + id + '_rollperiodselector'"
                           :value="5" :placeholder="$t('labels.rollperiod')">
                    <label>{{ $t('labels.rollperiod') }}</label>
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
        },
        ColumnIdField: {
            type: String,
            required: true
        },
        ColumnNameField: {
            type: String,
            required: true
        },
        LabelsDivId: {
            type: String,
            default: null,
            required: false
        },
        TimeAxisLabel: {
            type: String,
            default: 'created_at',
            required: false
        }
    },

    data () {
        return {
            chart: null,
            options: {},
            data: [],
            columns: [],
            colors: [],
            graph: null,
            debug: null
        }
    },

    components: {
        'loading-indicator': LoadingIndicator
    },

    methods: {
        set_rollperiod: function(e) {
            this.graph.adjustRoll(parseInt($('#dygraph_' + this.id + '_rollperiodselector').val()));
        },
        get_filter_from_date: function() {
            if ($('#filter_from_' + this.id).val() == undefined) {
                return this.FilterFromDate;
            }

            return "{{b64(" + $('#filter_from_' + this.id).val().base64encode() + ")}}";
        },
        get_filter_to_date: function() {
            if ($('#filter_to_' + this.id).val() == undefined) {
                return this.FilterToDate + " 23:59:59";
            }

            return "{{b64(" + ($('#filter_to_' + this.id).val() + " 23:59:59").base64encode() + ")}}";
        },

        init: function() {
            this.build();
        },
        build: function() {
            $('#dygraph_' + this.id + '_loading').show();
            var that = this;
            var url = this.source + '?filter[' + this.FilterColumn + ']=ge:' + this.get_filter_from_date() +
                ':and:le:' + this.get_filter_to_date() + '&filter[is_anomaly]=0&order[' + this.FilterColumn + ']=desc';

            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    var groupBy = function(xs, key) {
                        return xs.reduce(function(rv, x) {
                            (rv[x[key]] = rv[x[key]] || []).push(x);
                            return rv;
                        }, {});
                    };
                    var _columns = Object.values(groupBy(data.data, that.ColumnIdField));

                    var color_repo = {
                        'temperature_celsius': ['#f44336', '#c62828', '#b71c1c'],
                        'humidity_percent': ['#2196f3', '#1565c0', '#0d47a1'],
                        'default': ['#ff9800', '#009688', '#03a9f4', '#e91e63', '#9c27b0']
                    };
                    var color_repo_used = {
                        'temperature_celsius': 0,
                        'humidity_percent': 0,
                        'default': 0
                    };

                    that.columns = [that.FilterColumn];
                    that.colors = [];
                    _columns.forEach(function (c) {
                        var repo_name = '';
                        if (color_repo[c[0]['value_type']] !== undefined) {
                            repo_name = c[0]['value_type'];
                        }
                        else {
                            repo_name = 'default';
                        }
                        var color = '';
                        var color_num = color_repo_used[repo_name];
                        if (color_repo[repo_name].length <= color_num) {
                            color_num = color_repo_used.default;
                            console.log('Out of colors for ' + repo_name);
                            repo_name = 'default';
                            if (color_repo.default.length <= color_repo_used.default) {
                                console.log('Default color is used up. Falling back to black');
                                color = '#000000';
                            }
                            else {
                                color = color_repo.default[color_num];
                            }
                        }
                        else {
                            color = color_repo[repo_name][color_num];
                        }
                        that.colors.push(color);
                        color_repo_used[repo_name] += 1;

                        that.columns.push(c[0][that.ColumnNameField])
                    });

                    var rows = [];
                    var i = 0;
                    _columns.forEach(function (c) {
                        c.forEach(function(d) {
                            var series = [];
                            series.push(new Date(d[that.FilterColumn]));
                            for (var j = 0; j < _columns.length; j++) {
                                if (i === j) {
                                    series.push(d.value);
                                }
                                else {
                                    series.push(null);
                                }
                            }
                            rows.push(series);
                        });

                        i += 1;
                    });

                    rows.sort(function(a, b) {
                        if (a[0] > b[0]) {
                            return 1;
                        }

                        if (a[0] < b[0]) {
                            return -1;
                        }

                        return 0;
                    });

                    rows.map(function(r, i) {
                        r.map(function (c, j) {
                            if (c === null) {
                                if (i > 0) {
                                    return rows[i-1][j];
                                }
                            }
                            return c;
                        });
                    });

                    that.data = rows;

                    that.draw();
                },
                error: function (error) {
                    $('#dygraph_' + that.id + '_loading').hide();
                    console.log(JSON.stringify(error));
                }
            });
        },
        draw: function() {
            if (this.data === null || this.data.length < 1) {
                $('#dygraph_' + this.id + '_loading').hide();
                return;
            }

            this.options = {
                connectSeparatedPoints: true,
                strokeWidth: 1.5,
                rollPeriod: 4,
                showRoller: false,
                showRangeSelector: true,
                rangeSelectorPlotFillGradientColor: '#ffcc80',
                rangeSelectorPlotFillColor: '#ffcc80',
                rangeSelectorPlotLineWidth: 1,
                rangeSelectorPlotStrokeColor: '#ff6d00',
                rangeSelectorBackgroundStrokeColor: '#757575',
                rangeSelectorBackgroundStrokeWidth: 1,
                legend: 'always',
                colors: this.colors,
                axisLineColor: '#D4D4D4',
                labels: this.columns,
                legendFormatter: function(data) {
                    if (data.x == null) {
                        // This happens when there's no selection and {legend: 'always'} is set.
                        return '<br>' + data.series.map(function(series) {
                            return series.dashHTML + ' ' + series.labelHTML
                        }).join('<br>');
                    }

                    //var html = this.getLabels()[0] + ': ' + data.xHTML;
                    var html = that.TimeAxisLabel + ': ' + data.xHTML;
                    data.series.forEach(function(series) {
                        if (!series.isVisible) {
                            return;
                        }
                        var yData = series.yHTML === undefined ? '' : series.yHTML;
                        var labeledData = series.labelHTML + ': ' + yData;
                        if (series.isHighlighted) {
                            labeledData = '<b>' + labeledData + '</b>';
                        }
                        html += '<br>' + series.dashHTML + ' ' + labeledData;
                    });
                    return html;
                },
                axes: {
                    x: {
                        axisLabelFormatter: function (d) {
                            return d.toLocaleDateString();
                        },
                        valueFormatter: function (ms) {
                            return new Date(ms).toLocaleString();
                        }
                    }
                }
            };

            if (this.LabelsDivId !== null) {
                this.options.labelsDiv = this.LabelsDivId;
                this.options.labelsSeparateLines = true;
            }

            var that = this;

            try {
                this.graph = new Dygraph(
                    document.getElementById('dygraph_' + this.id),
                    this.data,
                    this.options
                );

                this.graph.ready(function() {
                    $('#dygraph_' + that.id + '_loading').hide();
                });
            }
            catch (ex) {
                $('#dygraph_' + that.id + '_loading').hide();
                console.log(ex);
            }

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
