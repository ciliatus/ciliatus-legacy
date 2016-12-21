<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight">
                <thead>
                <tr>
                    <th>
                        {{ $tc('components.animals', 1) }}
                    </th>
                    <th>
                        {{ $t('labels.scheduled') }}
                    </th>
                </tr>
                </thead>

                <tbody>

                <tr v-for="animal in animals">
                    <td>
                        <a v-bind:href="'/animals/' + animal.id">{{ animal.display_name }}</a>
                    </td>
                    <td>
                        <span v-for="schedule in get_animal_weighing_schedules(animal.id)">
                            <a v-bind:href="'/animals/' + animal.id + '/weighing_schedules/' + schedule.id + '/edit'">
                                {{ schedule.interval_days }}:
                            </a>
                            <i>{{ schedule.timestamps.next }} ({{ schedule.due_days }} {{ $tc('units.days', schedule.due_days) }})</i>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            animals: [],
            schedules: []
        }
    },

    props: {
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        get_animal_weighing_schedules: function (animal_id) {
            return this.schedules.filter(function (el) {
                return el.animal.id == animal_id;
            })
        },
        load_data: function() {
            window.eventHubVue.processStarted();
            var that = this;

            /*
             * load animals
             * then load weighing schedules
             */
            $.ajax({
                url: '/api/v1/animals?filter[death_date]=null&raw',
                method: 'GET',
                success: function (data) {
                    that.animals = data.data;

                    /*
                     * Load weighing schedules
                     */
                    $.ajax({
                        url: '/api/v1/animal_weighing_schedules?raw',
                        method: 'GET',
                        success: function (data) {
                            that.schedules = data.data;
                            that.$nextTick(function() {
                                $('.dropdown-button').dropdown();
                            });
                            window.eventHubVue.processEnded();
                        },
                        error: function (error) {
                            console.log(JSON.stringify(error));
                            window.eventHubVue.processEnded();
                        }
                    });
                    window.eventHubVue.processEnded();

                    /*
                     * END Load weighing schedules
                     */
                },
                error: function (error) {
                    console.log(JSON.stringify(error));
                    window.eventHubVue.processEnded();
                }
            });

            window.eventHubVue.processEnded();

            /*
             * END Load animals
             */

        }
    },

    created: function() {
        this.load_data();
    }
}
</script>
