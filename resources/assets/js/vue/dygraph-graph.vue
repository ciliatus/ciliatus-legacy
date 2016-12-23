<template>
    <div>
        <div v-if="ShowFilterField !== null">
            <div class="row" style="margin-bottom: 0">
                <div class="input-field col s12 m4 l4">
                    <input class="datepicker" type="date" :placeholder="$t('labels.from')" name="filter_from" :id="'filter_from_' + id"
                           :data-default="new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-01'"
                           :value="new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-01'">
                    <label :for="'filter_from_' + id">{{ $t('labels.from') }}</label>
                </div>
                <div class="input-field col s12 m4 l4">
                    <input class="datepicker" type="date" :placeholder="$t('labels.to')" name="filter_to" :id="'filter_to_' + id"
                           :data-default="new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getDate()"
                           :value="new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getDate()">
                    <label :for="'filter_to_' + id">{{ $t('labels.to') }}</label>
                </div>
                <div class="input-field col s12 m4 l4">
                    <button class="btn waves-effect waves-light" v-on:click="build">{{ $t('buttons.next') }}</button>
                </div>
            </div>
        </div>
        <div :id="'dygraph_' + id + '_loading'" class="center" style="display:none;">
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
        <div :id="'dygraph_' + id" style="width: 100%;"></div>

    </div>

</template>

<script>

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
        ShowFilterField: {
            type: String,
            default: null,
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

    methods: {
        filterFrom: function() {
            if (this.ShowFilterField === null) {
                return null;
            }
            if ($('#filter_from_' + this.id).val() == '') {
                return null;
            }
            else {
                $('#filter_from_' + this.id).val()
            }
        },
        filterTo: function() {
            if (this.ShowFilterField === null) {
                return null;
            }
            if ($('#filter_to_' + this.id).val() == '') {
                return null;
            }
            else {
                $('#filter_to_' + this.id).val()
            }
        },

        init: function() {
            this.build();
        },
        build: function() {
            $('#dygraph_' + this.id + '_loading').show();
            var that = this;
            var url = this.source;
            if (this.filterFrom !== null) {
                url = url + '&filter[' + this.ShowFilterField + ']=gt:' + $('#filter_from_' + this.id).val();
            }
            if (this.filterTo !== null && this.filterFrom !== null) {
                url = url + ':and:lt:' + $('#filter_to_' + this.id).val();
            }
            else if (this.filterTo !== null) {
                url = url + '&filter[' + this.ShowFilterField + ']=lt:' + $('#filter_to_' + this.id).val();
            }
            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    that.data = data.data.csv;
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
                    colors: ['#5555EE', '#CC5555'],
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

        this.$nextTick(function() {
            $('.datepicker').pickadate({
                format: 'yyyy-mm-dd',
            });
        });

        window.eventHubVue.processEnded();
    }
};
</script>
