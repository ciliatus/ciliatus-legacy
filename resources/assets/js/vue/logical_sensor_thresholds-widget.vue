<template>

    <div class="card">
        
        <div class="card-header">
            <i class="material-icons">vertical_align_center</i>
            {{ $tc("components.logical_sensor_thresholds", 2) }}
        </div>

        <template v-for="ls in logical_sensors">

        <div class="card-content">

            <div class="card-sub-header">
                <a v-bind:href="'/logical_sensors/' + ls.id"><strong>{{ ls.name }}</strong></a>
            </div>

            <div class="row row-no-margin" v-for="th in ls.thresholds">
                <i class="material-icons">vertical_align_center</i>

                <span>{{ th.timestamps.starts }}:</span>
                <span v-if="th.rawvalue_lowerlimit !== null">{{ $t("labels.min_short") }}: {{ th.rawvalue_lowerlimit }}</span>
                <span v-if="th.rawvalue_upperlimit !== null">{{ $t("labels.max_short") }}: {{ th.rawvalue_upperlimit }}</span>

                <span class="right">
                    <a v-bind:href="'/logical_sensor_thresholds/' + th.id + '/edit'">
                        <i class="material-icons">edit</i>
                    </a>
                </span>
            </div>
            <div class="row row-no-margin" v-if="ls.thresholds.length < 1">
                {{ $t("tooltips.no_data") }}
            </div>

        </div>

        <div class="card-action">
            <a v-bind:href="'/logical_sensor_thresholds/create?preset[belongsTo_type]=LogicalSensor&preset[belongsTo_id]=' + ls.id">{{ $t("buttons.add") }}</a>
        </div>

        </template>

    </div>

</template>

<script>
export default {

    data () {
        return {
            logical_sensors: []
        }
    },

    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        sourceFilter: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function(a) {
            var item = null;
            this.logical_sensors.forEach(function(data, index) {
                if (data.id === a.logical_sensor.id) {
                    item = index;
                }
            });
            if (item !== null) {
                this.logical_sensors.splice(item, 1, a.logical_sensor);
            }
        },

        delete: function(a) {
            var item = null;
            this.logical_sensors.forEach(function(data, index) {
                if (data.id === a.logical_sensor_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.logical_sensors.splice(item, 1);
            }
        },

        load_data: function() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/logical_sensors?with[]=thresholds&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    that.logical_sensors = data.data;

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
            .listen('LogicalSensorUpdated', (e) => {
                this.update(e);
            }).listen('LogicalSensorDeleted', (e) => {
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
