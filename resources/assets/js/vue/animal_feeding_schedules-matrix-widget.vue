<template>
    <div>
        <div :class="wrapperClasses">
            <table class="responsive highlight">
                <thead>
                <tr>
                    <th data-field="id" class="hide-on-med-and-down">
                        {{ $tc('components.animals', 1) }}
                    </th>

                    <th v-for="type in feeding_types">
                        {{ type.name }}
                    </th>
                </tr>
                </thead>

                <tbody>

                <tr v-for="animal in animals">
                    <td>
                        <a v-bind:href="'/animals/' + animal.id">{{ animal.display_name }}</a>
                    </td>
                    <td v-for="type in feeding_types">
                        <span v-for="schedule in get_animal_feeding_schedules_of_type(animal.id, type.name)">
                            <a v-bind:href="'/animals/' + animal.id + '/feeding_schedules/' + schedule.id + '/edit'">{{ schedule.interval_days }}</a>
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
            feeding_types: [],
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
        get_animal_feeding_schedules_of_type: function (animal_id, type) {
            return this.schedules.filter(function (el) {
                return el.type == type && el.animal.id == animal_id;
            })
        },
        load_data: function() {
            window.eventHubVue.processStarted();
            var that = this;

            /*
             * Load feeding types
             * then load animals
             * then load feeding schedules
             *
             * @TODO: First two should be parallel
             */

            /*
             * Load feeding types
             */
            $.ajax({
                url: '/api/v1/properties?filter[type]=AnimalFeedingType&raw',
                method: 'GET',
                success: function (data) {
                    that.feeding_types = data.data;

                    /*
                     * Load animals
                     */
                    $.ajax({
                        url: '/api/v1/animals?filter[death_date]=null&raw',
                        method: 'GET',
                        success: function (data) {
                            that.animals = data.data;

                            /*
                             * Load feeding schedules
                             */
                            $.ajax({
                                url: '/api/v1/animal_feeding_schedules?raw',
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
                             * END Load feeding schedules
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

                },
                error: function (error) {
                    console.log(JSON.stringify(error));
                    window.eventHubVue.processEnded();
                }
            });

            /*
             * END Load feeding types
             */


        }
    },

    created: function() {
        this.load_data();
    }
}
</script>
