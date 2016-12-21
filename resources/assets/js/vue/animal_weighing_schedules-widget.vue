<template>
    <div>
        <div :class="wrapperClasses">
            <div class="card">
                <div class="card-content teal lighten-1 white-text">
                    <i class="material-icons">schedule</i>
                    {{ $tc("components.animal_weighing_schedules", 2) }}
                </div>

                <div class="card-content">
                    <span class="card-title activator truncate">
                        <span>{{ $tc("components.animal_weighing_schedules", 2) }}</span>
                        <!--<i class="material-icons right">more_vert</i>-->
                    </span>

                    <div v-for="aws in animal_weighing_schedules">
                        <p>
                            <span v-show="aws.timestamps.next != null">{{ aws.timestamps.next }}</span>
                            <span v-show="aws.timestamps.next == null">{{ $t("labels.now") }}</span>
                            <span v-show="aws.due_days == 0">
                                <span class="new badge" v-bind:data-badge-caption="$t('labels.due')"> </span>
                            </span>
                            <span v-show="aws.due_days < 0">
                                <span class="new badge red" v-bind:data-badge-caption="$t('labels.overdue')"> </span>
                            </span>
                        </p>
                    </div>
                    <div v-if="animal_weighing_schedules.length < 1">
                        <p>{{ $t('labels.no_data') }}</p>
                    </div>
                </div>

                <div class="card-action">
                    <a v-bind:href="'/animals/' + animalId + '/weighing_schedules/create'">{{ $t("buttons.add") }}</a>
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
            animal_weighing_schedules: []
        }
    },

    props: {
        animalId: {
            type: String,
            required: true
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

            if (a.animal_weighing_schedule.animal.id !== this.animalId) {
                return;
            }

            this.animal_weighing_schedules.forEach(function(data, index) {
                if (data.id === a.animal_weighing_schedule.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.animal_weighing_schedules.push(a.animal_weighing_schedule)
            }
            else if (item !== null) {
                this.animal_weighing_schedules.splice(item, 1, a.animal_weighing_schedule);
            }
        },

        delete: function(a) {
            var item = null;
            this.animal_weighing_schedules.forEach(function(data, index) {
                if (data.id === a.animal_weighing_schedule_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.animal_weighing_schedules.splice(item, 1);
            }
        },

        submit: function(e) {
            window.submit_form(e);
        }

    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('AnimalWeighingScheduleUpdated', (e) => {
                this.update(e);
            }).listen('AnimalWeighingScheduleDeleted', (e) => {
                this.delete(e);
            });

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/animals/' + that.animalId + '/weighing_schedules?raw=true',
            method: 'GET',
            success: function (data) {
                that.animal_weighing_schedules = data.data;
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
