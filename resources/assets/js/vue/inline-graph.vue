<template>
    <span>
        <peity :type="type" :options="options" :data="graphData"></peity>
    </span>
</template>

<script>

import Peity from 'vue-peity'

export default {

    components: {
        Peity
    },

    props: ['source', 'type', 'options', 'parentid', 'graphtype', 'dataPrefill'],

    computed: {
        graphData: function() {
            if (this.data === undefined)
                return '';

            return this.data.toString()
        }
    },

    data () {
        return {
            data: []
        }
    },

    methods: {
        createSensorrreading: function(value) {
            this.data.push(value);
        },
        updateTerrariumGraph: function(t) {
            if (t.id === this.parentid) {
                if (this.graphtype === 'humidity_percent')
                    this.data = t.humidity_history;
                else if (this.graphtype === 'temperature_celsius')
                    this.data = t.temperature_history;
            }
        },
        rerender: function() {
            var tmp = this.data;
            this.data = [0];

            this.$nextTick(function() {
                this.data = tmp;
            });
        }
    },

    created: function() {
        if (this.dataPrefill != null) {
            this.data = this.dataPrefill;
        }
        else {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: that.source,
                method: 'GET',
                success: function (data) {
                    that.data = data.data;
                    window.eventHubVue.processEnded();
                },
                error: function (error) {
                    console.log(JSON.stringify(error));
                    window.eventHubVue.processEnded();
                }
            });
        }

        window.eventHubVue.$on('ForceRerender', this.rerender);
        window.eventHubVue.$on('SensorreadingCreated', this.createSensorrreading);
        window.eventHubVue.$on('TerrariumGraphUpdated', this.updateTerrariumGraph);
    }
};
</script>
