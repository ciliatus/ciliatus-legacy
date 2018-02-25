<template>
    <div :class="wrapperClasses">
        <div class="card" v-if="pump.data">
            <div class="card-header">
                <i class="material-icons">rotate_right</i>
                {{ $tc("labels.pumps", 1) }}
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
        <div v-else>
            <loading-card-widget> </loading-card-widget>
        </div>
    </div>
</template>

<script>
    import LoadingCardWidget from './loading-card-widget';

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

        components: {
            'loading-card-widget': LoadingCardWidget
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
