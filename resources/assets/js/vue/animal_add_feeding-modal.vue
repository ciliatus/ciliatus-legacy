<template>

    <div v-bind:id="'modal_just_fed_' + animalId" class="modal" style="min-height: 800px;">
        <form v-bind:action="'/api/v1/animals/' + animalId + '/feedings'" data-method="POST" v-on:submit="submit">
            <div class="modal-content">
                <h4>{{ $t("labels.just_fed") }}</h4>


                <select name="meal_type" v-if="feedingTypes.length > 0">
                    <option v-for="ft in feedingTypes" v-bind:value="ft.name">{{ ft.name }}</option>
                </select>
                <span v-else>
                    <strong>{{ $t('tooltips.no_feeding_types') }}</strong>
                </span>
                <label>{{ $t("labels.meal_type") }}</label>

                <input type="date" class="datepicker" :placeholder="$t('labels.date')" name="created_at">
                <label>{{ $t('labels.date') }}</label>
            </div>

            <div class="modal-footer">
                <button v-if="feedingTypes.length > 0" class="btn modal-action modal-close waves-effect waves-light" type="submit">{{ $t("buttons.save") }}
                    <i class="material-icons left">send</i>
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
            $('.datepicker').pickadate({
                selectMonths: true,
                selectYears: 15,
                format: 'yyyy-mm-dd',
            });
        }
    }
</script>