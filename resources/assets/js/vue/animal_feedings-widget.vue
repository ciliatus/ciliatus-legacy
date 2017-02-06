<template>
    <div>
        <div :class="wrapperClasses">
            <div v-bind:id="'modal_add_feeding_' + animalId" class="modal" style="min-height: 400px">
                <form v-bind:action="'/api/v1/animals/' + animalId + '/feedings'" data-method="POST" onsubmit="window.submit_form">
                    <div class="modal-content">
                        <h4>{{ $t("labels.just_fed") }}</h4>
                        <p>
                            <select name="meal_type" id="meal_type">
                                <option v-for="ft in animal_feeding_types" v-bind:value="ft">{{ ft }}</option>
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

            <div class="card">
                <div class="card-content teal lighten-1 white-text">
                    <i class="material-icons">local_dining</i>
                    {{ $tc("components.animal_feedings", 2) }}
                </div>

                <div class="card-content">
                    <span class="card-title activator truncate">
                        <span>{{ $tc("components.animal_feedings", 2) }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>

                    <div v-for="af in animal_feedings">
                        <p>
                            <span v-if="af.timestamps.created_diff.days > 1">{{ $t('units.days_ago', {val: af.timestamps.created_diff.days}) }}</span>
                            <span v-if="af.timestamps.created_diff.days <= 1 && af.timestamps.created_diff.hours > 1">{{ $t('units.hours_ago', {val: af.timestamps.created_diff.hours}) }}</span>
                            <span v-if="af.timestamps.created_diff.days <= 1 && af.timestamps.created_diff.hours <= 1">{{ $t('units.just_now') }}</span>
                            <span> - {{ af.type }}</span>
                        </p>
                    </div>
                    <div v-if="animal_feedings.length < 1">
                        <p>{{ $t('labels.no_data') }}</p>
                    </div>
                </div>

                <div class="card-action">
                    <a v-bind:href="'#modal_add_feeding_' + animalId" v-bind:onclick="'$(\'#modal_add_feeding_' + animalId + '\').modal(); $(\'#modal_add_feeding_' + animalId + ' select\').material_select(); $(\'#modal_add_feeding_' + animalId + '\').modal(\'open\');'">{{ $t("buttons.add") }}</a>
                </div>

                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4"><i class="material-icons right">close</i></span>

                    <p>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            animal_feedings: [],
            animal_feeding_types: []
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
            required: true
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function(a) {
            var item = null;
            if (a.animal_feeding.animal.id !== this.animalId) {
                return;
            }

            this.animal_feedings.forEach(function(data, index) {
                if (data.id === a.animal_feeding.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.animal_feedings.push(a.animal_feeding)
            }
            else if (item !== null) {
                this.animal_feedings.splice(item, 1, a.animal_feeding);
            }
        },

        delete: function(a) {
            var item = null;
            this.animal_feedings.forEach(function(data, index) {
                if (data.id === a.animal_feeding_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.animal_feedings.splice(item, 1);
            }
        },

        load_data: function() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/animals/' + that.animalId + '/feedings?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    that.animal_feedings = data.data;
                    window.eventHubVue.processEnded();
                },
                error: function (error) {
                    console.log(JSON.stringify(error));
                    window.eventHubVue.processEnded();
                }
            });

            window.eventHubVue.processStarted();
            $.ajax({
                url: '/api/v1/animals/feedings/types',
                method: 'GET',
                success: function (data) {
                    that.animal_feeding_types = data.data;
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
            .listen('AnimalFeedingUpdated', (e) => {
                this.update(e);
            }).listen('AnimalFeedingDeleted', (e) => {
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
