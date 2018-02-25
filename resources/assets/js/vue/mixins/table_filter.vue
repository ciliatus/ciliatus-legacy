<template>
    <thead>
        <tr>
            <template v-for="filter in filters" v-if="hideCols.indexOf(filter.name) === -1">
                <th :data-field="filter.name" :class="filter.class">
                    <template v-if="!filter.noSort && $parent.$refs.pagination">
                        <a href="#!" v-on:click="set_order(filter.path)">
                            {{ $t('labels.' + filter.name) }}
                        </a>

                        <i v-if="$parent.$refs.pagination"
                           v-show="$parent.$refs.pagination.order.field === filter.path &&
                                   $parent.$refs.pagination.order.direction === 'asc'"
                           class="material-icons">arrow_drop_up</i>

                        <i v-if="$parent.$refs.pagination"
                           v-show="$parent.$refs.pagination.order.field === filter.path &&
                                   $parent.$refs.pagination.order.direction === 'desc'"
                           class="material-icons">arrow_drop_down</i>
                    </template>
                    <template v-else-if="filter.name">
                        {{ $t('labels.' + filter.name) }}
                    </template>

                    <template v-if="!filter.noFilter && $parent.$refs.pagination">
                        <div class="input-field inline" v-if="$parent.$refs.pagination">
                            <input :id="'filter_' + filter.name"
                                   type="text" v-model="$parent.$refs.pagination.filter[filter.path]"
                                   v-on:keyup.enter="set_filter">
                            <label :for="'filter_' + filter.name">Filter</label>
                        </div>
                    </template>
                </th>
            </template>
        </tr>
    </thead>
</template>

<script>
export default {

    data () {
        return {
            meta: [],
            applied_filter: {},
            filter_string: '',
        }
    },

    props: {
        filterFields: {
            type: Array,
            default: function(){return [];},
            required: false
        },
        cols: {
            type: Number,
            required: true
        },
        hideCols: {
            type: Array,
            default: function(){return [];},
            required: false
        }
    },

    computed: {
        filters () {
            let filters = [];
            for (let i = 0; i < this.cols; i++) {
                let filter = this.filterFields.filter(f => f.col === i);
                filters.push(
                    filter.length > 0 ? filter[0] : {name: '', class: '', noFilter: true, col: i}
                )
            }

            return filters;
        }
    },

    methods: {

        toggle_filters: function() {
            this.showFilters = !this.showFilters;
        },

        set_order: function(field) {
            this.$parent.$refs.pagination.set_order(field);
        },

        set_filter: function() {
            this.$parent.$refs.pagination.set_filter();
        }

    }

};
</script>