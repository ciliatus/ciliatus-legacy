<template>
    <div>
        <div :class="wrapperClasses" v-for="pump in pumps">
            <div class="card">
                <div class="card-content teal lighten-1 white-text">
                    {{ $tc("components.pumps", 2) }}
                </div>

                <div class="card-content">
                    <span class="card-title activator truncate">
                        <span>{{ pump.name }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>
                </div>

                <div class="card-action">
                    <a v-bind:href="'/pumps/' + pump.id">{{ $t("buttons.details") }}</a>
                    <a v-bind:href="'/pumps/' + pump.id + '/edit'">{{ $t("buttons.edit") }}</a>
                </div>

                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4"><i class="material-icons right">close</i></span>
                    <p>

                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            pumps: []
        }
    },

    props: {
        pumpId: {
            type: String,
            default: '',
            required: false
        },
        subscribeAdd: {
            type: Boolean,
            default: true,
            required: false
        },
        subscribeDelete: {
            type: Boolean,
            default: true,
            required: false
        },
        wrapperClasses: {
            type: String,
            default: '',
            required: false
        }
    },

    methods: {
        update: function(cu) {
            var item = null;
            this.pumps.forEach(function(data, index) {
                if (data.id === cu.pump.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true1) {
                this.pumps.push(cu.pump);
            }
            else if (item !== null) {
                this.pumps.splice(item, 1, cu.pump);
            }
        },

        delete: function(cu) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.pumps.forEach(function(data, index) {
                if (data.id === cu.pump.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.pumps.splice(item, 1);
            }
        }
    },

    created: function() {
        window.echo.private('dashboard-updates')
                .listen('pumpUpdated', (e) => {
                this.update(e);
        }).listen('pumpDeleted', (e) => {
                this.delete(e);
        });

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/pumps/' + that.pumpId + '?raw',
            method: 'GET',
            success: function (data) {
                if (that.pumpId !== '') {
                    that.pumps = [data.data];
                }
                else {
                    that.pumps = data.data;
                }

                window.eventHubVue.processEnded();
            },
            error: function (error) {
                alert(JSON.stringify(error));
                window.eventHubVue.processEnded();
            }
        });
    }
}
</script>
