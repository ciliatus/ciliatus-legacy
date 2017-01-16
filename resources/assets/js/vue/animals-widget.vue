<template>
    <div :class="[containerClasses, 'masonry-grid']" :id="containerId">
        <div v-for="animal in animals">
            <div v-bind:id="'modal_just_fed_' + animal.id" class="modal">
                <form v-bind:action="'/api/v1/animals/' + animal.id + '/feedings'" data-method="POST" v-on:submit="submit">
                    <div class="modal-content" style="min-height: 300px">
                        <h4>{{ $t("labels.just_fed") }}</h4>
                        <p>
                            <select name="meal_type" id="meal_type">
                                <option v-for="ft in feeding_types" v-bind:value="ft.name">{{ ft.name }}</option>
                            </select>
                            <label for="meal_type">{{ $t("labels.meal_type") }}</label>
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn modal-action modal-close waves-effect waves-light" type="submit">{{ $t("buttons.save") }}
                            <i class="material-icons right">send</i>
                        </button>
                    </div>
                </form>
            </div>

            <div v-bind:id="'modal_add_weight_' + animal.id" class="modal">
                <form v-bind:action="'/api/v1/animals/' + animal.id + '/weighings'" data-method="POST" v-on:submit="submit">
                    <div class="modal-content">
                        <h4>{{ $t("labels.add_weight") }}</h4>
                        <p>
                            <input name="weight" id="weight" v-bind:placeholder="$t('labels.weight')+ '/g'">
                            <label for="weight">{{ $t("labels.weight") }}/g</label>
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn modal-action modal-close waves-effect waves-light" type="submit">{{ $t("buttons.save") }}
                            <i class="material-icons right">send</i>
                        </button>
                    </div>
                </form>
            </div>

            <div :class="wrapperClasses">
                <div class="card">
                    <div class="card-image waves-effect waves-block waves-light terrarium-card-image"
                         v-bind:class="animal.default_background_filepath ? '' : 'teal lighten-1'"
                         v-bind:style="animal.default_background_filepath ? 'background-image: url(\'' + animal.default_background_filepath + '\');' : ''">
                    </div>

                    <div class="card-content">
                        <span class="card-title activator truncate">
                            <span>{{ animal.display_name }} </span>
                            <i class="material-icons right">more_vert</i>
                        </span>
                        <p>
                            <span v-show="animal.latin_name">{{ animal.latin_name }}</span>
                            <span v-show="animal.common_name && !animal.latin_name">{{ animal.common_name }}</span>
                            <span v-show="animal.birth_date || animal.death_date">, {{ animal.age_value }} {{ $tc("units." + animal.age_unit, animal.age_value) }}</span>

                            <span v-if="animal.last_feeding">
                                <br />
                                <i class="material-icons tiny">local_dining</i>
                                <span v-if="animal.last_feeding.timestamps.diff.value == 0">{{ $t("labels.today") }}</span>
                                <span v-if="animal.last_feeding.timestamps.diff.value > 0">
                                    {{ $t('units.' + animal.last_feeding.timestamps.diff.unit + '_ago', {val: animal.last_feeding.timestamps.diff.value}) }}: {{ animal.last_feeding.name }}
                                </span>
                            </span>
                            <br />
                        </p>
                    </div>

                    <div class="card-action">
                        <a v-bind:href="'/animals/' + animal.id">{{ $t("buttons.details") }}</a>
                        <a v-bind:href="'/animals/' + animal.id + '/edit'">{{ $t("buttons.edit") }}</a>
                    </div>

                    <div class="card-reveal">
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
    </div>
</template>

<script>
export default {
    data () {
        return {
            animals: [],
            feeding_types: []
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
        }
    },

    methods: {
        update: function(a) {
            var item = null;
            this.animals.forEach(function(data, index) {
                if (data.id === a.animal.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.animals.push(a.animal)
            }
            else if (item !== null) {
                this.animals.splice(item, 1, a.animal);
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

            if (item !== null) {
                this.animals.splice(item, 1);
            }

            this.$nextTick(function() {
                this.refresh_grid();
            });
        },

        refresh_grid: function() {
            $('#' + this.containerId).masonry('reloadItems');
            $('#' + this.containerId).masonry('layout');
        },

        submit: function(e) {
            window.submit_form(e);
        },

        load_data: function() {
            var that = this;

            var source_url = '';
            if (this.animalId !== null) {
                source_url = '/api/v1/animals/' + this.animalId
            }
            else {
                source_url = '/api/v1/animals/?order[death_date]=asc&order[display_name]=asc&raw=true';
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
                        that.animals = data.data;
                    }

                    that.$nextTick(function() {
                        $('#' + that.containerId).masonry({
                            columnWidth: '.col',
                            itemSelector: '.col',
                        });
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
        }

    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('AnimalUpdated', (e) => {
                this.update(e);
            }).listen('AnimalDeleted', (e) => {
                this.delete(e);
            });


        this.load_data();

        var that = this;
        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function() {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000)
        }
    }

}
</script>
