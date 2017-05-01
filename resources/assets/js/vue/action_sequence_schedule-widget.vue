<template>

</template>

<script>
export default {
    data () {
        return {
            action_sequence_schedules: []
        }
    },

    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        assId: {
            type: String,
            default: '',
            required: false
        },
        sourceFilter: {
            type: String,
            default: 'filter[last_finished_at]=nottoday',
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function(ass) {
            var item = null;
            this.action_sequence_schedules.forEach(function(data, index) {
                if (data.id === ass.action_sequence_schedule.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.action_sequence_schedules.push(ass.action_sequence_schedule);
            }
            else if (item !== null) {
                this.action_sequence_schedules.splice(item, 1, ass.action_sequence_schedule);
            }
        },

        delete: function(ass) {
            var item = null;
            this.action_sequence_schedules.forEach(function(data, index) {
                if (data.id === ass.action_sequence_schedule_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.action_sequence_schedules.splice(item, 1);
            }
        },

        load_data: function() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: uri,
                method: 'GET',
                success: function (data) {
                    if (that.assId !== '') {
                        that.action_sequence_schedules = [data.data];
                    }
                    else {
                        that.action_sequence_schedules = data.data;
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
                .listen('ActionSequenceScheduleUpdated', (e) => {
                this.update(e);
        }).listen('ActionSequenceScheduleDeleted', (e) => {
                this.delete(e);
        });

        var uri = '';
        if (this.assid === '') {
            uri = '/api/v1/action_sequence_schedules/?raw=true&' + this.sourceFilter;
        }
        else {
            uri = '/api/v1/action_sequence_schedules/' + this.assid;
        }

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
