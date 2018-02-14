<template>
    <div :class="wrapperClasses" v-if="pump.data">
        <div class="card">
            <div class="card-header">
                <i class="material-icons">rotate_right</i>
                {{ $tc("components.pumps", 1) }}
            </div>

            <div class="card-content">
                <span class="card-title activator">
                    {{ pump.data.name }}
                </span>

                <div>
                    {{ $t('labels.model') }}: {{ pump.data.model }}
                </div>
            </div>

            <div class="card-action">
                <a v-bind:href="'/pumps/' + pump.data.id">{{ $t("buttons.details") }}</a>
                <a v-bind:href="'/pumps/' + pump.data.id + '/edit'">{{ $t("buttons.edit") }}</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        pumpId: {
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

    computed: {
        pump () {
            return this.$store.state.pumps.filter(v => v.id = this.pumpId)[0];
        }
    },

    methods: {
        load_data: function() {
            this.$parent.ensureObject('pumps', this.pumpId);
        }
    },

    created: function() {
        this.load_data();
    }
}
</script>
