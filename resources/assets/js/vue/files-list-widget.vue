<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight collapsible" data-collapsible="expandable">
                <thead>
                <tr>
                    <th v-if="showOptionSelect" width="100">

                    </th>
                    <th data-field="display_name">
                        <a href="#!" v-on:click="set_order('name')">{{ $t('labels.display_name') }}</a>
                        <i v-show="order.field == 'display_name' && order.direction == 'asc'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'display_name' && order.direction == 'desc'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_display_name" type="text" v-model="filter.display_name" v-on:keydown.enter="set_filter">
                            <label for="filter_display_name">Filter</label>
                        </div>
                    </th>

                    <th data-field="mimetype">
                        <a href="#!" v-on:click="set_order('name')">{{ $t('labels.type') }}</a>
                        <i v-show="order.field == 'mimetype' && order.direction == 'mimetype'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'mimetype' && order.direction == 'mimetype'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_mimetype" type="text" v-model="filter.mimetype" v-on:keydown.enter="set_filter">
                            <label for="filter_mimetype">Filter</label>
                        </div>
                    </th>

                    <th data-field="size">
                        <a href="#!" v-on:click="set_order('name')">{{ $t('labels.size') }}</a>
                        <i v-show="order.field == 'size' && order.direction == 'size'" class="material-icons">arrow_drop_up</i>
                        <i v-show="order.field == 'size' && order.direction == 'size'" class="material-icons">arrow_drop_down</i>
                        <div class="input-field inline">
                            <input id="filter_size" type="text" v-model="filter.size" v-on:keydown.enter="set_filter">
                            <label for="filter_size">Filter</label>
                        </div>
                    </th>

                    <th data-field="timestamps.created_at">
                        {{ $t('labels.created_at') }}
                    </th>
                </tr>
                </thead>

                <template v-for="file in files">
                    <tbody>
                        <tr class="collapsible-header">

                            <td v-if="showOptionSelect">
                                <span>
                                    <input name="file" type="radio" :id="file.id" :value="file.id">
                                    <label :for="file.id"> </label>
                                </span>
                            </td>

                            <td>
                                <span>
                                    <i class="material-icons">{{ file.icon }}</i>
                                    <a v-bind:href="'/files/' + file.id">{{ file.display_name }}</a>
                                </span>
                            </td>

                            <td>
                                <span>
                                    {{ file.mimetype }}
                                </span>
                            </td>

                            <td>
                                <span v-if="file.size / 1024 > 1024">
                                    {{ Math.round(file.size / 1024 / 1024, 1) }} MB
                                </span>
                                <span v-else>
                                    {{ Math.round(file.size / 1024, 0) }} KB
                                </span>
                            </td>

                            <td>
                                {{ $t(
                                    'units.' + $getMatchingTimeDiff(file.timestamps.created_diff).unit,
                                    {val: $getMatchingTimeDiff(file.timestamps.created_diff).val}
                                )}}
                            </td>

                            <td>
                                <span>
                                    <a class="waves-effect waves-light btn-small" v-bind:href="'/files/' + file.id + '/edit'">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </span>
                            </td>

                        </tr>
                        <tr class="collapsible-body">
                            <td v-if="showOptionSelect">
                            </td>
                            <td colspan="4">
                                <img v-if="file.mimetype.startsWith('image') && file.thumb != undefined" :src="file.thumb.path_external">
                            </td>
                        </tr>
                    </tbody>
                </template>
            </table>

            <ul class="pagination" v-if="meta.hasOwnProperty('pagination')">
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
            files: [],
            meta: [],
            filter: {
                name: '',
                model: '',
                'file.name': '',
                'terrarium.display_name': ''
            },
            filter_string: '',
            order: {
                field: 'name',
                direction: 'asc'
            },
            order_string: '',
            page: 1
        }
    },

    props: {
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        sourceFilter: {
            type: String,
            default: 'filter[usage]=notlike:thumb:or:null',
            required: false
        },
        refreshTimeoutSeconds: {
            type: Number,
            default: 60,
            required: false
        },
        showOptionSelect: {
            type: Boolean,
            default: false,
            required: false
        }
    },

    methods: {
        update: function(f) {
            var item = null;
            this.files.forEach(function(data, index) {
                if (data.id === f.file.id) {
                    item = index;
                }
            });
            if (item !== null) {
                this.files.splice(item, 1, f.file);
            }
        },

        delete: function(f) {
            var item = null;
            this.files.forEach(function(data, index) {
                if (data.id === f.file.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.files.splice(item, 1);
            }
        },
        
        set_order: function(field) {
            if (this.order.field == field || field === null) {
                if (this.order.direction == 'asc') {
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
            this.load_data();
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
            this.load_data();
        },
        set_page: function(page) {
            this.page = page;
            this.load_data();
        },
        load_data: function() {
            window.eventHubVue.processStarted();
            this.order_string = 'order[' + this.order.field + ']=' + this.order.direction;
            var that = this;
            $.ajax({
                url: '/api/v1/files?page=' + that.page + that.filter_string + that.order_string,
                method: 'GET',
                success: function (data) {
                    that.meta = data.meta;
                    that.files = data.data;
                    that.$nextTick(function() {
                        $('table.collapsible').collapsibletable();
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
        window.echo.private('dashboard-updates')
                .listen('FileUpdated', (e) => {
                this.update(e);
        }).listen('FileDeleted', (e) => {
                this.delete(e);
        });

        var that = this;
        setTimeout(function() {
            that.set_filter();
        }, 100);

        this.set_filter();

        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function() {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000)
        }
    }
}
</script>
