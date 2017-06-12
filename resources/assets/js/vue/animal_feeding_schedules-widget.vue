<template>
    <div>
        <div :class="wrapperClasses">
            <div class="card">
                <div class="card-header">
                    <i class="material-icons">schedule</i>
                    {{ $tc("components.animal_feeding_schedules", 2) }}
                </div>

                <div class="card-content">
                    <span class="card-title activator truncate">
                        <span>{{ $tc("components.animal_feeding_schedules", 2) }}</span>
                        <!--<i class="material-icons right">more_vert</i>-->
                    </span>

                    <div v-for="afs in animal_feeding_schedules">
                        <p>
                            <span v-if="afs.timestamps.next != null">{{ afs.timestamps.next }} - </span>{{ afs.type }}
                            <span v-show="afs.due_days == 0">
                                <span class="new badge" v-bind:data-badge-caption="$t('labels.due')"> </span>
                            </span>
                            <span v-show="afs.due_days < 0">
                                <span class="new badge red" v-bind:data-badge-caption="$t('labels.overdue')"> </span>
                            </span>
                        </p>
                    </div>
                    <div v-if="animal_feeding_schedules.length < 1">
                        <p>{{ $t('labels.no_data') }}</p>
                    </div>
                </div>

                <div class="card-action">
                    <a v-bind:href="'/animals/' + animalId + '/feeding_schedules/create'">{{ $t("buttons.add") }}</a>
                    <a v-bind:href="'/animals/' + animalId + '/edit'">{{ $t("buttons.edit") }}</a>
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
            animal_feeding_schedules: []
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

            if (a.animal_feeding_schedule.animal.id !== this.animalId) {
                return;
            }

            this.animal_feeding_schedules.forEach(function(data, index) {
                if (data.id === a.animal_feeding_schedule.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.animal_feeding_schedules.push(a.animal_feeding_schedule)
            }
            else if (item !== null) {
                this.animal_feeding_schedules.splice(item, 1, a.animal_feeding_schedule);
            }
        },

        delete: function(a) {
            var item = null;
            this.animal_feeding_schedules.forEach(function(data, index) {
                if (data.id === a.animal_feeding_schedule_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.animal_feeding_schedules.splice(item, 1);
            }
        },

        submit: function(e) {
            window.submit_form(e);
        },

        load_data: function() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/animals/' + that.animalId + '/feeding_schedules?raw=true&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    that.animal_feeding_schedules = data.data;
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
            .listen('AnimalFeedingScheduleUpdated', (e) => {
                this.update(e);
            }).listen('AnimalFeedingScheduleDeleted', (e) => {
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
