<template>
    <div :class="containerClasses" :id="containerId">
        <div class="timeline">
            <div class="timeline-event" v-for="entry in entries">
                <div class="timeline-date">
                    <!-- @TODO: there has to be a better way to do this -->
                    <span v-show="entry.timestamps.created_diff.days > 0"
                          class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="entry.timestamps.created">
                                {{ $t('units.days_ago', {val: entry.timestamps.created_diff.days}) }}
                    </span>
                    <span v-show="entry.timestamps.created_diff.days < 1 &&
                                  entry.timestamps.created_diff.hours > 0"
                          class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="entry.timestamps.created">
                                {{ $t('units.hours_ago', {val: entry.timestamps.created_diff.hours}) }}
                    </span>
                    <span v-show="entry.timestamps.created_diff.days < 1 &&
                                  entry.timestamps.created_diff.hours < 1 &&
                                  entry.timestamps.created_diff.minutes > 1"
                          class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="entry.timestamps.created">
                                {{ $t('units.minutes_ago', {val: entry.timestamps.created_diff.minutes}) }}
                    </span>
                    <span v-show="entry.timestamps.created_diff.days < 1 &&
                                  entry.timestamps.created_diff.hours < 1 &&
                                  entry.timestamps.created_diff.minutes < 2"
                          class="tooltipped" data-position="bottom" data-delay="50" v-bind:data-tooltip="entry.timestamps.created">
                                {{ $t('units.just_now') }}
                    </span>
                </div>
                <div class="card timeline-content">
                    <div class="card-content">
                        <h5>{{ entry.title }}</h5>

                        <p v-html="entry.text"> </p>
                    </div>

                    <div class="card-action">
                        <a v-bind:href="'/biography_entries/' + entry.id + '/edit'">{{ $t("buttons.edit") }}</a>
                    </div>
                </div>
                <div class="timeline-badge teal darken-2 white-text">
                    <i v-if="entry.category" class="material-icons tooltipped" data-position="top" data-delay="50"
                       v-bind:data-tooltip="entry.category.name">
                        {{ entry.category.icon }}
                    </i>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            entries: []
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
            default: null,
            required: false
        },
        belongsToId: {
            type: String,
            default: null,
            required: false
        },
        entryId: {
            type: String,
            default: null,
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
            this.entries.forEach(function(data, index) {
                if (data.id === a.entry.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.entries.push(a.animal)
            }
            else if (item !== null) {
                this.entries.splice(item, 1, a.entry);
            }

            this.$nextTick(function() {
                this.refresh_grid();
            });
        },

        delete: function(a) {
            var item = null;
            this.entries.forEach(function(data, index) {
                if (data.id === a.entry_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.entries.splice(item, 1);
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
            if (this.entryId !== null) {
                source_url = '/api/v1/biography_entries/' + this.entryId
            }
            else {
                source_url = '/api/v1/biography_entries/?order[created_at]=desc&filter[belongsTo_type]=' + this.belongsToType + '&filter[belongsTo_id]=' + this.belongsToId + '&raw=true';
            }

            window.eventHubVue.processStarted();
            $.ajax({
                url: source_url,
                method: 'GET',
                success: function (data) {
                    if (that.entryId !== null) {
                        that.entries = [data.data];
                    }
                    else {
                        that.entries = data.data;
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
            .listen('BiographyEntryUpdated', (e) => {
                this.update(e);
            }).listen('BiographyEntryDeleted', (e) => {
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
