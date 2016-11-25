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

    props: ['source', 'type', 'options', 'parentid', 'graphtype'],

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
            if (t.terrarium.id == this.parentid) {
                if (this.graphtype == 'humidity_percent')
                    this.data = t.terrarium.humidity_history;
                else if (this.graphtype == 'temperature_celsius')
                    this.data = t.terrarium.temperature_history;
            }
        }
    },

    created: function() {
        /*$.getJSON(this.source, function(history) {
            this.data = history.data;
        }.bind(this));*/

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
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });

        window.eventHubVue.$on('SensorreadingCreated', this.createSensorrreading);
        window.eventHubVue.$on('TerrariumGraphUpdated', this.updateTerrariumGraph);
    }
};
</script>
