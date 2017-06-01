<template>
    <div>
        <div class="row" v-if="!animalId" v-show="showFilters">
            <div class="col s10 m8 l8">
                <div class="input-field inline">
                    <input id="filter_display_name" type="text" :placeholder="$t('labels.display_name')"
                           v-model="filter.display_name" v-on:keyup.enter="set_filter">
                    <label for="filter_display_name">{{ $t('labels.display_name') }}</label>
                </div>

                <div class="input-field inline">
                    <input id="filter_lat_name" type="text" :placeholder="$t('labels.latin_name')"
                           v-model="filter.lat_name" v-on:keyup.enter="set_filter">
                    <label for="filter_lat_name">{{ $t('labels.latin_name') }}</label>
                </div>

                <div class="input-field inline">
                    <input id="filter_common_name" type="text" :placeholder="$t('labels.common_name')"
                           v-model="filter.common_name" v-on:keyup.enter="set_filter">
                    <label for="filter_common_name">{{ $t('labels.common_name') }}</label>
                </div>
            </div>
            <div class="col s2 m4 l4 right-align">
                <div class="input-field inline">
                    <a href="#!"><i class="material-icons" v-on:click="toggle_filters">filter_list</i></a>
                </div>
            </div>
        </div>
        <div class="row" v-if="!animalId" v-show="!showFilters">
            <div class="col s12 right-align">
                <div class="input-field inline">
                    {{ $t('labels.filter') }}
                    <a href="#!"><i class="material-icons" v-on:click="toggle_filters">filter_list</i></a>
                </div>
            </div>
        </div>
        <div :class="[containerClasses, 'masonry-grid']" :id="containerId">
            <div :class="wrapperClasses" v-for="animal in animals">
                <!-- Modals -->
                <div v-bind:id="'modal_just_fed_' + animal.id" class="modal" style="min-height: 800px;">
                    <form v-bind:action="'/api/v1/animals/' + animal.id + '/feedings'" data-method="POST" v-on:submit="submit">
                        <div class="modal-content">
                            <h4>{{ $t("labels.just_fed") }}</h4>

                            <select name="meal_type">
                                <option v-for="ft in feeding_types" v-bind:value="ft.name">{{ ft.name }}</option>
                            </select>
                            <label>{{ $t("labels.meal_type") }}</label>

                            <input type="date" class="datepicker" :placeholder="$t('labels.date')" name="created_at">
                            <label>{{ $t('labels.date') }}</label>
                        </div>

                        <div class="modal-footer">
                            <button class="btn modal-action modal-close waves-effect waves-light" type="submit">{{ $t("buttons.save") }}
                                <i class="material-icons left">send</i>
                            </button>
                        </div>
                    </form>
                </div>

                <div v-bind:id="'modal_add_weight_' + animal.id" class="modal" style="min-height: 800px;">
                    <form v-bind:action="'/api/v1/animals/' + animal.id + '/weighings'" data-method="POST" v-on:submit="submit">
                        <div class="modal-content">
                            <h4>{{ $t("labels.add_weight") }}</h4>

                            <input name="weight" id="weight" v-bind:placeholder="$t('labels.weight')+ '/g'">
                            <label for="weight">{{ $t("labels.weight") }}/g</label>

                            <input type="date" class="datepicker" :placeholder="$t('labels.date')" name="created_at">
                            <label>{{ $t('labels.date') }}</label>
                        </div>

                        <div class="modal-footer">
                            <button class="btn modal-action modal-close waves-effect waves-light" type="submit">{{ $t("buttons.save") }}
                                <i class="material-icons left">send</i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Card -->
                <div class="card">
                    <div class="card-image terrarium-card-image"
                         v-bind:style="animal.default_background_filepath ? 'background-image: url(\'' + animal.default_background_filepath + '\');' : 'background-image: url(\'/svg/Ciliatus_Logo.svg\'); background-position: top center;'">

                        <div class="tiny right" style="position: relative; top: 120px;" v-show="animal.loading_data">
                            <loading-indicator :size="20" ></loading-indicator>
                        </div>
                    </div>

                    <div class="card-content">
                        <span class="card-title activator truncate">
                            <span><a :href="'/animals/' + animal.id">{{ animal.display_name }}</a></span>
                            <i class="material-icons right" v-if="!animal.death_date">more_vert</i>
                        </span>
                        <p>
                            <span v-show="animal.latin_name">{{ animal.latin_name }},</span>
                            <span v-show="animal.common_name && !animal.latin_name">{{ animal.common_name }},</span>
                            <span v-show="animal.birth_date || animal.death_date">{{ animal.age_value }} {{ $tc("units." + animal.age_unit, animal.age_value) }}</span>

                            <span v-if="animal.last_feeding && !animal.death_date">
                                <br />
                                <i class="material-icons tiny">local_dining</i>
                                {{ $t(
                                    'units.' + $getMatchingTimeDiff(animal.last_feeding.timestamps.created_diff).unit,
                                    {val: $getMatchingTimeDiff(animal.last_feeding.timestamps.created_diff).val}
                                )}}
                                {{ animal.last_feeding.name }}
                            </span>

                            <span v-if="animal.last_weighing && !animal.death_date">
                                <br />
                                <i class="material-icons tiny">file_download</i>
                                {{ $t(
                                    'units.' + $getMatchingTimeDiff(animal.last_weighing.timestamps.created_diff).unit,
                                    {val: $getMatchingTimeDiff(animal.last_weighing.timestamps.created_diff).val}
                                )}}
                                {{ animal.last_weighing.value }}{{ animal.last_weighing.name }}
                                <span v-if="animal.last_weighing.trend && animal.last_weighing.trend > 0" class="green-text">
                                    (+ {{ animal.last_weighing.trend }}%)
                                </span>
                                <span v-if="animal.last_weighing.trend && animal.last_weighing.trend < 0" class="red-text">
                                    ({{ animal.last_weighing.trend }}%)
                                </span>
                                <span v-if="animal.last_weighing.trend && animal.last_weighing.trend == 0">
                                    (+/- 0%)
                                </span>
                            </span>
                        </p>
                    </div>

                    <div class="card-reveal" v-if="!animal.death_date">
                        <span class="card-title">{{ $tc("components.terraria", 1) }}<i class="material-icons right">close</i></span>

                        <p>
                            <a v-if="animal.terrarium" v-bind:href="'/terraria/' + animal.terrarium.id">{{ animal.terrarium.display_name }}</a>
                        </p>

                        <span class="card-title">{{ $t("labels.just_fed") }}</span>

                        <p>
                            <a class="waves-effect waves-teal btn" v-bind:href="'#modal_just_fed_' + animal.id" v-bind:onclick="'$(\'#modal_just_fed_' + animal.id + '\').modal(); $(\'#modal_just_fed_' + animal.id + ' select\').material_select(); $(\'#modal_just_fed_' + animal.id + '\').modal(\'open\');'">{{ $t("labels.just_fed") }}</a>
                        </p>
                        <p>
                            <a class="waves-effect waves-teal btn" v-bind:href="'#modal_add_weight_' + animal.id" v-bind:onclick="'$(\'#modal_add_weight_' + animal.id + '\').modal(); $(\'#modal_add_weight_' + animal.id + ' select\').material_select(); $(\'#modal_add_weight_' + animal.id + '\').modal(\'open\');'">{{ $t("labels.add_weight") }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="!animalId">
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
import LoadingIndicator from './loading-indicator.vue';

export default {
    data () {
        return {
            animals: [],
            initial: true,
            meta: [],
            feeding_types: [],
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
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        animalId: {
            type: String,
            default: null,
            required: false
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        subscribeAdd: {
            type: Boolean,
            default: true,
            required: false
        },
        subscribeDelete: {
            type: Boolean,
            default: true,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        },
        containerClasses: {
            type: String,
            default: '',
            required: false
        },
        containerId: {
            type: String,
            default: 'animals-masonry-grid',
            required: false
        },
        showFilters: {
            type: Boolean,
            default: false,
            required: false
        }
    },

    components: {
        'loading-indicator': LoadingIndicator
    },

    methods: {
        toggle_filters: function() {
            this.showFilters = !this.showFilters;
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
        update: function(a) {
            var item = null;
            this.animals.forEach(function(data, index) {
                if (data.id === a.animal_id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true) {
                var that = this;
                $.ajax({
                    url: '/api/v1/animals/' + a.animal_id,
                    method: 'GET',
                    success: function (data) {
                        that.animals.push(data.data);

                        that.$nextTick(function() {
                            that.refresh_grid();
                        });
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            }
            else if (item !== null) {
                this.$set(this.animals[item], 'loading_data', true);
                var that = this;
                $.ajax({
                    url: '/api/v1/animals/' + a.animal_id,
                    method: 'GET',
                    success: function (data) {
                        that.animals.splice(item, 1, data.data);

                        that.$nextTick(function() {
                            that.refresh_grid();
                        });
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                        this.$set(this.animals[item], 'loading_data', false);
                    }
                });
            }

            this.$nextTick(function() {
                this.refresh_grid();
            });
        },
        delete: function(a) {
            var item = null;
            this.animals.forEach(function(data, index) {
                if (data.id === a.animal_id) {
                    item = index;
                }
            });

            if (item !== null && this.subscribeDelete === true) {
                this.animals.splice(item, 1);
            }

            this.$nextTick(function() {
                this.refresh_grid();
            });
        },

        submit: function(e) {
            window.submit_form(e);
        },

        load_data: function() {
            var that = this;
            var source_url = '';
            this.order_string = 'order[' + this.order.field + ']=' + this.order.direction;
            if (this.animalId !== null) {
                source_url = '/api/v1/animals/' + this.animalId
            }
            else {
                source_url = '/api/v1/animals/?pagination[per_page]=6&page=' + this.page + this.filter_string + this.order_string;
            }

            window.eventHubVue.processStarted();
            $.ajax({
                url: source_url,
                method: 'GET',
                success: function (data) {
                    if (that.animalId !== null) {
                        that.animals = [data.data];
                    }
                    else {
                        that.meta = data.meta;
                        that.animals = data.data;
                    }

                    that.$nextTick(function() {
                        if (that.initial) {
                            var element = '#' + this.containerId;
                            $(element).masonry({
                                columnWidth: '.col',
                                itemSelector: '.col',
                            });
                            that.initial = false;
                        }
                        that.refresh_grid();
                    });

                    window.eventHubVue.processEnded();
                },
                error: function (error) {
                    console.log(JSON.stringify(error));
                    window.eventHubVue.processEnded();
                }
            });

            window.eventHubVue.processStarted();
            $.ajax({
                url: '/api/v1/properties?filter[type]=AnimalFeedingType&raw=true',
                method: 'GET',
                success: function (data) {
                    that.feeding_types = data.data;
                    window.eventHubVue.processEnded();
                },
                error: function (error) {
                    console.log(JSON.stringify(error));
                    window.eventHubVue.processEnded();
                }
            });
        },

        refresh_grid: function() {
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
            $('.modal').modal();
            $('.datepicker').pickadate({
                selectMonths: true,
                selectYears: 15,
                format: 'yyyy-mm-dd',
            });
        }
    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('AnimalUpdated', (e) => {
                this.update(e);
            }).listen('AnimalDeleted', (e) => {
                this.delete(e);
            });

        var that = this;
        setTimeout(function() {
            that.set_filter();
        }, 100);

        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function() {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000)
        }
    }

}
</script>