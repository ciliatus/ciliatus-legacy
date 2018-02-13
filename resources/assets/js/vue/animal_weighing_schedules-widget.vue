<template>
    <div>
        <div :class="wrapperClasses">
            <div class="card">
                <div class="card-header">
                    <i class="material-icons">schedule</i>
                    {{ $tc("components.animal_weighing_schedules", 2) }}
                </div>

                <div class="card-content">
                    <div v-for="aws in animal_weighing_schedules">

                        <span v-show="aws.timestamps.next != null">{{ aws.timestamps.next }}</span>

                        <span v-show="aws.timestamps.next == null">{{ $t("labels.now") }}</span>

                        <span class="right">
                            <a :href="'/animals/' + animalId + '/weighing_schedules/' + aws.id + '/edit'">
                                <i class="material-icons">edit</i>
                            </a>
                        </span>

                        <span class="right">
                            <span v-show="aws.due_days == 0" class="new badge" v-bind:data-badge-caption="$t('labels.due')"> </span>
                            <span v-show="aws.due_days < 0" class="new badge red" v-bind:data-badge-caption="$t('labels.overdue')"> </span>
                        </span>
                    </div>
                    <div v-if="animal_weighing_schedules.length < 1">
                        <p>{{ $t('labels.no_data') }}</p>
                    </div>
                </div>

                <div class="card-action">
                    <a v-bind:href="'/animals/' + animalId + '/weighing_schedules/create'">{{ $t("buttons.add") }}</a>
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
        },

        load_data: function() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/animals/' + that.animalId + '/weighing_schedules?all=true&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    that.animal_weighing_schedules = data.data;
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
            .listen('AnimalWeighingSchedulePropertyUpdated', (e) => {
                this.update(e);
            }).listen('AnimalWeighingSchedulePropertyDeleted', (e) => {
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
