<template>
    <div :class="containerClasses">
        <div class="col s12 m12 l4">
            <div class="card" v-if="file.data">
                <div class="card-header">
                    <i class="material-icons">attach_file</i>
                    {{ $tc("labels.files", 1) }}
                </div>

                <div class="card-content">
                    <div>
                        <span>{{ $t("labels.size") }}: {{ (file.data.size / 1024 / 1024).toFixed(2) }} MB</span><br />
                        <span>{{ $t("labels.type") }}: {{ file.data.mimetype }}</span>
                    </div>

                    <div>
                        <p>
                            <strong>{{ $t('labels.associated_with') }}:</strong>
                        </p>
                        <p v-for="model in file.data.models">
                            <i class="material-icons">{{ model.icon }}</i>
                            <a :href="model.url" v-if="!model.name && !model.display_name">{{ model.title }}</a>
                            <a :href="model.url" v-if="model.name && !model.display_name">{{ model.name }}</a>
                            <a :href="model.url" v-if="model.display_name">{{ model.display_name }}</a>
                            <span class="right">
                                    <a class="red-text" :href="'/files/associate/' + model.class + '/' + model.id + '/' + file.data.id">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </span>
                        </p>
                    </div>
                </div>

                <div class="card-action">
                    <a v-bind:href="'/files/' + file.data.id + '/edit'">{{ $t("buttons.edit") }}</a>
                    <a v-bind:href="'/files/' + file.data.id + '/download/' + file.data.display_name">{{ $t("buttons.download") }}</a>
                </div>
            </div>
            <div v-else>
                <loading-card-widget> </loading-card-widget>
            </div>
        </div>

        <div class="col s12 m12 l8" v-if="file.data && file.data.is_image && file.data.thumb !== undefiend">
            <div class="card" v-if="file.data">
                <div class="card-header">
                    <span>{{ $t('labels.preview') }}</span>
                </div>
                <div class="card-content">
                    <img :src="file.data.thumb.path_external" />
                </div>
            </div>
            <div v-else>
                <loading-card-widget> </loading-card-widget>
            </div>
        </div>
    </div>
</template>

<script>
    import LoadingCardWidget from './loading-card-widget';

    export default {
        props: {
            fileId: {
                type: String,
                default: '',
                required: false
            },
            containerClasses: {
                type: String,
                default: '',
                required: false
            }
        },

        components: {
            'loading-card-widget': LoadingCardWidget
        },

        computed: {
            file () {
                return this.$store.state.files.filter(f => f.id = this.fileId)[0];
            }
        },

        methods: {
            load_data: function() {
                this.$parent.ensureObject('files', this.fileId, null, ['properties']);
            }
        },

        created: function() {
            this.load_data();
        }
    }
</script>