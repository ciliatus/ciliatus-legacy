<template>
    <div>
        <div :class="wrapperClasses">
            <div class="card">
                <div class="card-header">
                    <i class="material-icons">schedule</i>
                    {{ $tc("labels.animal_weighing_schedules", 2) }}
                </div>

                <div class="card-content">
                    <div class="row no-margin" v-for="schedule in schedules">
                        <template v-if="schedule.data">
                            <span v-show="schedule.data.timestamps.next != null">{{ schedule.data.timestamps.next }}</span>

                            <span v-show="schedule.data.timestamps.next == null">{{ $t("labels.now") }}</span>

                            <span class="right">
                                <a :href="'/animals/' + animalId + '/weighing_schedules/' + schedule.data.id + '/edit'">
                                    <i class="material-icons">edit</i>
                                </a>
                            </span>

                            <span class="right">
                                <span v-show="schedule.data.due_days == 0" class="new badge" v-bind:data-badge-caption="$t('labels.due')"> </span>
                                <span v-show="schedule.data.due_days < 0" class="new badge red" v-bind:data-badge-caption="$t('labels.overdue')"> </span>
                            </span>
                        </template>
                    </div>
                    <div v-if="schedules.length < 1">
                        <p>{{ $t('labels.no_data') }}</p>
                    </div>
                </div>

                <div class="card-action">
                    <a :href="'/animals/' + animalId + '/weighing_schedules/create'">{{ $t("buttons.add") }}</a>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        data () {
            return {
                ids: []
            }
        },

        props: {
            wrapperClasses: {
                type: String,
                default: '',
                required: false
            },
            animalId: {
                type: String,
                required: true
            }
        },

        computed: {
            schedules () {
                let that = this;
                return this.$store.state.animal_weighing_schedules.filter(function(s) {
                    return that.ids.includes(s.id) && s.data !== null
                });
            }
        },

        methods: {
            load_data: function() {
                let that = this;

                $.ajax({
                    url: '/api/v1/animals/' + that.animalId + '/weighing_schedules?all=true',
                    method: 'GET',
                    success: function (data) {
                        that.ids = data.data.map(v => v.id);

                        that.$parent.ensureObjects('animal_weighing_schedules', that.ids, data.data);
                    },
                    error: function (error) {
                        console.log(JSON.stringify(error));
                    }
                });
            }
        },

        created: function() {
            this.load_data();
        }
    }
</script>