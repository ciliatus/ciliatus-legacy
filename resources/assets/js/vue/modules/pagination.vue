<template>
    <ul class="pagination">
        <li v-bind:class="{ 'disabled': pagination.current_page == 1, 'waves-effect': pagination.current_page != 1 }">
            <a href="#!" v-on:click="set_page(1)"><i class="material-icons">first_page</i></a>
        </li>
        <li v-bind:class="{ 'disabled': pagination.current_page == 1, 'waves-effect': pagination.current_page != 1 }">
            <a href="#!" v-on:click="set_page(pagination.current_page-1)"><i class="material-icons">chevron_left</i></a>
        </li>

        <li v-if="pagination.current_page-3 > 0" class="waves-effect"><a href="#!" v-on:click="set_page(pagination.current_page-3)">{{ pagination.current_page-3 }}</a></li>
        <li v-if="pagination.current_page-2 > 0" class="waves-effect"><a href="#!" v-on:click="set_page(pagination.current_page-2)">{{ pagination.current_page-2 }}</a></li>
        <li v-if="pagination.current_page-1 > 0" class="waves-effect"><a href="#!" v-on:click="set_page(pagination.current_page-1)">{{ pagination.current_page-1 }}</a></li>

        <li class="active"><a href="#!">{{ pagination.current_page }}</a></li>

        <li v-if="pagination.current_page+1 <= pagination.total_pages" class="waves-effect"><a href="#!" v-on:click="set_page(pagination.current_page+1)">{{ pagination.current_page+1 }}</a></li>
        <li v-if="pagination.current_page+2 <= pagination.total_pages" class="waves-effect"><a href="#!" v-on:click="set_page(pagination.current_page+2)">{{ pagination.current_page+2 }}</a></li>
        <li v-if="pagination.current_page+3 <= pagination.total_pages" class="waves-effect"><a href="#!" v-on:click="set_page(pagination.current_page+3)">{{ pagination.current_page+3 }}</a></li>

        <li v-bind:class="{ 'disabled': pagination.current_page == pagination.total_pages, 'waves-effect': pagination.current_page != pagination.total_pages }">
            <a href="#!" v-on:click="set_page(pagination.current_page+1)"><i class="material-icons">chevron_right</i></a>
        </li>
        <li v-bind:class="{ 'disabled': pagination.current_page == pagination.total_pages, 'waves-effect': pagination.current_page != pagination.total_pages }">
            <a href="#!" v-on:click="set_page(pagination.total_pages)"><i class="material-icons">last_page</i></a>
        </li>
    </ul>   
</template>

<script>
export default {
    data: function() {
        return {
            pagination: {}
        }
    },
    
    props: {
        PaginationObject: {
            type: Object,
            required: true
        }
    },

    methods: {
        update: function(pagination) {
            this.pagination = pagination;
        },
        set_page: function(page) {
            this.$parent.$options.methods.set_page(page);
        },
    },

    created: function() {
        this.update(this.PaginationObject);
    }
}
</script>
