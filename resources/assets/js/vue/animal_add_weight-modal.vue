<template>

    <div v-bind:id="containerId" class="modal">
        <form v-bind:action="'/api/v1/animals/' + animalId + '/weighings'" data-method="POST" v-on:submit="submit">
            <div class="modal-content">
                <h4>{{ $t("labels.add_weight") }}</h4>

                <input name="weight" id="weight" v-bind:placeholder="$t('labels.weight')+ '/g'">
                <label for="weight">{{ $t("labels.weight") }}/g</label>

                <input type="date" class="datepicker" :placeholder="$t('labels.date')" name="created_at">
                <label>{{ $t('labels.date') }}</label>
            </div>

            <div class="modal-footer">
                <button class="btn modal-action modal-close waves-effect waves-light">{{ $t("buttons.save") }}
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