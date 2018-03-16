<template>
    <div :class="wrapperClasses">
        <div class="card" v-if="generic_component.data">
            <div class="card-header">
                <i :class="'mdi mdi-18px mdi-' + generic_component.data.type.icon"></i>
                {{ generic_component.data.type.name_singular }}
            </div>

            <div class="card-content">
                <span class="card-title">
                    {{ generic_component.data.name }}
                </span>

                <div v-for="(property, index) in generic_component.data.properties">
                    {{ property.name }}: {{ property.value }}<br />
                </div>
            </div>

            <div class="card-action">
                <a v-bind:href="'/generic_components/' + generic_component.data.id">{{ $t("buttons.details") }}</a>
                <a v-bind:href="'/generic_components/' + generic_component.data.id + '/edit'">{{ $t("buttons.edit") }}</a>
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
            genericComponentId: {
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
            generic_component () {
                let component = this.$store.state.generic_components.filter(p => p.id = this.genericComponentId);
                return component.length > 0 ? component[0] : {};
            }
        },

        methods: {
            load_data: function() {
                this.$parent.ensureObject('generic_components', this.genericComponentId, null, ['properties', 'states', 'type']);
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