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
                        <span class="card-title activator truncate">
                            <span>{{ file.display_name }}</span>
                            <i class="material-icons right">more_vert</i>
                        </span>
                        <p>
                            <span>{{ $t("labels.size") }}: {{ (file.size / 1024 / 1024).toFixed(2) }} MB</span><br />
                            <span>{{ $t("labels.type") }}: {{ file.mimetype }}</span>
                        </p>
                    </div>

                    <div class="card-action">
                        <a v-bind:href="'/files/' + file.id + '/edit'">{{ $t("buttons.edit") }}</a>
                        <a v-bind:href="'/files/' + file.id + '/download/' + file.display_name">{{ $t("buttons.download") }}</a>
                    </div>

                    <div class="card-reveal">
                        <span class="card-title"><i class="material-icons right">close</i></span>
                        <p>

                        </p>
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
                url: '/api/v1/files/?with[]=properties&' + that.fileId + that.sourceFilter,
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
