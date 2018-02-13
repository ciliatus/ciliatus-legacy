<template>
    <div :class="containerClasses" :id="containerId">
        <div class="timeline">
            <div class="timeline-event" v-for="caresheet in caresheets">
                <div class="timeline-date">
                    <!-- @TODO: there has to be a better way to do this -->
                    <span v-show="caresheet.timestamps.created_diff.days > 0"
                          class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="caresheet.timestamps.created">
                                {{ $t('units.days_ago', {val: caresheet.timestamps.created_diff.days}) }}
                    </span>
                    <span v-show="caresheet.timestamps.created_diff.days < 1 &&
                                  caresheet.timestamps.created_diff.hours > 0"
                          class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="caresheet.timestamps.created">
                                {{ $t('units.hours_ago', {val: caresheet.timestamps.created_diff.hours}) }}
                    </span>
                    <span v-show="caresheet.timestamps.created_diff.days < 1 &&
                                  caresheet.timestamps.created_diff.hours < 1 &&
                                  caresheet.timestamps.created_diff.minutes > 1"
                          class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="caresheet.timestamps.created">
                                {{ $t('units.minutes_ago', {val: caresheet.timestamps.created_diff.minutes}) }}
                    </span>
                    <span v-show="caresheet.timestamps.created_diff.days < 1 &&
                                  caresheet.timestamps.created_diff.hours < 1 &&
                                  caresheet.timestamps.created_diff.minutes < 2"
                          class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="caresheet.timestamps.created">
                                {{ $t('units.just_now') }}
                    </span>
                </div>
                <div class="card timeline-content">
                    <div class="card-content">
                        <h5>{{ caresheet.title }}</h5>
                    </div>

                    <div class="card-action">
                        <a v-bind:href="'/animals/' + belongsToId + '/caresheets/' + caresheet.id">{{ $t("buttons.details") }}</a>
                        <a v-bind:href="'/animals/' + belongsToId + '/caresheets/' + caresheet.id + '/delete'">{{ $t("buttons.delete") }}</a>
                    </div>
                </div>
                <div class="timeline-badge teal darken-2 white-text">
                    <i class="material-icons">content_paste</i>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            caresheets: []
        }
    },

    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        belongsToType: {
            type: String,
            default: 'Animal',
            required: false
        },
        belongsToId: {
            type: String,
            default: null,
            required: false
        },
        caresheetId: {
            type: String,
            default: null,
            required: false
        },
        containerId: {
            type: String,
            default: 'caresheet-list',
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
            this.caresheets.forEach(function(data, index) {
                if (data.id === a.caresheet.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.caresheets.push(a.caresheet)
            }
            else if (item !== null) {
                this.caresheets.splice(item, 1, a.caresheet);
            }

            this.$nextTick(function() {
                this.refresh_grid();
            });
        },

        delete: function(a) {
            var item = null;
            this.caresheets.forEach(function(data, index) {
                if (data.id === a.caresheet_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.caresheets.splice(item, 1);
            }

            this.$nextTick(function() {
                this.refresh_grid();
            });
        },

        refresh_grid: function() {
            $('.tooltipped').tooltip({delay: 50});
        },

        load_data: function() {
            var that = this;

            var source_url = '';
            if (this.caresheetId !== null) {
                source_url = '/api/v1/animals/caresheets/' + this.caresheetId
            }
            else {
                source_url = '/api/v1/animals/' + this.belongsToId + '/caresheets/?order[created_at]=desc&filter[belongsTo_type]=' + this.belongsToType + '&filter[belongsTo_id]=' + this.belongsToId + '&all=true';
            }

            window.eventHubVue.processStarted();
            $.ajax({
                url: source_url,
                method: 'GET',
                success: function (data) {
                    if (that.caresheetId !== null) {
                        that.caresheets = [data.data];
                    }
                    else {
                        that.caresheets = data.data;
                    }

                    that.$nextTick(function() {
                        that.refresh_grid();
                    });

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
            .listen('BiographyEntryEventUpdated', (e) => {
                this.update(e);
            }).listen('BiographyEntryEventDeleted', (e) => {
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
