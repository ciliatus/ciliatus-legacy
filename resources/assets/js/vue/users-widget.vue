<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight">
                <thead>
                <tr>
                    <th data-field="id" class="hide-on-med-and-down">
                        ID
                        <div class="input-field inline">
                            <input id="filter_id" type="text" v-model="filter.id" v-on:keyup.enter="set_filter">
                            <label for="filter_id">Filter</label>
                        </div>
                    </th>
                    <th data-field="name">
                        {{ $t('labels.name') }}
                        <div class="input-field inline">
                            <input id="filter_name" type="text" v-model="filter.name" v-on:keyup.enter="set_filter">
                            <label for="filter_name">Filter</label>
                        </div>
                    </th>
                    <th data-field="email">
                        {{ $t('labels.email') }}
                        <div class="input-field inline">
                            <input id="filter_email" type="text" v-model="filter.email" v-on:keyup.enter="set_filter">
                            <label for="filter_email">Filter</label>
                        </div>
                    </th>
                    <th data-field="actions">
                        {{ $t('labels.actions') }}
                    </th>
                </tr>
                </thead>

                <tbody>

                <tr v-for="user in users">
                    <td class="hide-on-med-and-down">{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>
                        <a class="dropdown-button btn btn-small" href="#!" v-bind:data-activates="'dropdown-edit-user_' + user.id">
                            {{ $t('labels.actions') }}<i class="material-icons">keyboard_arrow_down</i>
                        </a>

                        <ul v-bind:id="'dropdown-edit-user_' + user.id" class="dropdown-content">
                            <li>
                                <a v-bind:href="'/users/' + user.id + '/edit'">
                                    {{ $t('buttons.edit') }}
                                </a>
                            </li>
                            <li>
                                <a v-bind:href="'/users/' + user.id + '/delete'">
                                    {{ $t('buttons.delete') }}
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>

            <ul class="pagination">
                <li v-bind:class="{ 'disabled': meta.pagination.current_page == 1, 'waves-effect': meta.pagination.current_page != 1 }">
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
                <li v-bind:class="{ 'disabled': meta.pagination.current_page == meta.pagination.total_pages, 'waves-effect': meta.pagination.current_page != meta.pagination.total_pages }">
                    <a href="#!" v-on:click="set_page(meta.pagination.total_pages)"><i class="material-icons">last_page</i></a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            users: [],
            meta: [],
            filter: {
                id: null,
                name: null,
                email: null
            },
            filter_string: '',
            page: 1
        }
    },

    props: {
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        set_filter: function() {
            this.filter_string = '';
            for (var prop in this.filter) {
                if (this.filter.hasOwnProperty(prop)) {
                    if (this.filter[prop] !== null
                        && this.filter[prop] !== '') {

                        this.filter_string += 'filter[' + prop + ']=like:*' + this.filter[prop] + '*&';
                    }
                }
            }
            this.load_data();
        },
        set_page: function(page) {
            this.page = page;
            this.load_data();
        },
        load_data: function() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/users?page=' + this.page + '&' + this.filter_string,
                method: 'GET',
                success: function (data) {
                    that.meta = data.meta;
                    that.users = data.data;
                    that.$nextTick(function() {
                        $('.dropdown-button').dropdown();
                    });
                    window.eventHubVue.processEnded();
                },
                error: function (error) {
                    console.log(JSON.stringify(error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function() {
        var that = this;
        setTimeout(function() {
            that.load_data();
        }, 100);
    }
}
</script>
