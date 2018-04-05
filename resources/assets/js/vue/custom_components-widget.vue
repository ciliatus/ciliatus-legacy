<template>
    <div :class="wrapperClasses">
        <div class="card" v-if="custom_component.data">
            <div class="card-header">
                <i :class="'mdi mdi-18px mdi-' + custom_component.data.type.icon"></i>
                {{ custom_component.data.type.name_singular }}
            </div>

            <div class="card-content">
                <span class="card-title">
                    {{ custom_component.data.name }}
                </span>

                <div v-for="(property, index) in custom_component.data.properties">
                    {{ property.name }}: {{ property.value }}<br />
                </div>
            </div>

            <div class="card-action">
                <a v-bind:href="'/custom_components/' + custom_component.data.id">{{ $t("buttons.details") }}</a>
                <a v-bind:href="'/custom_components/' + custom_component.data.id + '/edit'">{{ $t("buttons.edit") }}</a>
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

        data () {
            return {
            }
        },

        props: {
            customComponentId: {
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
            custom_component () {
                let component = this.$store.state.custom_components.filter(p => p.id = this.customComponentId);
                return component.length > 0 ? component[0] : {};
            }
        },

        methods: {
            load_data: function() {
                this.$parent.ensureObject('custom_components', this.customComponentId, null, ['properties', 'states', 'type']);
            }
        },

        created: function() {
            let that = this;
            setTimeout(function() {
                that.load_data();
            }, 2000);
        }
    }
</script>