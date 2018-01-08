<template>
    <div :class="containerClasses">
        <div :class="wrapperClasses" v-for="file in files">
            <div class="col s12 m12 l4">
                <div class="card">
                    <div class="card-header">
                        <i class="material-icons">attach_file</i>
                        {{ $tc("components.files", 1) }}
                    </div>

                    <div class="card-content">
                        <div>
                            <span>{{ $t("labels.size") }}: {{ (file.size / 1024 / 1024).toFixed(2) }} MB</span><br />
                            <span>{{ $t("labels.type") }}: {{ file.mimetype }}</span>
                        </div>

                        <div>
                            <p>
                                <strong>{{ $t('labels.associated_with') }}:</strong>
                            </p>
                            <p v-for="model in file.models">
                                <i class="material-icons">{{ model.icon }}</i>
                                <a :href="model.url" v-if="model.name && !model.display_name">{{ model.name }}</a>
                                <a :href="model.url" v-if="model.display_name">{{ model.display_name }}</a>
                                <span class="right">
                                    <a class="red-text" :href="'/files/associate/' + model.class + '/' + model.id + '/' + file.id">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="card-action">
                        <a v-bind:href="'/files/' + file.id + '/edit'">{{ $t("buttons.edit") }}</a>
                        <a v-bind:href="'/files/' + file.id + '/download/' + file.display_name">{{ $t("buttons.download") }}</a>
                    </div>
                </div>
            </div>

            <div class="col s12 m12 l8" v-if="file.is_image && file.thumb !== undefiend">
                <div class="card">
                    <div class="card-header">
                        <span>{{ $t('labels.preview') }}</span>
                    </div>
                    <div class="card-content">
                        <img :src="file.thumb.path_external" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    data () {
        return {
            files: []
        }
    },

    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        fileId: {
            type: String,
            default: '',
            required: false
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        },
        belongsTo_type: {
            type: String,
            default: '',
            required: false
        },
        belongsTo_id: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function(a) {
            var item = null;
            this.files.forEach(function(data, index) {
                if (data.id === a.file.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.files.push(a.animal)
            }
            else if (item !== null) {
                this.files.splice(item, 1, a.animal);
            }
        },

        delete: function(a) {
            var item = null;
            this.files.forEach(function(data, index) {
                if (data.id === a.file_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.files.splice(item, 1);
            }
        },

        load_data: function() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/files/' + that.fileId + '?with[]=properties&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    if (that.fileId !== '') {
                        that.files = [data.data];
                    }
                    else {
                        that.files = data.data;
                    }

                    window.eventHubVue.processEnded();
                },
                error: function (error) {
                    console.log(JSON.stringify(error));
                    window.eventHubVue.processEnded();
                }
            });
        }
    },

    created: function() {
        window.echo.private('dashboard-updates')
            .listen('FileUpdated', (e) => {
                this.update(e);
            }).listen('FileDeleted', (e) => {
                this.delete(e);
            });

        var that = this;
        setTimeout(function() {
            that.load_data();
        }, 100);

        if (this.refreshTimeoutSeconds !== null) {
            setInterval(function() {
                that.load_data();
            }, this.refreshTimeoutSeconds * 1000)
        }

    }
}
</script>
