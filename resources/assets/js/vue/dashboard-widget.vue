<template>
    <div :class="containerClasses" :id="containerId">

        <div :class="wrapperClasses" v-if="dashboard.terraria.critical.length > 0">
            <div class="card">
                <div class="card-content red darken-3 white-text">
                    {{ $tc("components.terraria", 2) }}
                </div>

                <div class="card-content red darken-2 white-text">
                    <span class="card-title activator truncate">
                        <span>{{ dashboard.terraria.critical.length }} {{ $tc("components.terraria", dashboard.terraria.critical.length) }} {{ $t("labels.critical") }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>

                    <div v-for="terrarium in dashboard.terraria.critical">
                        <p>
                            <a v-bind:href="'/terraria/' + terrarium.id" class="white-text">{{ terrarium.display_name }}</a>
                        </p>
                    </div>
                </div>

                <div class="card-reveal red darken-2 white-text">
                    <span class="card-title red darken-2 white-text"><i class="material-icons right">close</i></span>

                    <p>
                    </p>
                </div>

                <!--
                <div class="card-action teal lighten-1">
                    <a v-bind:href="''" class="white-text">{{ $t("buttons.details") }}</a>
                </div>
                -->
            </div>
        </div>
        <div :class="wrapperClasses" v-if="dashboard.animal_feeding_schedules.overdue.length > 0">
            <div class="card">
                <div class="card-content orange darken-3 white-text">
                    <span>{{ $tc("components.animal_feedings", 2) }} {{ $t("labels.overdue") }}</span>
                </div>

                <div class="card-content orange darken-2 white-text">
                    <span class="card-title activator truncate">
                        <span>{{ dashboard.animal_feeding_schedules.overdue.length }} {{ $tc("components.animal_feedings", dashboard.animal_feeding_schedules.overdue.length) }} {{ $t("labels.overdue") }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>

                    <div v-for="schedule in dashboard.animal_feeding_schedules.overdue">
                        <p>
                            <a v-bind:href="'/animals/' + schedule.animal.id" class="white-text">
                                {{ schedule.animal.display_name }}:
                            </a>

                            <a v-bind:href="'/animals/' + schedule.animal.id + '/feeding_schedules/' + schedule.id" class="white-text">
                                {{ schedule.type }}
                            </a>

                            ({{ $t("labels.since") }} {{ (schedule.due_days*-1) }} {{ $tc("units.days", (schedule.due_days*-1)) }})
                        </p>
                    </div>
                </div>

                <div class="card-reveal orange darken-2 white-text">
                    <span class="card-title orange darken-2 white-text"><i class="material-icons right">close</i></span>

                    <p>
                    </p>
                </div>

                <!--
                <div class="card-action teal lighten-1">
                    <a v-bind:href="''" class="white-text">{{ $t("buttons.details") }}</a>
                </div>
                -->
            </div>
        </div>
        <div :class="wrapperClasses" v-if="dashboard.terraria.critical.length < 1">
            <div class="card">
                <div class="card-content teal white-text">
                    {{ $tc("components.terraria", 2) }}
                </div>

                <div class="card-content teal lighten-1 white-text">
                    <span class="card-title activator truncate">
                        <span>0 {{ $tc("components.terraria", 2) }} {{ $t("labels.critical") }}</span>
                        <i class="material-icons">check</i>
                    </span>
                </div>
            </div>
        </div>
        <div :class="wrapperClasses">
            <div class="card">
                <div class="card-content teal white-text">
                    {{ $tc("components.animal_feedings", 2) }} {{ $t("labels.due") }}
                </div>

                <div class="card-content teal lighten-1 white-text">
                    <span class="card-title activator truncate">
                        <span>{{ dashboard.animal_feeding_schedules.due.length }} {{ $tc("components.animal_feedings", dashboard.animal_feeding_schedules.due.length) }} {{ $t("labels.due") }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>

                    <div v-for="schedule in dashboard.animal_feeding_schedules.due">
                        <p>
                            <a v-bind:href="'/animals/' + schedule.animal.id" class="white-text">
                                {{ schedule.animal.display_name }}:
                            </a>

                            <a v-bind:href="'/animals/' + schedule.animal.id + '/feeding_schedules/' + schedule.id" class="white-text">
                                {{ schedule.type }}
                            </a>
                        </p>
                    </div>
                </div>

                <div class="card-reveal teal lighten-1 white-text">
                    <span class="card-title teal lighten-2 white-text"><i class="material-icons right">close</i></span>

                    <p>
                    </p>
                </div>

                <!--
                <div class="card-action teal lighten-1">
                    <a v-bind:href="''" class="white-text">{{ $t("buttons.details") }}</a>
                </div>
                -->
            </div>
        </div>
        <div :class="wrapperClasses">
            <div class="card">
                <div class="card-content teal white-text">
                    {{ $tc("components.terraria", 2) }}
                </div>

                <div class="card-content teal lighten-1 white-text">
                    <span class="card-title activator truncate">
                        <span>{{ dashboard.terraria.ok.length }} {{ $tc("components.terraria", dashboard.terraria.ok.length) }} {{ $t("labels.ok") }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>
                </div>

                <div class="card-reveal teal lighten-1 white-text">
                    <span class="card-title teal lighten-1 white-text"><i class="material-icons right">close</i></span>

                    <p>
                    </p>
                </div>

                <!--
                <div class="card-action teal lighten-1">
                    <a v-bind:href="''" class="white-text">{{ $t("buttons.details") }}</a>
                </div>
                -->
            </div>
        </div>

    </div>
</template>

<script>
export default {

    data () {
        return {
            dashboard: []
        }
    },

    props: {
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
            default: 'dashboard-masonry-grid',
            required: false
        }
    },

    methods: {
        updateTerrarium: function(e) {
            this.refresh_grid();
        },
        deleteTerrarium: function(e) {
            this.refresh_grid();
        },
        updateAnimalFeedingSchedule: function(e) {
            var item = null;

            /*
                Check in due array
            */
            this.dashboard.animal_feeding_schedules.due.forEach(function(data, index) {
                if (data.id === e.animal_feeding_schedule.id) {
                    item = index;
                }
            });
            if (item !== null) {
                if (e.animal_feeding_schedule.due_days === 0) {
                    this.dashboard.animal_feeding_schedules.due.splice(item, 1, e.animal_feeding_schedule);
                }
                else {
                    this.dashboard.animal_feeding_schedules.due.splice(item, 1);
                }
            }

            /*
                Check in overdue array
                if not found under due
             */
            if (item === null) {
                this.dashboard.animal_feeding_schedules.overdue.forEach(function(data, index) {
                    if (data.id === e.animal_feeding_schedule.id) {
                        item = index;
                    }
                });

                if (item !== null) {
                    if (e.animal_feeding_schedule.due_days >= 0) {
                        this.dashboard.animal_feeding_schedules.overdue.splice(item, 1);
                    }
                    else {
                        this.dashboard.animal_feeding_schedules.overdue.splice(item, 1, e.animal_feeding_schedule);
                    }
                }
            }

            /*
                Push if not found
            */
            if (item === null) {
                if (e.animal_feeding_schedule.due_days == 0) {
                    this.dashboard.animal_feeding_schedules.due.push(e.animal_feeding_schedule);
                }
                else if (e.animal_feeding_schedule.due_days < 0) {
                    this.dashboard.animal_feeding_schedules.overdue.push(e.animal_feeding_schedule);
                }
            }

            this.refresh_grid();
        },

        deleteAnimalFeedingSchedule: function(e) {
            this.dashboard.animal_feeding_schedules.due.forEach(function(data, index) {
                if (data.id === e.animal_feeding_schedule.id) {
                    this.dashboard.animal_feeding_schedules.due.splice(index, 1);
                }
            });

            this.dashboard.animal_feeding_schedules.overdue.forEach(function(data, index) {
                if (data.id === e.animal_feeding_schedule.id) {
                    this.dashboard.animal_feeding_schedules.overdue.splice(index, 1);
                }
            });

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
    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('TerrariumUpdated', (e) => {
                this.updateTerrarium(e);
            }).listen('TerrariumDeleted', (e) => {
                this.deleteTerrarium(e);
            }).listen('AnimalFeedingScheduleUpdated', (e) => {
                this.updateAnimalFeedingSchedule(e);
            }).listen('AnimalFeedingScheduleDeleted', (e) => {
                this.deleteAnimalFeedingSchedule(e);
        });

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/dashboard',
            method: 'GET',
            success: function (data) {
                that.dashboard = data.data;
                that.refresh_grid();
                window.eventHubVue.processEnded();
            },
            error: function (error) {
                window.notification('An error occured :(', 'red darken-1 text-white');
                console.log(error);
                window.eventHubVue.processEnded();
            }
        });
    }
}
</script>
