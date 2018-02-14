<template>
    <div :class="containerClasses">
        <div :class="wrapperClasses">
            <div class="card">
                <div class="card-header">
                    <i class="material-icons">attach_file</i>
                    {{ files.length }} {{ $tc("labels.files", 2) }}
                </div>

                <div class="card-content">
                    <div>
                        <div class="chip" v-for="file in files">
                            <i class="material-icons">insert_drive_file</i>
                            <a v-bind:href="'/files/' + file.id">{{ file.display_name }}</a> <i>{{ (file.size / 1024 / 1024).toFixed(2) }} MB</i>
                        </div>
                    </div>
                </div>

                <div class="card-action">
                    <a v-bind:href="'/files/create?preset[belongsTo_type]=' + belongsTo_type + '&preset[belongsTo_id]=' + belongsTo_id">{{ $t("buttons.add") }}</a>
                    <a v-bind:href="'/files/?filter[belongsTo_type]=' + belongsTo_type + '&filter[belongsTo_id]=' + belongsTo_id">{{ $t("buttons.details") }}</a>
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
        },
        wrapperClasses: {
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
