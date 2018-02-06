<template>

    <div class="card">

        <div class="card-header">
            <i class="material-icons">playlist_play</i>
            {{ action_sequences.length }} {{ $tc("components.action_sequences", 2) }}
        </div>

        <div v-for="as in action_sequences">
            <div class="card-content">
                <div class="card-sub-header">
                    <a :href="'/action_sequences/' + as.id + '/edit'"><strong>{{ as.name }}</strong></a>

                    <a v-bind:href="'/action_sequences/' + as.id + '/edit'" class="right">
                        <i class="material-icons">edit</i>
                    </a>
                </div>

                <div class="row row-no-margin" v-for="asi in as.intentions">
                    <i class="material-icons">explore</i>

                    <span v-if="asi.intention === 'increase'">{{ $t('labels.increases') }}</span>
                    <span v-if="asi.intention === 'decrease'">{{ $t('labels.decreases') }}</span>

                    {{ $t('labels.' + asi.type) }}

                    <span v-show="asi.states.running">
                        <span class="new badge" v-bind:data-badge-caption="$t('labels.active')"> </span>
                    </span>
                </div>

                <div v-for="ast in as.triggers">
                    <i class="material-icons">flare</i> {{ ast.logical_sensor.name }} {{ $t('units.' + ast.reference_value_comparison_type) }} {{ ast.reference_value }}
                    <span v-show="ast.states.running">
                        <span class="new badge" v-bind:data-badge-caption="$t('labels.active')"> </span>
                    </span>
                </div>

                <div v-for="ass in as.schedules">
                    <i class="material-icons">schedule</i> {{ ass.timestamps.starts }} <i v-show="!ass.runonce">{{ $t("labels.daily") }}</i>
                    <span v-show="ass.states.running">
                        <span class="new badge" v-bind:data-badge-caption="$t('labels.active')"> </span>
                    </span>
                </div>
            </div>

            <div v-if="action_sequences.length < 1">
                {{ $t('tooltips.no_data') }}
            </div>

        </div>

        <div class="card-action">
            <a v-bind:href="'/action_sequences/create?preset[terrarium]=' + terrariumId">{{ $t("buttons.add") }}</a>
        </div>

    </div>

</template>

<script>
export default {

    data () {
        return {
            action_sequences: []
        }
    },

    props: {
        refreshTimeoutSeconds: {
            type: Number,
            default: null,
            required: false
        },
        action_sequenceId: {
            type: String,
            default: '',
            required: false
        },
        terrariumId: {
            type: String,
            default: '',
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
            this.action_sequences.forEach(function(data, index) {
                if (data.id === a.action_sequence.id) {
                    item = index;
                }
            });
            if (item === null) {
                this.action_sequences.push(a.action_sequence)
            }
            else if (item !== null) {
                this.action_sequences.splice(item, 1, a.action_sequence);
            }
        },

        delete: function(a) {
            var item = null;
            this.action_sequences.forEach(function(data, index) {
                if (data.id === a.action_sequences_id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.action_sequences.splice(item, 1);
            }
        },

        load_data: function() {
            window.eventHubVue.processStarted();
            var that = this;
            $.ajax({
                url: '/api/v1/action_sequences/' + that.action_sequenceId + '?with[]=schedules&with[]=triggers&' +
                     'with[]=intentions&with[]=terrarium&' + that.sourceFilter,
                method: 'GET',
                success: function (data) {
                    if (that.action_sequenceId !== '') {
                        that.action_sequences = [data.data];
                    }
                    else {
                        that.action_sequences = data.data;
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
            .listen('ActionSequenceUpdated', (e) => {
                this.update(e);
            }).listen('ActionSequenceDeleted', (e) => {
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
