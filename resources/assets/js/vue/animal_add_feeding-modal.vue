<template>

    <div v-bind:id="'modal_just_fed_' + animalId" class="modal">
        <form v-bind:action="'/api/v1/animals/' + animalId + '/feedings'" data-method="POST" v-on:submit="submit">
            <div class="modal-content">
                <h4>{{ $t("labels.just_fed") }}</h4>

                <div class="row">
                    <div class="input-field col s12">
                        <select id="meal_type" name="meal_type" v-if="feedingTypes.length > 0">
                            <option v-for="ft in feedingTypes" v-bind:value="ft">{{ ft }}</option>
                        </select>
                        <span v-else>
                            <strong>{{ $t('tooltips.no_feeding_types') }}</strong>
                        </span>
                        <label for="meal_type">{{ $t("labels.meal_type") }}</label>
                    </div>

                    <div class="input-field col s12">
                        <input type="text" id="date-feeding-created" class="datepicker" name="created_at">
                        <label for="date-feeding-created">{{ $t('labels.date') }}</label>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn modal-action modal-close waves-effect waves-light">
                    {{ $t("buttons.save") }}
                    <i class="mdi mdi-18px mdi-floppy left"></i>
                </button>

                <button class="btn modal-action modal-close waves-effect waves-light" type="reset">
                    {{ $t("buttons.close") }}
                    <i class="mdi mdi-18px mdi-close left"></i>
                </button>
            </div>
        </form>
    </div>

</template>

<script>

    export default {

        data () {
            return {}
        },

        props: {
            animalId: {
                type: String,
                required: true
            },
            feedingTypes: {
                type: Array,
                required: true
            },
            containerId: {
                type: String,
                required: true
            }
        },

        methods: {
            submit: function(e) {
                window.submit_form(e);
            },
        },

        created: function() {
            $('.modal').modal();
            this.$nextTick(() => {
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoClose: true,
                    defaultDate: new Date(),
                    setDefaultDate: true
                });
            });
        }
    }
</script>