<template>
    <div>

        <div class="row">
            <div class="col s10">
                <ul class="pagination" v-if="meta.hasOwnProperty('pagination')">
                    <li v-bind:class="['hide-on-small-only', { 'disabled': meta.pagination.current_page == 1, 'waves-effect': meta.pagination.current_page != 1 }]">
                        <a href="#!" v-on:click="set_page(1)"><i class="material-icons">first_page</i></a>
                    </li>
                    <li v-bind:class="{ 'disabled': meta.pagination.current_page == 1, 'waves-effect': meta.pagination.current_page != 1 }">
                        <a href="#!" v-on:click="set_page(meta.pagination.current_page-1)"><i class="material-icons">chevron_left</i></a>
                    </li>

                    <li v-if="meta.pagination.current_page-3 > 0" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page-3)">{{ meta.pagination.current_page-3 }}</a></li>
                    <li v-if="meta.pagination.current_page-2 > 0" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page-2)">{{ meta.pagination.current_page-2 }}</a></li>
                    <li v-if="meta.pagination.current_page-1 > 0" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page-1)">{{ meta.pagination.current_page-1 }}</a></li>

                    <li class="active"><a href="#!">{{ meta.pagination.current_page }}</a></li>

                    <li v-if="meta.pagination.current_page+1 <= meta.pagination.total_pages" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page+1)">{{ meta.pagination.current_page+1 }}</a></li>
                    <li v-if="meta.pagination.current_page+2 <= meta.pagination.total_pages" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page+2)">{{ meta.pagination.current_page+2 }}</a></li>
                    <li v-if="meta.pagination.current_page+3 <= meta.pagination.total_pages" class="waves-effect"><a href="#!" v-on:click="set_page(meta.pagination.current_page+3)">{{ meta.pagination.current_page+3 }}</a></li>

                    <li v-bind:class="{ 'disabled': meta.pagination.current_page == meta.pagination.total_pages, 'waves-effect': meta.pagination.current_page != meta.pagination.total_pages }">
                        <a href="#!" v-on:click="set_page(meta.pagination.current_page+1)"><i class="material-icons">chevron_right</i></a>
                    </li>
                    <li v-bind:class="['hide-on-small-only', { 'disabled': meta.pagination.current_page == meta.pagination.total_pages, 'waves-effect': meta.pagination.current_page != meta.pagination.total_pages }]">
                        <a href="#!" v-on:click="set_page(meta.pagination.total_pages)"><i class="material-icons">last_page</i></a>
                    </li>
                </ul>
            </div>
            <div class="col s2 right-align">
                <ul class="pagination">
                    <li class="waves-effect">
                        <a href="#!"><i class="material-icons" v-on:click="toggle_filters">filter_list</i></a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row" v-show="showFilters">
            <div class="col s12">
                <div class="input-field inline" v-for="field in filterFields">
                    <input :id="'filter_' + field" type="text" :placeholder="$t('labels.' + field)"
                           v-model="filter[field]" v-on:keyup.enter="set_filter">
                    <label :for="'filter_' + field">{{ $t('labels.'+ field) }}</label>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
export default {

    data () {
        return {
            meta: [],
            filter: {},
            filter_string: '',
            order: {
                field: 'display_name',
                direction: 'asc'
            },
            order_string: '',
            page: 1
        }
    },

    props: {
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        showFilters: {
            type: Boolean,
            default: false,
            required: false
        },
        filterFields: {
            type: Array,
            default:  [],
            required: false
        }
    },

    methods: {

        toggle_filters: function() {
            this.showFilters = !this.showFilters;
        },

        set_order: function(field) {
            this.order_string = 'order[' + this.order.field + ']=' + this.order.direction;
            if (this.order.field === field || field === null) {
                if (this.order.direction === 'asc') {
                    this.order.direction = 'desc';
                }
                else {
                    this.order.direction = 'asc';
                }
            }
            else {
                this.order.field = field;
            }

            this.order_string = 'order[' + this.order.field + ']=' + this.order.direction;
            this.$parent.load_data();
        },

        set_filter: function() {
            this.filter_string = '&';
            if (this.sourceFilter !== '') {
                this.filter_string += this.sourceFilter + '&';
            }
            for (var prop in this.filter) {
                if (this.filter.hasOwnProperty(prop)) {
                    if (this.filter[prop] !== null
                        && this.filter[prop] !== '') {

                        this.filter_string += 'filter[' + prop + ']=like:*' + this.filter[prop] + '*&';
                    }
                }
            }
            this.$parent.load_data();
        },

        set_page: function(page) {
            this.page = page;
            this.$parent.load_data();
        },

    }

};
</script>