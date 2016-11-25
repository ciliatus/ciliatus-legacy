<template>
    <div>
        <div :class="wrapperClasses" v-for="controlunit in controlunits">
            <div class="card">
                <div class="card-content teal lighten-2 white-text">
                    {{ $tc("components.controlunits", 2) }}
                </div>

                <div class="card-content">
                    <span class="card-title activator truncate">
                        <span>{{ controlunit.name }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>
                </div>

                <div class="card-action">
                    <a v-bind:href="'/controlunits/' + controlunit.id">{{ $t("buttons.details") }}</a>
                    <a v-bind:href="'/controlunits/' + controlunit.id + '/edit'">{{ $t("buttons.edit") }}</a>
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
            controlunits: []
        }
    },

    props: {
        controlunitId: {
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
            this.controlunits.forEach(function(data, index) {
                if (data.id === cu.controlunit.id) {
                    item = index;
                }
            });
            if (item === null && this.subscribeAdd === true1) {
                this.controlunits.push(cu.controlunit);
            }
            else if (item !== null) {
                this.controlunits.splice(item, 1, cu.controlunit);
            }
        },

        delete: function(cu) {
            if (this.subscribeDelete !== true) {
                return;
            }
            var item = null;
            this.controlunits.forEach(function(data, index) {
                if (data.id === cu.controlunit.id) {
                    item = index;
                }
            });

            if (item !== null) {
                this.controlunits.splice(item, 1);
            }
        }
    },

    created: function() {
        window.echo.private('dashboard-updates')
                .listen('ControlunitUpdated', (e) => {
                this.update(e);
        }).listen('ControlunitDeleted', (e) => {
                this.delete(e);
        });

        window.eventHubVue.processStarted();
        var that = this;
        $.ajax({
            url: '/api/v1/controlunits/' + that.controlunitId,
            method: 'GET',
            success: function (data) {
                if (that.controlunitId !== '') {
                    that.controlunits = [data.data];
                }
                else {
                    that.controlunits = data.data;
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
