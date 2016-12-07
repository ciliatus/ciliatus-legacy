<template>
    <div :class="containerClasses" :id="containerId">
        <div v-for="animal in animals">
            <div v-bind:id="'modal_just_fed_' + animal.id" class="modal">
                <form v-bind:action="'/api/v1/animals/' + animal.id + '/feedings'" data-method="POST" v-on:submit="submit">
                    <div class="modal-content" style="min-height: 500px">
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

            <div :class="wrapperClasses">
                <div class="card">
                    <div class="card-image waves-effect waves-block waves-light terrarium-card-image"
                         v-bind:class="animal.default_background_filepath ? '' : 'teal lighten-1'"
                         v-bind:style="animal.default_background_filepath ? 'background-image: url(\'' + animal.default_background_filepath + '\');' : ''">
                    </div>

                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4 truncate">
                            <span>{{ animal.display_name }}</span>
                            <i class="material-icons right">more_vert</i>
                        </span>
                        <p>
                            <span v-show="animal.latin_name">{{ animal.latin_name }}</span>
                            <span v-show="animal.common_name && !animal.latin_name">{{ animal.common_name }}</span>
                            <br />

                            <span v-show="animal.birth_date !== null">{{ animal.birth_date }}</span>
                            <span v-show="animal.death_date !== null"> - {{ animal.death_date }}</span>
                            <span v-show="animal.birth_date || animal.death_date"><i>{{ animal.age_value }} {{ $tc("units." + animal.age_unit, animal.age_value) }}</i></span>

                            <span v-if="animal.last_feeding">
                                <br />
                                <span v-if="animal.last_feeding.timestamps.diff.value == 0">{{ $t("labels.today") }}</span>
                                <span v-if="animal.last_feeding.timestamps.diff.value > 0">{{ animal.last_feeding.timestamps.diff.value }} {{ $tc("units." + animal.last_feeding.timestamps.diff.unit, animal.last_feeding.timestamps.diff.value) }}</span>
                                <i>{{ animal.last_feeding.name }}</i>
                            </span>
                            <br />
                        </p>
                    </div>

                    <div class="card-action">
                        <a v-bind:href="'/animals/' + animal.id">{{ $t("buttons.details") }}</a>
                        <a v-bind:href="'/animals/' + animal.id + '/edit'">{{ $t("buttons.edit") }}</a>
                    </div>

                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">{{ $tc("components.terraria", 1) }}<i class="material-icons right">close</i></span>

                        <p>
                            <a v-if="animal.terrarium" v-bind:href="'/terraria/' + animal.terrarium.id">{{ animal.terrarium.display_name }}</a>
                        </p>

                        <span class="card-title grey-text text-darken-4">{{ $t("labels.just_fed") }}</span>

                        <p>
                            <a class="waves-effect waves-teal btn" v-bind:href="'#modal_just_fed_' + animal.id" v-bind:onclick="'$(\'#modal_just_fed_' + animal.id + '\').modal(); $(\'#modal_just_fed_' + animal.id + ' select\').material_select(); $(\'#modal_just_fed_' + animal.id + '\').modal(\'open\');'">{{ $t("labels.just_fed") }}</a>
                        </p>
                        <p>
                            <a class="waves-effect waves-teal btn">{{ $t("labels.add_weight") }}</a>
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
        animalId: {
            type: String,
            default: '',
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

            this.refresh_grid();
        },

        delete: function(a) {
            var item = null;
            this.animals.forEach(function(data, index) {
                if (data.id === a.animal.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.animals.splice(item, 1);
            }

            this.refresh_grid();
        },

        refresh_grid: function() {
            this.$nextTick(function() {
                var $container = $('#' + this.containerId);
                $container.masonry({
                  columnWidth: '.col',
                  itemSelector: '.col',
                });
            });
        },

        submit: function(e) {
            window.submit_form(e);
        }

    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('AnimalUpdated', (e) => {
                this.update(e);
            }).listen('AnimalDeleted', (e) => {
                this.delete(e);
            });

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/animals/' + that.animalId,
            method: 'GET',
            success: function (data) {
                if (that.animalId !== '') {
                    that.animals = [data.data];
                }
                else {
                    that.animals = data.data;
                }

                that.refresh_grid();

                window.eventHubVue.processEnded();
            },
            error: function (error) {
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });

        $.ajax({
            url: '/api/v1/properties?filter[type]=AnimalFeedingType&raw',
            method: 'GET',
            success: function (data) {
                that.feeding_types = data.data;
                window.eventHubVue.processEnded();
            },
            error: function (error) {
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });
    }

}
</script>
