<template>
    <span>
        <peity :type="type" :options="options" :data="points_string"> </peity>
    </span>
</template>

<script>

import Peity from 'vue-peity'

export default {

    data () {
        return {
            points_array: []
        }
    },

    props: ['points', 'type', 'options', 'sourceField', 'sourceType', 'sourceId'],

    components: {
        Peity
    },

    computed: {
        points_string () {
            if (this.points_array) {
                return this.points_array.map(p => Math.round(p*10) / 10).toString();
            }

            return [];
        }
    },

    methods: {
        rerender () {
            let tmp = this.points_array;
            this.points_array = [0];

            this.$nextTick(function() {
                this.points_array = tmp;
            });
        }
    },

    created () {
        this.points_array = this.points;

        window.eventHubVue.$on('CiliatusObjectUpdated', (obj) => {
            if (obj.id === this.sourceId && obj.type === this.sourceType) {
                this.points_array = obj.data[this.sourceField];
                this.rerender();
            }
        });
    }
};
</script>
