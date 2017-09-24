<template>
    <div>
        <div :class="wrapperClasses">
            <div class="card">
                <div class="card-header">
                    <i class="material-icons">schedule</i>
                    {{ $tc("components.animal_feeding_schedules", 2) }}
                </div>

                <div class="card-content">
                    <div v-for="afs in animal_feeding_schedules">
                        <span v-if="afs.timestamps.next != null">{{ afs.timestamps.next }} - </span>{{ afs.type }}


                        <span class="right">
                            <a :href="'/animals/' + animalId + '/feeding_schedules/' + afs.id + '/edit'">
                                <i class="material-icons">edit</i>
                            </a>
                        </span>

                        <span class="right">
                            <span v-show="afs.due_days == 0" class="new badge" v-bind:data-badge-caption="$t('labels.due')"> </span>
                            <span v-show="afs.due_days < 0" class="new badge red" v-bind:data-badge-caption="$t('labels.overdue')"> </span>
                        </span>
                    </div>
                    <div v-if="animal_feeding_schedules.length < 1">
                        <p>{{ $t('labels.no_data') }}</p>
                    </div>
                </div>

                <div class="card-action">
                    <a v-bind:href="'/animals/' + animalId + '/feeding_schedules/create'">{{ $t("buttons.add") }}</a>
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
            .listen('AnimalFeedingSchedulePropertyUpdated', (e) => {
                this.update(e);
            }).listen('AnimalFeedingSchedulePropertyDeleted', (e) => {
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
