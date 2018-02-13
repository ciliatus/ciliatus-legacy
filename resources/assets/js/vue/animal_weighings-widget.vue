<template>
    <div>
        <div :class="wrapperClasses">
            <div v-bind:id="'modal_add_weighing_' + animalId" class="modal" style="min-height: 800px;">
                <form v-bind:action="'/api/v1/animals/' + animalId + '/weighings'" data-method="POST" onsubmit="window.submit_form">
                    <div class="modal-content">
                        <h4>{{ $t("labels.add_weight") }}</h4>
                        <p>
                            <input type="text" name="weight" id="weight" :placeholder="$t('labels.weight')" value="">
                            <label for="weight">{{ $t("labels.weight") }}/g</label>

                            <input type="date" class="datepicker" :placeholder="$t('labels.date')" name="created_at">
                            <label>{{ $t('labels.date') }}</label>
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn modal-action modal-close waves-effect waves-light" type="submit">{{ $t("buttons.save") }}
                            <i class="material-icons left">send</i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="material-icons">vertical_align_bottom</i>
                    {{ $tc("components.animal_weighings", 2) }}
                </div>

                <div class="card-content">
                    <div v-for="af in animal_weighings">
                        <div style="width: 100%;" class="row row-no-margin">
                            <span v-if="af.timestamps.created_diff.days > 1">{{ $t('units.days_ago', {val: af.timestamps.created_diff.days}) }}</span>
                            <span v-if="af.timestamps.created_diff.days <= 1 && af.timestamps.created_diff.hours > 1">{{ $t('units.hours_ago', {val: af.timestamps.created_diff.hours}) }}</span>
                            <span v-if="af.timestamps.created_diff.days <= 1 && af.timestamps.created_diff.hours <= 1">{{ $t('units.just_now') }}</span>
                            <span> - {{ af.amount }}g</span>
                            <span class="right"><a class="red-text" :href="'/animals/' + animalId + '/weighings/' + af.id + '/delete'"><i class="material-icons">delete</i></a></span>
                        </div>
                    </div>
                    <div v-if="animal_weighings.length < 1">
                        <p>{{ $t('labels.no_data') }}</p>
                    </div>
                </div>

                <div class="card-action">
                    <a v-bind:href="'#modal_add_weighing_' + animalId" v-bind:onclick="'$(\'#modal_add_weighing_' + animalId + '\').modal(); $(\'#modal_add_weighing_' + animalId + ' select\').material_select(); $(\'#modal_add_weighing_' + animalId + '\').modal(\'open\');'">{{ $t("buttons.add") }}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            animal_weighings: []
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
            if (a.animal_weighing.animal.id !== this.animalId) {
                return;
            }

            this.animal_weighings.forEach(function(data, index) {
                if (data.id === a.animal_weighing.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.animal_weighings.push(a.animal_weighing)
            }
            else if (item !== null) {
                this.animal_weighings.splice(item, 1, a.animal_weighing);
            }
        },

        delete: function(a) {
            var item = null;
            this.animal_weighings.forEach(function(data, index) {
                if (data.id === a.animal_weighing_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.animal_weighings.splice(item, 1);
            }
        },

        load_data: function() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/animals/' + that.animalId + '/weighings?all=true&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    that.animal_weighings = data.data;
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
            .listen('AnimalWeighingEventUpdated', (e) => {
                this.update(e);
            }).listen('AnimalWeighingEventDeleted', (e) => {
                this.delete(e);
            });

        var that = this;
        setTimeout(function() {
            that.load_data();
        }, 100);

        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function() {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000)
        }
    }

}
</script>
