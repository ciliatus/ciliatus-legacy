<template>

    <div v-bind:id="containerId" class="modal">
        <form v-bind:action="'/api/v1/animals/' + animalId + '/weighings'" data-method="POST" v-on:submit="submit">
            <div class="modal-content">
                <h4>{{ $t("labels.add_weight") }}</h4>

                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" name="weight" id="weight">
                        <label for="weight">{{ $t("labels.weight") }}/g</label>
                    </div>

                    <div class="input-field col s12">
                        <input type="text" :id="'date-weight-created-' + animalId" class="datepicker" name="created_at">
                        <label :for="'date-feeding-created-' + animalId">{{ $t('labels.date') }}</label>
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
            let that = this;
            $('.modal').modal();
            this.$nextTick(() => {
                $('#date-weight-created-' + that.animalId).datepicker({
                    format: 'yyyy-mm-dd',
                    autoClose: true,
                    defaultDate: new Date(),
                    setDefaultDate: true
                });
            });
        }
    }
</script>